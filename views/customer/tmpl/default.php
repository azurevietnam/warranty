<?php
// No direct access
defined('_JEXEC') or die;
?>
<div id="com_warranty" style="text-align: center">
    <h4>Khách hàng đăng ký</h4>
    <div>
        <div style="margin-bottom: 10px">
            <input type="text" id="name" name="name" placeholder="Họ tên" style="margin: 0 auto; text-align: center;">
        </div>
        <div style="margin-bottom: 10px">
            <input type="text" id="username" name="username" placeholder="Tên đăng nhập" style="margin: 0 auto; text-align: center;">
        </div>
        <div style="margin-bottom: 10px">
            <input type="text" id="email" name="email" placeholder="Địa chỉ email" style="margin: 0 auto; text-align: center;">
        </div>
        <div style="margin-bottom: 10px">
            <input type="password" id="password" name="password" placeholder="Mật khẩu" style="margin: 0 auto; text-align: center;">
        </div>
        <div style="margin-bottom: 10px">
            <input type="password" id="password1" name="password1" placeholder="Xác nhận mật khẩu" style="margin: 0 auto; text-align: center;">
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
            <button id="submit" type="button" class="btn">Đăng ký</button>
        </div>
    </div>

    <script type="text/javascript">
        jQuery(function($){
            setTimeout(function(){
                $('#refresh_verify_image').trigger('click');
                $('#verify_image').show();
            }, 1000);

            $('#submit').click(function(){
                var name = $('#name').val();
                var username = $('#username').val();
                var email = $('#email').val();
                var password = $('#password').val();
                var password1 = $('#password1').val();
                var imei = $('#imei_number').val();
                var code = $('#verify_code').val();
                var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);

                if(name == ''){
                    alert('Xin vui lòng nhập họ tên!');
                    return false;
                }else if(username == ''){
                    alert('Xin vui lòng nhập tên đăng nhập!');
                    return false;
                }else if(email == ''){
                    alert('Xin vui lòng nhập địa chỉ email!');
                    return false;
                }else if(!pattern.test(email)){
                    alert('Địa chỉ email không hợp lệ!');
                    return false;
                }else if(password == ''){
                    alert('Xin vui lòng nhập mật khẩu!');
                    return false;
                }else if(password1 == ''){
                    alert('Xin vui lòng nhập xác nhận mật khẩu!');
                    return false;
                }else if(password != password1){
                    alert('Mật khẩu và xác nhận mật khẩu không giống nhau!');
                    return false;
                }else if(imei == ''){
                    alert('Xin vui lòng nhập số imei!');
                    return false;
                }else if(code == ''){
                    alert('Xin vui lòng nhập mã xác nhận!');
                    return false;
                }

                $.ajax({
                    url: 'index.php?option=com_warranty&task=customer.register',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        name: name,
                        username: username,
                        email: email,
                        password: password,
                        password1: password1,
                        imei: imei,
                        code: code
                    },
                    success: function(data){
                        $('#refresh_verify_image').trigger('click');
                        alert(data.msg);
                        if(data.rs){
                            $('#name, #username, #email, #password, #password1, #imei_number, #verify_code').val('');
                        }
                    }
                });
            })
        })
    </script>
</div>

