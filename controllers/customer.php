<?php
// No direct access.
defined('_JEXEC') or die;

class WarrantyControllerCustomer extends JControllerLegacy
{
    function __construct(){
        $this->input = JFactory::getApplication()->input;
        parent::__construct();
    }

    public function register(){
        $name = $this->input->getString('name', '');
        $username = $this->input->getString('username', '');
        $email = $this->input->getString('email', '');
        $password = $this->input->getString('password', '');
        $password1 = $this->input->getString('password1', '');
        $imei = $this->input->getString('imei', '');
        $code = $this->input->getString('code', '');

        if($code == JFactory::getSession()->get('warranty_captcha_code')){
            $db = JFactory::getDbo();

            $db->setQuery('SELECT id FROM #__warranty_requests WHERE imei='.$db->quote($imei));
            if($db->loadResult()){
                exit(json_encode(array('rs'=>true, 'msg'=>'Số imei này đã được đăng ký bời một người dùng khác. \nXin vui lòng liên hệ Trung Tâm Chăm Sóc Khách Hàng.')));
            }

            $db->setQuery('SELECT * FROM #__warranty_products WHERE imei='.$db->quote($imei));
            $product = $db->loadObject();

            if($product){
                $user = JUser::getInstance(0);
                $data = array();
                $data['id'] = 0;
                $data['name'] = $name;
                $data['username'] = $username;
                $data['email'] = $email;
                $data['password'] = $password;
                $data['password1'] = $password1;

                $usersConfig = JComponentHelper::getParams( 'com_users' );
                $newUsertype = $usersConfig->get( 'new_usertype' );
                if($newUsertype){
                    $data['groups'] = array($newUsertype);
                }

                // Bind the data.
                if (!$user->bind($data)) {
                    exit(json_encode(array('rs'=>false, 'msg'=>$user->getError())));
                }

                // Store the data.
                if (!$user->save()){
                    exit(json_encode(array('rs'=>false, 'msg'=>$user->getError())));
                }

                $query = $db->getQuery(true);
                $query
                    ->insert('#__warranty_requests')
                    ->set('customer_id='.$user->id)
                    ->set('imei='.$db->quote($imei))
                    ->set('status='.$db->quote($product->status))
                    ->set('created='.$db->quote(JFactory::getDate()->toSql()));
                $db->setQuery($query);
                if($db->execute()){
                    if($product->status){
                        exit(json_encode(array('rs'=>true, 'msg'=>'Tài khoản đã được tạo thành công. \nImei đã được kích hoạt.')));
                    }else{
                        exit(json_encode(array('rs'=>true, 'msg'=>'Tài khoản đã được tạo thành công. \nImei chưa được kích hoạt, chúng tôi sẽ kích hoạt tự động cho bạn trong 72 giờ.')));
                    }
                }else{
                    $user->delete();
                    exit(json_encode(array('rs'=>false, 'msg'=>$db->getErrorMsg())));
                }
            }else{
                exit(json_encode(array('rs' => false, 'msg' => 'Số imei không tìm thấy. \nXin vui lòng liên hệ Trung Tâm Chăm Sóc Khách Hàng.')));
            }
        }else{
            exit(json_encode(array('rs' => false, 'msg' => 'Mã xác nhận không hợp lệ!')));
        }
    }
}