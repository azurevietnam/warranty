<?php
// No direct access
defined('_JEXEC') or die;
?>
<div id="com_warranty" style="text-align: center" class="container">
    <div class="container">
        <h5>
            <?php if($this->items){?>
            <span style="display: inline-block">So sánh sản phẩm này với</span><br/>
            <span style="display: inline-block; margin-left: 7px">
                <select id="warranty_select_product">
                    <option value="">Chọn sản phẩm</option>
                    <?php foreach($this->items as $item){?>
                        <option value="<?php echo $item->product?>"><?php echo $item->product_title?></option>
                    <?php }?>
                </select>
            </span>
            <span style="display: inline-block"> và </span>
            <span style="display: inline-block; margin-left: 7px">
            <select id="warranty_select_product1">
                <option value="">Chọn sản phẩm</option>
                <?php foreach($this->items as $item){?>
                    <option value="<?php echo $item->product?>"><?php echo $item->product_title?></option>
                <?php }?>
            </select>
        </span>
            <?php }?>
        </h5>
        <hr/>
        <?php if(!$this->item){?>
            <div class="alert alert-error">Không tìm thấy sản phẩm</div>
        <?php }else{ ?>
            <div style="width: 100%; float: left;border-right: 1px solid #e4e4e4;padding: 10px;position: relative" class="warranty_compare_product">
                <h4><a href="<?php echo $this->item->link;?>"><?php echo $this->item->product_title;?></a></h4>
                <h5><?php echo $this->item->attribs->get('title_caption');?></h5>
                <div>
                    <img src="<?php if($this->item->images && $this->item->images->get('image_intro')) echo JUri::root().$this->item->images->get('image_intro');?>" alt="" style="height: 200px"/>
                </div>
                <div>
                    <table class="table table-bordered">
                        <?php
                        for($i=1;$i<=10;$i++){
                            $field = 'field'.$i;
                            if($this->item->$field) echo '<tr><td style="text-align: center">'.$this->item->$field.'</td></tr>';
                        }
                        ?>
                        <?php if($this->item->description){?>
                            <tr><td style="text-align: center"><?php echo $this->item->description?></td></tr>
                        <?php }?>
                    </table>
                </div>
                <span class="cp-vs" style="display: none">VS</span>
            </div>
            <div class="warranty_compare_product" id="warranty_compare_product" style="width: 0px; float: left;border-right: 1px solid #e4e4e4;padding: 10px;position: relative; display: none">
                <h4><a href="" id="warranty_compare_product_title"></a></h4>
                <h5 id="warranty_compare_product_price"></h5>
                <div>
                    <img id="warranty_compare_product_image" src="" alt="" style="height: 200px"/>
                </div>
                <div>
                    <table class="table table-bordered" id="warranty_compare_product_spec"></table>
                </div>
                <span class="cp-vs">VS</span>
            </div>
            <div class="warranty_compare_product" id="warranty_compare_product1" style="width: 0px; float: left;padding: 10px;position: relative; display: none">
                <h4><a href="" id="warranty_compare_product_title1"></a></h4>
                <h5 id="warranty_compare_product_price1"></h5>
                <div>
                    <img id="warranty_compare_product_image1" src="" alt="" style="height: 200px"/>
                </div>
                <div>
                    <table class="table table-bordered" id="warranty_compare_product_spec1"></table>
                </div>
            </div>
        <?php }?>
    </div>
</div>
<hr/>
<style>
    .cp-vs {
        display: block;
        width: 35px;
        height: 35px;
        line-height: 35px;
        color: #65A0ED;
        border-radius: 50%;
        position: absolute;
        top: 70px;
        right: -19px;
        z-index: 100;
        font-weight: 700;
        font-size: 15px;
        border: 1px solid #eaeaea;
        background: #fff;
    }
</style>
<script type="text/javascript">
    jQuery(function($){
        $('#warranty_select_product, #warranty_select_product1').change(function(){
            if(!$('#warranty_select_product').val() && !$('#warranty_select_product1').val()){
                $('.warranty_compare_product').eq(0).css('border-right-width', '0px').find('.cp-vs').hide();
                $('.warranty_compare_product').eq(0).animate({width: '100%'}, 500);
                $('.warranty_compare_product').eq(1).hide();
                $('.warranty_compare_product').eq(2).hide();
            }

            if($('#warranty_select_product').val() && $('#warranty_select_product1').val()){
                $('.warranty_compare_product').animate({width: '33.3%'}, 500);
                $('.warranty_compare_product').css('border-right-width', '1px').find('.cp-vs').show();
            }
            if($('#warranty_select_product').val() && !$('#warranty_select_product1').val()){
                $('.warranty_compare_product').eq(0).css('border-right-width', '1px').find('.cp-vs').show();
                $('.warranty_compare_product').eq(0).animate({width: '50%'}, 500);
                $('.warranty_compare_product').eq(1).css('border-right-width', '0px').find('.cp-vs').hide();
                $('.warranty_compare_product').eq(1).animate({width: '50%'}, 500);
                $('.warranty_compare_product').eq(2).hide();
            }
            if(!$('#warranty_select_product').val() && $('#warranty_select_product1').val()){
                $('.warranty_compare_product').eq(0).css('border-right-width', '1px').find('.cp-vs').show();
                $('.warranty_compare_product').eq(0).animate({width: '50%'}, 500);
                $('.warranty_compare_product').eq(1).hide();
                $('.warranty_compare_product').eq(2).animate({width: '50%'}, 500);
            }

            var suffix = '';
            if($(this).attr('id') == 'warranty_select_product1') suffix = '1';
            var value = $(this).val();
            if(value){
                $.ajax({
                    url: 'index.php?option=com_warranty&task=compare.getProduct',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        id: value
                    },
                    success: function(data){
                        if(data.rs){
                            $('#warranty_compare_product_title'+suffix).text(data.product.product_title).attr('href', data.product.link);
                            $('#warranty_compare_product_price'+suffix).text(data.product.attribs.title_caption);
                            $('#warranty_compare_product_image'+suffix).attr('src', data.product.images.image_intro);
                            var html = '';
                            for(var i=0;i<=10;i++){
                                var field = 'field'+i;
                                if(data.product[field]) html += '<tr><td style="text-align: center">'+data.product[field]+'</td></tr>';
                            }
                            if(data.product.description) html += '<tr><td style="text-align: center">'+data.product.description+'</td></tr>';
                            $('#warranty_compare_product_spec'+suffix).html(html);
                            $('#warranty_compare_product'+suffix).show();
                        }else{
                            alert(data.msg);
                            $('#warranty_compare_product'+suffix).hide();
                        }
                    }
                });
            }
        });
        $('#warranty_select_product').trigger('change');
        $('#warranty_select_product1').trigger('change');
    });
</script>