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

    function importFile(){
        if(jQuery('#import_file').val()){
            Joomla.submitbutton('products.import');
        }else{
            alert('Please select a excel file (*.xls) to import!');
        }
    }

    function checkFile(){
        if(jQuery('#import_file').val()){
            Joomla.submitbutton('products.checkActive');
        }else{
            alert('Please select a excel file (*.xls) to import!');
        }
    }
</script>

<?php
//Joomla Component Creator code to allow adding non select list filters
if (!empty($this->extra_sidebar)) {
    $this->sidebar .= $this->extra_sidebar;
}
?>

<form action="<?php echo JRoute::_('index.php?option=com_warranty&view=products'); ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<?php if(!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
        <?php if(JFactory::getApplication()->input->get('task') == 'ajaxImport'){?>
            <h3>Importing! please wait...</h3>

            <div id="importStatus">
                <h3>Processed: <span id="importProcessed">0</span> / <span><?php echo JFactory::getApplication()->input->get('record')?></span></h3>
                <h3>Inserted: <span id="importInserted" style="color: green">0</span> Record(s)</h3>
                <h3>Updated: <span id="importUpdated" style="color: blue">0</span> Record(s)</h3>
                <h3>Cancelled: <span id="importCancelled" style="color: red">0</span> Record(s)</h3>
                <div style="overflow:hidden;">
                    <h1 id="importReturn" class="pull-right" style="display: none;"><a href="<?php echo JRoute::_('index.php?option=com_warranty&view=products', false);?>">Return</a></h1>
                </div>
            </div>

            <script type="text/javascript">
                function importData(offset){
                    jQuery.ajax({
                        url: 'index.php?option=com_warranty&task=products.ajaxImport&type=<?php echo JFactory::getApplication()->input->get('type', 'xls')?>',
                        data: {offset: offset},
                        type: 'post',
                        dataType: 'json',
                        async: false,
                        success: function(data){
                            if(data.rs){
                                jQuery('#importProcessed').text(parseInt(data.offset) - 1);
                                jQuery('#importInserted').text(parseInt(jQuery('#importInserted').text()) + data.inserted);
                                jQuery('#importUpdated').text(parseInt(jQuery('#importUpdated').text()) + data.updated);
                                jQuery('#importCancelled').text(parseInt(jQuery('#importCancelled').text()) + data.cancelled);

                                if(data.complete){
                                    alert('Completed!');
                                    jQuery('#importReturn').show();
                                }else{
                                    setTimeout(function(){
                                        importData(data.offset);
                                    }, 100);
                                }
                            }else{
                                alert(data.msg);
                                jQuery('#importReturn').show();
                            }
                        }
                    });
                }

                jQuery(document).ready(function(){
                    setTimeout(function(){
                        importData(1);
                    }, 100);
                });
            </script>
        <?php }else if(JFactory::getApplication()->input->get('task') == 'checkActive'){?>
            <h3>Checking! please wait...</h3>

            <div id="importStatus">
                <h3>Processed: <span id="checkProcessed">0</span> / <span><?php echo JFactory::getApplication()->input->get('record')?></span></h3>
                <h3>Active: <span id="checkActive" style="color: green">0</span> Record(s)</h3>
                <h3>Inactive: <span id="checkInactive" style="color: blue">0</span> Record(s)</h3>
                <h3>Cancelled: <span id="checkCancelled" style="color: red">0</span> Record(s)</h3>
                <div style="overflow:hidden;">
                    <h1 id="getResult" class="pull-left" style="display: none;"><a href="<?php echo JRoute::_('index.php?option=com_warranty&task=products.getCheckResult', false);?>">Get Result</a></h1>
                    <h1 id="checkReturn" class="pull-right" style="display: none;"><a href="<?php echo JRoute::_('index.php?option=com_warranty&view=products', false);?>">Return</a></h1>
                </div>
            </div>

            <script type="text/javascript">
                function checkData(offset){
                    jQuery.ajax({
                        url: 'index.php?option=com_warranty&task=products.ajaxCheck&type=<?php echo JFactory::getApplication()->input->get('type', 'xls')?>',
                        data: {offset: offset},
                        type: 'post',
                        dataType: 'json',
                        async: false,
                        success: function(data){
                            if(data.rs){
                                jQuery('#checkProcessed').text(parseInt(data.offset) - 1);
                                jQuery('#checkActive').text(parseInt(jQuery('#checkActive').text()) + data.active);
                                jQuery('#checkInactive').text(parseInt(jQuery('#checkInactive').text()) + data.inactive);
                                jQuery('#checkCancelled').text(parseInt(jQuery('#checkCancelled').text()) + data.cancelled);

                                if(data.complete){
                                    alert('Completed!');
                                    jQuery('#checkReturn, #getResult').show();
                                }else{
                                    setTimeout(function(){
                                        checkData(data.offset);
                                    }, 100);
                                }
                            }else{
                                alert(data.msg);
                                jQuery('#checkReturn').show();
                            }
                        }
                    });
                }

                jQuery(document).ready(function(){
                    setTimeout(function(){
                        checkData(1);
                    }, 100);
                });
            </script>
        <?php } else{ ?>
        <div class="btn-toolbar clearfix">
            <input type="file" name="import_file" id="import_file"/>
            <button type="button" onclick="importFile()" class="btn btn-success"><i class="icon-upload"></i> Import</button>
            <button type="button" onclick="checkFile()" class="btn"><i class="icon-publish"></i> Check Active</button>
            <?php if($this->pagination->get('total')){?>
                <a href="index.php?option=com_warranty&task=products.exportFilter" class="btn btn-primary pull-right"><i class="icon-download"></i> Export By Filter - <?php echo $this->pagination->get('total');?> Record(s)</a>
            <?php }?>
            <?php if($this->total){?>
                <a href="index.php?option=com_warranty&task=products.exportAll" class="btn btn-primary pull-right"><i class="icon-download"></i> Export All - <?php echo $this->total;?> Record(s)</a>
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

                $attributes['placeholder'] = 'Active from';
                echo JHtml::_('calendar', $this->state->get('filter.from_date'), 'filter_from_date', 'filter_from_date', $format, $attributes);

                $attributes['placeholder'] = 'Active to';
                echo JHtml::_('calendar', $this->state->get('filter.to_date'), 'filter_to_date', 'filter_to_date', $format, $attributes);
                ?>
            </div>
            <div class="btn-group pull-left">
                <button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
                <button onclick="javascript: this.form.filter_from_date.value='';this.form.filter_to_date.value=''; this.form.submit();" class="btn hasTooltip" id="clear-date-button" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></button>
            </div>
        </div>

        <div class="btn-toolbar clearfix">
            <div class="filter-search btn-group pull-left">
                <?php
                $attributes['placeholder'] = 'Import from';
                echo JHtml::_('calendar', $this->state->get('filter.import_from_date'), 'filter_import_from_date', 'filter_import_from_date', $format, $attributes);

                $attributes['placeholder'] = 'Import to';
                echo JHtml::_('calendar', $this->state->get('filter.import_to_date'), 'filter_import_to_date', 'filter_import_to_date', $format, $attributes);
                ?>
            </div>
            <div class="btn-group pull-left">
                <button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
                <button onclick="javascript: this.form.filter_import_from_date.value='';this.form.filter_import_to_date.value=''; this.form.submit();" class="btn hasTooltip" id="clear-date-button" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></button>
            </div>
        </div>
		<div class="clearfix"> </div>
		<table class="table table-striped" id="productList">
			<thead>
				<tr>
					<th width="1%" class="hidden-phone">
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
					</th>
                    <th width="1%" class="nowrap center"><?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.status', $listDirn, $listOrder); ?></th>
                    <th width="15%">
                        <?php echo JHtml::_('grid.sort', 'COM_WARRANTY_IMEI', 'a.imei', $listDirn, $listOrder); ?>
                    </th>
                    <th>
                        <?php echo JHtml::_('grid.sort', 'COM_WARRANTY_SHOP_NAME', 'a.shop_name', $listDirn, $listOrder); ?>
                    </th>
                    <th>
                        <?php echo JHtml::_('grid.sort', 'COM_WARRANTY_COLOR', 'a.color', $listDirn, $listOrder); ?>
                    </th>
                    <th>
                        <?php echo JText::_('COM_WARRANTY_IMAGE'); ?>
                    </th>
                    <th>
                        <?php echo JHtml::_('grid.sort', 'COM_WARRANTY_SELL_IN', 'a.sell_in', $listDirn, $listOrder); ?>
                    </th>
                    <th>
                        <?php echo JHtml::_('grid.sort', 'COM_WARRANTY_CUSTOMER_NAME', 'a.customer_name', $listDirn, $listOrder); ?>
                    </th>
                    <th>
                        <?php echo JHtml::_('grid.sort', 'COM_WARRANTY_TITLE_ERROR', 'error_code', $listDirn, $listOrder); ?>
                    </th>
                    <!--<th style="width: 1%" class="nowrap hidden-phone">
                        <?php /*echo JHtml::_('grid.sort', 'COM_WARRANTY_ID', 'a.id', $listDirn, $listOrder); */?>
                    </th>-->
				</tr>
			</thead>
			<tfoot>
                <tr>
                    <td colspan="10">
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
                    <td class="center"><?php echo JHtml::_('jgrid.published', $item->status, $i, 'products.', $canChange, 'cb'); ?></td>
                    <td>
                        <a href="<?php echo JRoute::_('index.php?option=com_warranty&task=product.edit&id='.(int) $item->id); ?>"><?php echo $item->imei; ?></a>
                        <br/><small><?php echo $item->active; ?></small>
                        <br/><small><?php echo JText::_('COM_WARRANTY_MODEL').': '.$item->model; ?></small>
                    </td>
                    <td>
                        <?php echo $item->shop_name; ?>
                        <?php if($item->shop_address){?>
                            <br/><small><?php echo 'Address: '.$item->shop_address;?></small>
                        <?php }?>
                        <?php if($item->shop_region){?>
                            <br/><small><?php echo JText::_('COM_WARRANTY_SHOP_REGION').': '.$item->shop_region;?></small>
                        <?php }?>
                    </td>
                    <td><?php echo $item->color; ?></td>
                    <td>
                        <?php if($item->image){?>
                            <a class="modal" href="<?php echo JUri::root().$item->image;?>"><img src="<?php echo JUri::root().$item->image;?>" alt="" style="height: 50px"/></a>
                        <?php }elseif($item->model && $item->color && is_file(JPATH_SITE.'/images/mau-sac/'.str_replace(' ', '-', strtolower($item->model.'-'.$item->color).'.png'))){
                            $item->image = 'images/mau-sac/'.str_replace(' ', '-', strtolower($item->model.'-'.$item->color).'.png');
                            ?>
                            <a class="modal" href="<?php echo JUri::root().$item->image;?>"><img src="<?php echo JUri::root().$item->image;?>" alt="" style="height: 50px"/></a>
                        <?php }?>
                    </td>
                    <td><?php echo $item->sell_in; ?></td>
                    <td>
                        <?php echo $item->customer_name; ?>
                        <?php if($item->customer_address){?>
                            <br/><small><?php echo 'Address: '.$item->customer_address;?></small>
                        <?php }?>
                        <?php if($item->customer_phone){?>
                            <br/><small><?php echo 'Phone: '.$item->customer_phone;?></small>
                        <?php }?>
                    </td>
                    <td><?php echo $item->error_code; ?></td>
                    <!--<td><?php /*echo $item->id; */?></td>-->
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
        <?php }?>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
<style type="text/css">
    #filter_to_date, #filter_import_to_date{margin-left: 5px;}
</style>

