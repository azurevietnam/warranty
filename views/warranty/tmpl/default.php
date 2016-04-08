<?php
// No direct access
defined('_JEXEC') or die;

$db = JFactory::getDbo();
$db->setQuery('SELECT DISTINCT manufacturer AS name FROM #__warranty_products');
$list = $db->loadObjectList();
$options = '';
if($list) foreach($list as $item) if($item->name){
    $options .= '<option value="'.$item->name.'">'.$item->name.'</option>';
}
?>
<div id="com_warranty" style="text-align: center">
    <h4>Tra cứu thông tin bảo hành sản phẩm</h4>
    <div>
        <div style="margin-bottom: 10px">
            <select id="manufacturer" name="manufacturer" style="margin: 0 auto; text-align: center;">
                <option value="">- Nhà sản xuất -</option>
                <?php echo $options;?>
            </select>
        </div>
        <div style="margin-bottom: 10px">
            <input type="text" id="imei_number" name="imei_number" placeholder="Số Imei" style="margin: 0 auto; text-align: center;">
        </div>
        <div style="margin-bottom: 10px">
            <img id="verify_image" class="Captcha" alt="Captcha" src="" style="margin-right: 20px; margin-bottom: 5px; display: none;"/>
            <a id="refresh_verify_image" class="refreshCaptcha" onclick="jQuery('#verify_image').attr('src', 'index.php?option=com_warranty&task=warranty.generalCaptcha&' + Math.random());" href="Javascript:void(0)">Mã xác nhận mới</a>
            <input type="text" id="verify_code" name="verify_code" placeholder="Mã xác nhận" style="margin: 0 auto; text-align: center">
        </div>
        <div style="margin-bottom: 10px">
            <button id="searchProduct" type="button" class="btn">Tra Cứu</button>
        </div>
    </div>

    <table class="table table-bordered" id="warranty_rs" style="display: none">
        <tr>
            <th>Số Imei</th>
            <td id="warranty_rs_imei"></td>
        </tr>
        <tr>
            <th>Model</th>
            <td><span id="warranty_rs_model"></span> - <span id="warranty_rs_color"></span> <span> <a href="" id="warranty_rs_model_link" target="_blank"> - Chi tiết - </a></span></td>
        </tr>
        <tr id="warranty_rs_customer" style="display: none">
            <th>Khách hàng</th>
            <td>
                <div id="warranty_rs_customer_name"></div>
                <div style="font-style: italic" id="warranty_rs_customer_address"></div>
                <div style="font-style: italic" id="warranty_rs_customer_phone"></div>
                <div style="font-style: italic" id="warranty_rs_customer_note"></div>
            </td>
        </tr>
        <tr>
            <th>Mua tại cửa hàng</th>
            <td>
                <div id="warranty_rs_shop_name"></div>
                <div style="font-style: italic" id="warranty_rs_shop_address"></div>
            </td>
        </tr>
        <tr>
            <th>Ngày kích hoạt</th>
            <td id="warranty_rs_active"></td>
        </tr>
        <tr>
            <th>Thời gian bảo hành còn lại</th>
            <td id="warranty_rs_time_left"></td>
        </tr>
        <tr>
            <th>Hình ảnh sản phẩm</th>
            <td><img src="" alt="" id="warranty_rs_image" style="display: none; height: 200px"/></td>
        </tr>
        <tr>
            <th>Tình trạng bảo hành</th>
            <td id="warranty_rs_status"></td>
        </tr>
        <tr>
            <th>Mô tả</th>
            <td id="warranty_rs_note"></td>
        </tr>
    </table>

    <script type="text/javascript">
        jQuery(function($){
            setTimeout(function(){
                $('#refresh_verify_image').trigger('click');
                $('#verify_image').show();
            }, 1000);

            $('#searchProduct').click(function(){
                $('#warranty_rs').hide();
                var manufacturer = $('#manufacturer').val();
                var imei = $('#imei_number').val();
                var code = $('#verify_code').val();
                if(manufacturer == ''){
                    alert('Xin vui lòng chọn nhà sản xuất');
                    return false;
                }else if(imei == ''){
                    alert('Xin vui lòng nhập số imei!');
                    return false;
                }else if(code == ''){
                    alert('Xin vui lòng nhập mã xác nhận!');
                    return false;
                }

                $.ajax({
                    url: 'index.php?option=com_warranty&task=warranty.checkWarranty',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        manufacturer: manufacturer,
                        imei: imei,
                        code: code
                    },
                    success: function(data){
                        $('#refresh_verify_image').trigger('click');
                        if(data.rs){
                            $('#warranty_rs_imei').text(data.phone.imei);
                            $('#warranty_rs_model').text(data.phone.model);
                            $('#warranty_rs_color').text(data.phone.color);
                            if(data.phone.link){
                                $('#warranty_rs_model_link').attr('href', data.phone.link).attr('title', data.phone.product_title).show();
                            }else{
                                $('#warranty_rs_model_link').hide();
                            }
                            if(data.phone.image){
                                $('#warranty_rs_image').attr('src', data.phone.image).show();;
                            }else{
                                $('#warranty_rs_image').hide();
                            }
                            if(data.phone.customer_name){
                                $('#warranty_rs_customer').show();
                                $('#warranty_rs_customer_name').text(data.phone.customer_name);
                                if(data.phone.customer_address) {
                                    $('#warranty_rs_customer_address').text(data.phone.customer_address).show();
                                }else{
                                    $('#warranty_rs_customer_address').hide();
                                }

                                if(data.phone.customer_phone) {
                                    $('#warranty_rs_customer_phone').text(data.phone.customer_phone).show();
                                }else{
                                    $('#warranty_rs_customer_phone').hide();
                                }

                                if(data.phone.customer_note) {
                                    $('#warranty_rs_customer_note').text(data.phone.customer_note).show();
                                }else{
                                    $('#warranty_rs_customer_note').hide();
                                }
                            }else{
                                $('#warranty_rs_customer').hide();
                            }
                            $('#warranty_rs_shop_name').text(data.phone.shop_name);
                            $('#warranty_rs_shop_address').text(data.phone.shop_address);
                            $('#warranty_rs_active').text(data.phone.active);
                            $('#warranty_rs_time_left').text(data.phone.time_left);
                            $('#warranty_rs_status').html(data.phone.phone_status);
                            $('#warranty_rs_note').html(data.phone.note);
                            $('#warranty_rs').show();
                        }else{
                            alert(data.msg);
                        }

                    }
                });
            })
        })
    </script>
</div>

