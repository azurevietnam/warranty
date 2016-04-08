<?php
/**
 * @version     1.0.0
 * @package     com_warranty
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Chau Dang Khoa <boyupdatetofuture@gmail.com> - 
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('behavior.modal');
JHtml::_('formbehavior.chosen', 'select');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_warranty/assets/css/warranty.css');

$user	= JFactory::getUser();
$userId	= $user->get('id');

$input = JFactory::getApplication()->input;
$imei = $input->getString('filter_imei', '');

if($imei){
	$db = JFactory::getDbo();
	$db->setQuery('SELECT * FROM #__warranty_products WHERE imei='.$db->quote($imei));
	$product = $db->loadObject();
	if(!$product) JFactory::getApplication()->enqueueMessage('Không tìm thấy số imei này!');
}
?>

<form action="<?php echo JRoute::_('index.php?option=com_warranty&view=papers'); ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<?php if(!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
		<h1>In phiếu bảo hành</h1>
		<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left">
				<input type="text" name="filter_imei" id="filter_imei" value="<?php echo $imei;?>" placeholder="Số Imei" />
			</div>
			<div class="btn-group pull-left">
				<button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
			</div>
		</div>
		<?php if($product){?>
			<h1>Thông tin bảo hành</h1>
			<div class="form-horizontal" id="warranty_form">
				<div class="row-fluid">
					<div class="span6">
						<div class="control-group">
							<div class="control-label">Tên khách hàng</div>
							<div class="controls"><input type="text" id="customer_name" value="<?php echo $product->customer_name;?>"></div>
						</div>
						<div class="control-group">
							<div class="control-label">Địa chỉ</div>
							<div class="controls"><input type="text" id="customer_address" value="<?php echo $product->customer_address;?>"></div>
						</div>
						<div class="control-group">
							<div class="control-label">Điện thoại</div>
							<div class="controls"><input type="text" id="customer_phone" value="<?php echo $product->customer_phone;?>"></div>
						</div>
						<div class="control-group">
							<div class="control-label">Loại máy (Model)</div>
							<div class="controls"><input type="text" id="product_model" value="<?php echo $product->model.' - '.$product->color;?>"></div>
						</div>
						<div class="control-group">
							<div class="control-label">Số Imei</div>
							<div class="controls">
								<a href="index.php?option=com_warranty&task=product.edit&id=<?php echo $product->id?>" target="_blank"><?php echo $imei?></a>
								<input type="hidden" id="product_imei" value="<?php echo $product->imei;?>">
							</div>
						</div>
					</div>
					<div class="span6">
						<div class="control-group">
							<div class="control-label">Phụ kiện kèm theo ghi nhận</div>
							<div class="controls"><textarea id="product_note"></textarea></div>
						</div>
						<div class="control-group">
							<div class="control-label">Hiện trạng máy</div>
							<div class="controls"><textarea type="text" id="product_state"><?php echo $product->phone_status;?></textarea></div>
						</div>
						<div class="control-group">
							<div class="control-label">Lỗi do NSX/NSD</div>
							<div class="controls"><input type="text" id="product_error"></div>
						</div>
						<div class="control-group">
							<div class="control-label">Ngày tháng năm</div>
							<div class="controls">
								<input class="input-mini" type="text" id="product_date" value="<?php echo date('d');?>">
								<input class="input-mini" type="text" id="product_month" value="<?php echo date('m');?>">
								<input class="input-mini" type="text" id="product_year" value="<?php echo date('Y');?>">
							</div>
						</div>
					</div>
				</div>
			</div>

			<h1>Xem trước</h1>

			<div id="warranty_paper">
				<table id="warranty_paper_table" class="table">
					<tr>
						<td style="position: relative">
							<img src="<?php echo JUri::root().'images/logo-aimica.png';?>" alt="" style="width: 150px; position: absolute;">
							<h1 style="text-align: center">Phiếu bảo hành</h1>
						</td>
					</tr>
					<tr>
						<td><b>Tên khách hàng:</b> <span id="customer_name_val"><?php echo $product->customer_name;?></span></td>
					</tr>
					<tr>
						<td><b>Địa chỉ:</b> <span id="customer_address_val"><?php echo $product->customer_address;?></span></td>
					</tr>
					<tr>
						<td><b>Điện thoại:</b> <span id="customer_phone_val"><?php echo $product->customer_phone;?></span></td>
					</tr>
					<tr>
						<td><span><b>Loại máy (Model):</b> <span id="product_model_val"><?php echo $product->model.' - '.$product->color;?></span></span> <span style="float: right"><b>Số Imei: </b><?php echo $product->imei;?></span></td>
					</tr>
					<tr>
						<td><b>Phụ kiện kèm theo ghi nhận:</b> <span id="product_note_val"></span></td>
					</tr>
					<tr>
						<td><b>Hiện trạng máy:</b> <span id="product_state_val"><?php echo $product->phone_status;?></span></td>
					</tr>
					<tr>
						<td><b>Lỗi do NSX/NSD:</b> <span id="product_error_val"></span></td>
					</tr>
					<tr>
						<td style="border: 0">
							<div style="float: right; height: 80px; margin-right: 50px">
								Ngày <span id="product_date_val"><?php echo date('d');?></span> tháng <span id="product_month_val"><?php echo date('m');?></span> năm <span id="product_year_val"><?php echo date('Y');?></span>
							</div>
						</td>
					</tr>
				</table>

				<style type="text/css">
					#warranty_paper_table{
						border: 1px solid #ddd;
						margin-bottom: 20px;
					}
					#warranty_paper_table.table th, #warranty_paper_table.table td{
						border-top: 0;
						border-bottom: 1px solid #ddd;
						padding: 8px 15px;
					}
				</style>
			</div>

			<div class="pull-right"><a href="javascript:void(0)" id="warranty_print" class="btn btn-success"><i class="icon-print"></i> In</a></div>

			<script type="text/javascript">
				jQuery(function($){
					$('#warranty_form input, #warranty_form textarea').change(function(){
						var id = '#' + $(this).attr('id') + '_val';
						$(id).html($(this).val().replace("\n", '<br/>'));
					})

					$('#warranty_print').click(function(){
						var divContents = $("#warranty_paper").html();
						var printWindow = window.open('', '', 'height=600,width=800');
						printWindow.document.write('<html><head><title>Phiếu bảo hành</title><link rel="stylesheet" href="<?php echo JUri::root()?>administrator/templates/isis/css/template.css" type="text/css">');
						printWindow.document.write('</head><body >');
						printWindow.document.write(divContents);
						printWindow.document.write(divContents);
						printWindow.document.write('</body></html>');
						printWindow.document.close();
						printWindow.print();
					})
				});
			</script>
		<?php }?>
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

