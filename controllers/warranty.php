<?php
// No direct access.
defined('_JEXEC') or die;

class WarrantyControllerWarranty extends JControllerLegacy
{
    function __construct(){
        $this->input = JFactory::getApplication()->input;
        parent::__construct();
    }

    function generateCode($characters) {
        /* list all possible characters, similar looking characters and vowels have been removed */
        $possible = strtoupper('23456789bcdfghjkmnpqrstvwxyz');
        $code = '';
        $i = 0;
        while ($i < $characters) {
            $code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
            $i++;
        }
        return strtolower($code);
    }

    function CaptchaSecurityImages($width='120', $height='36', $characters='6', $type, $color) {
        $font = JPATH_COMPONENT.'/assets/font/monofont.ttf';
        $code = $this->generateCode($characters);
        JFactory::getSession()->set('warranty_captcha_code', $code);
        /* font size will be 70% of the image height */
        $font_size = $height * 0.7;
        $image = imagecreate($width, $height) or die('Cannot initialize new GD image stream');
        /* set the colours */
        $background_color = imagecolorallocate($image, 255, 255, 255);
        switch ($color){
            case 'green': $text_color = imagecolorallocate($image, 114, 161, 1); break;
            case 'red': $text_color = imagecolorallocate($image, 233, 0, 0); break;
            case 'orange': $text_color = imagecolorallocate($image, 255, 160, 0); break;
            case 'blue': $text_color = imagecolorallocate($image, 0, 137, 208); break;
            default: $text_color = imagecolorallocate($image, 56, 56, 56); break;
        }
        $noise_color = imagecolorallocate($image, 197, 197, 197);
        /* generate random dots in background */
        for( $i=0; $i<($width*$height)/3; $i++ ) {
            imagefilledellipse($image, mt_rand(0,$width), mt_rand(0,$height), 1, 1, $noise_color);
        }
        /* generate random lines in background */
        for( $i=0; $i<($width*$height)/150; $i++ ) {
            imageline($image, mt_rand(0,$width), mt_rand(0,$height), mt_rand(0,$width), mt_rand(0,$height), $noise_color);
        }
        /* create textbox and add text */
        $textbox = imagettfbbox($font_size, 0, $font, $code) or die('Error in imagettfbbox function');
        $x = ($width - $textbox[4])/2;
        $y = ($height - $textbox[5])/2;
        imagettftext($image, $font_size, 0, $x, $y, $text_color, $font , $code) or die('Error in imagettftext function');
        /* output captcha image to browser */
        header('Content-Type: image/jpeg');
        imagejpeg($image);
        imagedestroy($image);
    }

    public function generalCaptcha(){
        $width = $this->input->getInt('width', 120);
        $height = $this->input->getInt('height', 40);
        $characters = $this->input->getInt('characters', 5);
        $type = $this->input->getString('type','');
        $color = $this->input->getString('color','blue');
        $captcha = $this->CaptchaSecurityImages($width, $height, $characters, $type, $color);
        exit($captcha);
    }

    public static function parseTimeLeft($timeLeft){
        if($timeLeft){
            $parse = array();
            if($timeLeft['days']){
                if($timeLeft['days'] == 1) $parse[] = $timeLeft['days'].' '.JText::_('Ngày');
                else $parse[] = $timeLeft['days'].' '.JText::_('Ngày');
            }
            if($timeLeft['hours']){
                if($timeLeft['hours'] == 1) $parse[] = $timeLeft['hours'].' '.JText::_('Giờ');
                else $parse[] = $timeLeft['hours'].' '.JText::_('Giờ');
            }
            if($timeLeft['minutes']){
                if($timeLeft['minutes'] == 1) $parse[] = $timeLeft['minutes'].' '.JText::_('Phút');
                else $parse[] = $timeLeft['minutes'].' '.JText::_('Phút');
            }
            if($timeLeft['seconds']){
                if($timeLeft['seconds'] == 1) $parse[] = $timeLeft['seconds'].' '.JText::_('Giây');
                else $parse[] = $timeLeft['seconds'].' '.JText::_('Giây');
            }
            if(count($parse) >= 2) return $parse[0].' '.$parse[1];
            if(count($parse) == 1) return $parse[0];
        }
        return JText::_('Đã hết hạn');
    }

    public static function timeLeft($date){
        $uts = array();
        $uts['start']      =    strtotime(JFactory::getDate()) ;
        $uts['end']        =    strtotime($date);
        if( $uts['start']!==-1 && $uts['end']!==-1 ){
            if( $uts['end'] > $uts['start'] ){
                $diff    =    $uts['end'] - $uts['start'];
                if( $days=intval((floor($diff/86400))) )
                    $diff = $diff % 86400;
                if( $hours=intval((floor($diff/3600))) )
                    $diff = $diff % 3600;
                if( $minutes=intval((floor($diff/60))) )
                    $diff = $diff % 60;
                $diff    =    intval( $diff );
                if($diff) $minutes ++;

                return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
            }else{
                return false;
            }
        }
    }

    public function checkWarranty(){
        $manufacturer = $this->input->getString('manufacturer', '');
        $imei = $this->input->getString('imei', '');
        $code = $this->input->getString('code', '');

        if($code == JFactory::getSession()->get('warranty_captcha_code')){
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('a.*, m.product, c.title AS product_title, c.catid, c.alias, cat.alias AS category_alias')
                ->from('#__warranty_products a')
                ->join('LEFT', '#__warranty_models m ON SUBSTRING(a.imei, 1, 9) = m.code')
                ->join('LEFT', '#__content c ON c.id = m.product')
                ->join('LEFT', '#__categories cat ON cat.id = c.catid')
                ->where('a.manufacturer='.$db->quote($manufacturer))
                ->where('a.imei='.$db->quote($imei));
            $db->setQuery($query);
            $phone = $db->loadObject();

            if($phone){
                if($phone->image) $phone->image = JUri::root().$phone->image;
                elseif($phone->model && $phone->color && is_file(JPATH_SITE.'/images/mau-sac/'.str_replace(' ', '-', strtolower($phone->model.'-'.$phone->color).'.png'))){
                    $phone->image = JUri::root().'images/mau-sac/'.str_replace(' ', '-', strtolower($phone->model.'-'.$phone->color).'.png');
                }
                $timeLeft = $this->timeLeft(date("Y-m-d", strtotime(date("Y-m-d H:i:s", strtotime($phone->active)) . " + 1 year")));
                $phone->time_left = $this->parseTimeLeft($timeLeft);

                if($phone->product){
                    require_once JPATH_SITE . '/components/com_content/helpers/route.php';
                    $phone->slug    = $phone->product . ':' . $phone->alias;
                    $phone->catslug = $phone->catid . ':' . $phone->category_alias;
                    $phone->link = JRoute::_(ContentHelperRoute::getArticleRoute($phone->slug, $phone->catslug));
                }else{
                    $phone->link = '';
                }

                exit(json_encode(array('rs' => true, 'phone' => $phone)));
            }else{
                exit(json_encode(array('rs' => false, 'msg' => 'Số imei không tìm thấy hoặc chưa được kích hoạt bảo hành hoặc bạn bạn đã chọn sai nhà sản xuất. Xin vui lòng liên hệ Trung Tâm Chăm Sóc Khách Hàng.')));
            }
        }else{
            exit(json_encode(array('rs' => false, 'msg' => 'Mã xác nhận không hợp lệ!')));
        }
    }
}