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
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$canOrder	= $user->authorise('core.edit.state', 'com_warranty');
$saveOrder	= $listOrder == 'a.ordering';
if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_warranty&task=products.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'productList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
$sortFields = $this->getSortFields();

$listPrint = $this->state->get('filter.print_list');
?>
<script type="text/javascript">
	Joomla.orderTable = function() {
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>') {
			dirn = 'asc';
		} else {
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}

	jQuery(document).ready(function () {
		jQuery('#clear-search-button').on('click', function () {
			jQuery('#filter_search').val('');
			jQuery('#adminForm').submit();
		});
	});

	addPrint = function(id){
		var $ip = jQuery('input[name="filter_print_list"]');
		var pv = $ip.val();
		if(!pv){
			$ip.val(id);
			jQuery('#adminForm').submit();
		}else{
			var pv_array = pv.split(',');
			if(pv_array.indexOf(id) == -1){
				pv_array.push(id);
				$ip.val(pv_array.join(','));
				jQuery('#adminForm').submit();
			}
		}
	}

	removePrint = function(id){
		var $ip = jQuery('input[name="filter_print_list"]');
		var pv = $ip.val();
		if(pv == id){
			$ip.val('');
			jQuery('#adminForm').submit();
		}else{
			var pv_array = pv.split(',');
			var index = pv_array.indexOf(id.toString());
			if(index > -1){
				pv_array.splice(index, 1);
				$ip.val(pv_array.join(','));
				jQuery('#adminForm').submit();
			}
		}
	}
</script>

<?php
//Joomla Component Creator code to allow adding non select list filters
if (!empty($this->extra_sidebar)) {
    $this->sidebar .= $this->extra_sidebar;
}
?>

