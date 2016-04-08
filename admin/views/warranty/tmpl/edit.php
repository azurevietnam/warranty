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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_warranty/assets/css/warranty.css');
?>
<script type="text/javascript">
   Joomla.submitbutton = function(task)
    {
        if (task == 'warranty.cancel') {
            Joomla.submitform(task, document.getElementById('warranty-form'));
        }
        else {
            
            if (task != 'warranty.cancel' && document.formvalidator.isValid(document.id('warranty-form'))) {
                
                Joomla.submitform(task, document.getElementById('warranty-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_warranty&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="warranty-form" class="form-validate">

    <div class="form-horizontal">
        <div class="row-fluid">
            <div class="span6">
                <legend>Warranty Information</legend>
                <?php foreach ($this->form->getFieldset('basic') as $field) :?>
                    <div class="control-group">
                        <div class="control-label"><?php echo $field->label; ?></div>
                        <div class="controls"><?php echo $field->input; ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="span6">
                <legend>Published Information</legend>
                <?php foreach ($this->form->getFieldset('basic1') as $field) :?>
                    <div class="control-group">
                        <div class="control-label"><?php echo $field->label; ?></div>
                        <div class="controls"><?php echo $field->input; ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php echo $this->form->getInput('id');?>
        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>

    </div>
</form>