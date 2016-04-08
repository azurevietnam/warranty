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
?>

<form action="<?php echo JRoute::_('index.php?option=com_warranty&view=helps'); ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<?php if(!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
		<h1>Hướng dẫn sử dụng</h1>

		<ul class="nav nav-tabs" id="guideTab">
			<li><a href="#pbh" data-toggle="tab"><i class="icon-print"></i> In phiếu bảo hành</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane" id="pbh">
				<p class="alert alert-info"><span class='badge badge-info'>1</span> Thêm khách hàng/cửa hàng</p>
				<p><strong>Chức năng này giúp ta có thể quản lý danh sách khách hàng hoặc cửa hàng muốn bảo hành, tránh việc nhập liệu bị trùng lặp.</strong></p>
				<p>Link: <a href="<?php echo JRoute::_('index.php?option=com_warranty&view=shops')?>" target="_blank"><i class="icon-link"></i> Customers</a></p>

				<p class="alert alert-info"><span class='badge badge-info'>2</span> Thêm dữ liệu cho máy cần bảo hành</p>
				<p><strong>Ta phải thêm từng máy trước khi in vì mỗi máy có ngày nhận và ngày giao khác nhau, nên không thể thêm 1 lần nhiều máy được.</strong></p>
				<p>Link: <a href="<?php echo JRoute::_('index.php?option=com_warranty&view=warranties')?>" target="_blank"><i class="icon-link"></i> Warranties</a></p>
				<p>Chú ý: Khi chọn khách hàng: nếu là cửa hàng ta chú ý mã cửa hàng, nếu là khách hàng thông thường ta chú ý ID của khách hàng.</p>

				<p class="alert alert-info"><span class='badge badge-info'>3</span> Chọn máy cần in</p>
				<p><strong>Trong danh sách máy đã thêm <a href="<?php echo JRoute::_('index.php?option=com_warranty&view=warranties')?>" target="_blank"><i class="icon-link"></i> Warranties</a> ta chọn máy sẽ in</strong></p>
				<p>Nhấn nút <a href="javascript:void(0)" class="btn btn-mini btn-success">Print</a> trên máy muốn in, máy đó sẽ được thêm vào danh sách sẽ in và đây là kết quả:</p>
				<p><a class="modal thumbnail" href="<?php echo JURI::root();?>components/com_warranty/assets/images/presult.jpg"><img style="" src="<?php echo JURI::root();?>components/com_warranty/assets/images/presult.jpg"></a></p>
				<p>Nhấn nút <a href="javascript:void(0)" class="btn btn-mini btn-primary">Edit</a> để chỉnh sửa thông tin máy cần bảo hành.</p>
				<p>Nhấn nút <a href="javascript:void(0)" class="btn btn-mini btn-danger">Del</a> để xóa máy ra khỏi danh sách cần in.</p>
				<p>Sau khi chỉnh sửa các thông số, ta nhấn nút <a href="javascript:void(0)" class="btn btn-success"><i class="icon-print"></i> In</a> để in phiếu.</p>
			</div>
		</div>
		<?php echo JHtml::_('form.token'); ?>

		<script type="text/javascript">
			jQuery(function($){
				$('#guideTab a:first').tab('show');
			})
		</script>
	</div>
</form>