<form action="<?php echo JRoute::_('index.php?option=com_warranty&view=warranties'); ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<?php if(!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
		<?php if($listPrint){
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('DISTINCT a.*');
			$query->from('`#__warranty_warranties` AS a');
			$query->select('s.code AS shop_code, s.name AS shop_name, s.address AS shop_address, s.phone AS shop_phone')
				->join('LEFT', '#__warranty_shops s ON s.id=a.shop_id');
			$query->select('p.model, p.id AS product_id')
				->join('LEFT', '#__warranty_products p ON p.imei=a.imei');
			$query->where('a.id IN ('.$listPrint.')');
			$db->setQuery($query);
			$prints = $db->loadObjectList();
		?>
		<div style="padding: 10px; border: 1px solid #dddddd; border-radius: 4px;">
			<div id="warranty_paper">
				<table id="warranty_paper_table" class="table">
					<tbody>
						<tr><td colspan="7" style="border-bottom: 0; font-weight: bold; padding-top: 5px">TRUNG TÂM BẢO HÀNH AIMICA VIỆT NAM</td></tr>
						<tr><td colspan="7" style="border-bottom: 0">Địa chỉ: 34 Nguyễn Ngọc Lộc, P.14, Q.10, TPHCM</td></tr>
						<tr><td colspan="7" style="border-bottom: 0">Điện thoại: (08) 66.818.862 <span style="float: right; font-weight: bold">Số : 09/2015</span></td></tr>
						<tr><td colspan="7" style="border-bottom: 0">Website: www.aimica.com.vn</td></tr>
						<tr><th colspan="7" style="text-align: center; border-bottom: 0""><h2 style="margin: 0">PHIẾU TRẢ MÁY BẢO HÀNH</h2></th></tr>
						<tr><td colspan="7" style="border-bottom: 0">Mã Khách hàng: <?php echo $prints[0]->shop_code;?></td></tr>
						<tr><td colspan="7" style="border-bottom: 0">Khách hàng: <?php echo $prints[0]->shop_name;?></td></tr>
						<tr><td colspan="7" style="border-bottom: 0">Địa chỉ: <?php echo $prints[0]->shop_address;?></td></tr>
						<tr><td colspan="7">Điện thoại: <?php echo $prints[0]->shop_phone;?></td></tr>
						<tr style="background: #dddddd" class="warranty_paper_list_item">
							<th>STT</th>
							<th>Model</th>
							<th>Imel</th>
							<th>Phụ kiện</th>
							<th>Tình trạng bảo hành</th>
							<th>Code lỗi</th>
							<th>Ghi chú</th>
						</tr>
						<?php foreach($prints as $k=>$print){?>
							<tr class="warranty_paper_list_item">
								<td>
									<?php echo $k+1;?>
									<a href="javascript:void(0)" onclick="removePrint(<?php echo $print->id;?>)" title="Delete" class="btn btn-mini btn-danger hasTooltip pull-right remove_print" style="font-size: 11px; padding: 0 3px; margin-left: 3px">Del</a>
									<a href="<?php echo JRoute::_('index.php?option=com_warranty&task=warranty.edit&id='.(int) $print->id); ?>" target="_blank" title="Edit" class="btn btn-mini btn-primary hasTooltip pull-right edit_print" style="font-size: 11px; padding: 0 3px">Edit</a>
								</td>
								<td><?php echo $print->model;?></td>
								<td><?php echo $print->imei;?></td>
								<td><?php echo $print->accessories;?></td>
								<td><?php echo $print->warranty;?></td>
								<td><?php echo $print->error_codes;?></td>
								<td><?php echo $print->note;?></td>
							</tr>
						<?php }?>
						<?php /*if(count($prints)<3) for($i=0; $i<3-count($prints); $i++){?>
							<tr class="warranty_paper_list_item">
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
						<?php }*/?>
					<tr><td colspan="7">Sau 1 ngày kể từ khi nhận hàng, cửa hàng không có phản hồi thì xem như đã nhận đủ hàng như đã nêu trên.</td></tr>
					<tr>
						<td colspan="7">
							<div style="width: 30%; float: left; text-align: center">
								<p style="margin-bottom: 0">&nbsp;</p>
								<p style="font-weight: bold">Khách Hàng</p>
							</div>
							<div style="width: 50%; float: right; text-align: center">
								<p style="margin-bottom: 0"><span id="warranty_paper_print_place_text">HCM</span>, ngày <span id="warranty_paper_print_day_text"><?php echo date('d');?></span>  tháng <span id="warranty_paper_print_month_text"><?php echo date('m');?></span>  năm <span id="warranty_paper_print_year_text"><?php echo date('Y');?></span></p>
								<p style="font-weight: bold">Trung tâm Bảo hành Aimica Việt Nam</p>
								<p>&nbsp;</p>
								<p id="warranty_paper_print_name_text">Nguyễn Thị Ngọc Duyên</p>
							</div>
						</td>
					</tr>
					</tbody>
				</table>
				<style type="text/css">
					#warranty_paper_table{
						border: 1px solid #ddd;
						margin-bottom: 20px;
						font-size: 12px;
					}
					#warranty_paper_table.table th, #warranty_paper_table.table td{
						border-top: 0;
						border-bottom: 1px solid #ddd;
						padding: 1px 5px;
					}
					tr.warranty_paper_list_item td, tr.warranty_paper_list_item th{border-left: 1px solid #cccccc}
				</style>
			</div>
			<div class="clearfix">
				<div>
					<label for="warranty_paper_print_place" style="display: inline-block; margin-right: 10px">Địa điểm:
						<input type="text" style="margin-bottom: 0; width: 100px" id="warranty_paper_print_place" value="HCM">
					</label>
					<label for="warranty_paper_print_day" style="display: inline-block; margin-right: 10px">Ngày:
						<input type="text" style="margin-bottom: 0; width: 20px" id="warranty_paper_print_day" value="<?php echo date('d');?>">
					</label>
					<label for="warranty_paper_print_month" style="display: inline-block; margin-right: 10px">Tháng:
						<input type="text" style="margin-bottom: 0; width: 20px" id="warranty_paper_print_month" value="<?php echo date('m');?>">
					</label>
					<label for="warranty_paper_print_year" style="display: inline-block; margin-right: 10px">Năm:
						<input type="text" style="margin-bottom: 0; width: 40px" id="warranty_paper_print_year" value="<?php echo date('Y');?>">
					</label>
					<label for="warranty_paper_print_name" style="display: inline-block; margin-right: 10px">Đại diện:
						<input type="text" style="margin-bottom: 0; width: 200px" id="warranty_paper_print_name" value="Nguyễn Thị Ngọc Duyên">
					</label>
				</div>
				<div>
					<label for="warranty_paper_print_margin" style="display: inline-block; margin-right: 10px">Khoảng cách giữa 2 phiếu:
						<input type="text" style="margin-bottom: 0; width: 30px" id="warranty_paper_print_margin" value="20"> px
					</label>
					<a href="javascript:void(0)" id="warranty_print" class="btn btn-success"><i class="icon-print"></i> In</a>
				</div>
			</div>
			<script type="text/javascript">
				jQuery(function($){
					$('#warranty_paper_print_place, #warranty_paper_print_day, #warranty_paper_print_month, #warranty_paper_print_year, #warranty_paper_print_name').change(function(){
						var id = $(this).attr('id');
						$('#'+id+'_text').text($(this).val());
					});

					$('#warranty_print').click(function(){
						var printMargin = parseInt($('#warranty_paper_print_margin').val());
						if(!printMargin) printMargin = 20;
						var divContents = $("#warranty_paper").clone();
						$.each(divContents.find('.remove_print, .edit_print'), function(i, item){
							$(item).remove();
						});
						var printWindow = window.open('', '', 'height=600,width=800');
						divContents.children('.table').css('margin-bottom', printMargin+'px');
						var html = divContents.html();
						divContents.children('.table').css('margin-bottom', 0);
						var html2 = divContents.html();
						printWindow.document.write('<html><head><title>Phiếu bảo hành</title><link rel="stylesheet" href="<?php echo JUri::root()?>administrator/templates/isis/css/template.css" type="text/css">');
						printWindow.document.write('</head><body >');
						printWindow.document.write(html);
						printWindow.document.write(html2);
						printWindow.document.write('</body></html>');
						printWindow.document.close();
						printWindow.print();
					})
				});
			</script>
		</div>
		<hr>
		<?php }?>

		<div class="btn-toolbar clearfix">
			<?php if($this->pagination->get('total')){?>
				<a href="index.php?option=com_warranty&task=warranties.exportFilter" class="btn btn-primary pull-right" style="margin-left: 5px"><i class="icon-download"></i> Export By Filter - <?php echo $this->pagination->get('total');?> Record(s)</a>
			<?php }?>
			<?php if($this->total){?>
				<a href="index.php?option=com_warranty&task=warranties.exportAll" class="btn btn-primary pull-right"><i class="icon-download"></i> Export All - <?php echo $this->total;?> Record(s)</a>
			<?php }?>
		</div>
        <div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left">
				<label for="filter_search" class="element-invisible"><?php echo JText::_('JSEARCH_FILTER');?></label>
				<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('JSEARCH_FILTER'); ?>" />
			</div>
			<div class="btn-group pull-left">
				<button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				<button class="btn hasTooltip" id="clear-search-button" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></button>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC');?></label>
				<select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JFIELD_ORDERING_DESC');?></option>
					<option value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING');?></option>
					<option value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING');?></option>
				</select>
			</div>
			<div class="btn-group pull-right">
				<label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY');?></label>
				<select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JGLOBAL_SORT_BY');?></option>
					<?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder);?>
				</select>
			</div>
		</div>
		<div class="btn-toolbar clearfix">
			<div class="filter-search btn-group pull-left">
				<?php
				$format = '%Y-%m-%d';
				$attributes = array();
				$attributes['class'] = 'input-medium';

				$attributes['placeholder'] = 'Received from';
				echo JHtml::_('calendar', $this->state->get('filter.received_from_date'), 'filter_received_from_date', 'filter_received_from_date', $format, $attributes);

				$attributes['placeholder'] = 'Received to';
				echo JHtml::_('calendar', $this->state->get('filter.received_to_date'), 'filter_received_to_date', 'filter_received_to_date', $format, $attributes);
				?>
			</div>
			<div class="btn-group pull-left">
				<button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				<button onclick="javascript: this.form.received_from_date.value='';this.form.received_to_date.value=''; this.form.submit();" class="btn hasTooltip" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></button>
			</div>
		</div>
		<div class="btn-toolbar clearfix">
			<div class="filter-search btn-group pull-left">
				<?php
				$attributes['placeholder'] = 'Delivery from';
				echo JHtml::_('calendar', $this->state->get('filter.delivery_from_date'), 'filter_delivery_from_date', 'filter_delivery_from_date', $format, $attributes);

				$attributes['placeholder'] = 'Delivery to';
				echo JHtml::_('calendar', $this->state->get('filter.delivery_to_date'), 'filter_delivery_to_date', 'filter_delivery_to_date', $format, $attributes);
				?>
			</div>
			<div class="btn-group pull-left">
				<button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				<button onclick="javascript: this.form.filter_delivery_from_date.value='';this.form.delivery_to_date.value=''; this.form.submit();" class="btn hasTooltip" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></button>
			</div>
		</div>
		<div class="clearfix"> </div>
		<table class="table table-striped" id="productList">
			<thead>
				<tr>
					<th width="1%" class="hidden-phone">
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
					</th>
					<th>
						<?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.status', $listDirn, $listOrder); ?>
					</th>
                    <th>
                        <?php echo JHtml::_('grid.sort', 'COM_WARRANTY_RECEIVED', 'a.received', $listDirn, $listOrder); ?>
                    </th>
                    <th>
                        <?php echo JHtml::_('grid.sort', 'COM_WARRANTY_TITLE_SHOP', 's.name', $listDirn, $listOrder); ?>
                    </th>
					<th>
						<?php echo JHtml::_('grid.sort', 'COM_WARRANTY_IMEI', 'a.imei', $listDirn, $listOrder); ?>
					</th>
					<th>
						<?php echo JHtml::_('grid.sort', 'COM_WARRANTY_ACCESSORIES', 'a.accessories', $listDirn, $listOrder); ?>
					</th>
					<th>
						<?php echo JHtml::_('grid.sort', 'COM_WARRANTY_ERRORS', 'a.errors', $listDirn, $listOrder); ?>
					</th>
					<th>
						<?php echo JHtml::_('grid.sort', 'Error Codes', 'a.error_codes', $listDirn, $listOrder); ?>
					</th>
					<th>
						<?php echo JHtml::_('grid.sort', 'COM_WARRANTY_DELIVERY', 'a.delivery', $listDirn, $listOrder); ?>
					</th>
                    <th style="width: 1%" class="nowrap hidden-phone">
                        <?php echo JHtml::_('grid.sort', 'COM_WARRANTY_ID', 'a.id', $listDirn, $listOrder); ?>
                    </th>
				</tr>
			</thead>
			<tfoot>
                <tr>
                    <td colspan="20">
                        <?php echo $this->pagination->getListFooter(); ?>
                    </td>
                </tr>
			</tfoot>
			<tbody>
			<?php foreach ($this->items as $i => $item) :
				$ordering   = ($listOrder == 'a.ordering');
                $canCreate	= $user->authorise('core.create',		'com_warranty');
                $canEdit	= $user->authorise('core.edit',			'com_warranty');
                $canCheckin	= $user->authorise('core.manage',		'com_warranty');
                $canChange	= $user->authorise('core.edit.state',	'com_warranty');
				?>
				<tr class="row<?php echo $i % 2; ?>">
					<td class="hidden-phone"><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>
					<td>
						<?php switch($item->status){
							case 0: echo '<span class="label">'.JText::_('COM_WARRANTY_WAIT').'</span>'; break;
							case 1: echo '<span class="label label-success">'.JText::_('COM_WARRANTY_ACCEPT').'</span>'; break;
							case 2: echo '<span class="label label-important">'.JText::_('COM_WARRANTY_DENY').'</span>'; break;
						}?>
					</td>
                    <td>
						<a href="<?php echo JRoute::_('index.php?option=com_warranty&task=warranty.edit&id='.(int) $item->id); ?>"><?php echo $item->received; ?></a>
						<?php if(!$listPrint || ($listPrint && $prints[0]->shop_id == $item->shop_id)){?>
							<button onclick="addPrint(<?php echo $item->id;?>)" class="btn btn-mini btn-success" <?php if($listPrint && in_array($item->id, explode(',', $listPrint))) echo 'disabled';?> type="button">Print</button>
						<?php }?>
					</td>
                    <td>
						<a href="<?php echo JRoute::_('index.php?option=com_warranty&task=shop.edit&id='.(int) $item->shop_id); ?>" target="_blank"><?php echo $item->shop_name; ?></a>
						<br><span class="small"><?php echo JText::_('COM_WARRANTY_CODE').': '.$item->shop_code?></span>
						<br><span class="small"><?php echo JText::_('COM_WARRANTY_PHONE').': '.$item->shop_phone?></span>
					</td>
                    <td>
						<a href="<?php echo JRoute::_('index.php?option=com_warranty&task=product.edit&id='.(int) $item->product_id); ?>" target="_blank"><?php echo $item->imei; ?></a>
						<br><span class="small"><?php echo JText::_('COM_WARRANTY_MODEL').': '.$item->model?></span>
					</td>
                    <td><?php echo $item->accessories; ?></td>
                    <td><?php echo $item->errors; ?></td>
                    <td><?php echo $item->error_codes; ?></td>
                    <td><?php if($item->delivery != '0000-00-00') echo $item->delivery; ?></td>
                    <td><?php echo $item->id; ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<input type="hidden" name="filter_print_list" value="<?php echo $listPrint; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
<style type="text/css">
	#filter_received_to_date, #filter_delivery_to_date{margin-left: 5px;}
</style>

