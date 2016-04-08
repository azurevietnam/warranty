<?php

/**
 * @version     1.0.0
 * @package     com_warranty
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Chau Dang Khoa <boyupdatetofuture@gmail.com> - 
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Warranty.
 */
class WarrantyViewProducts extends JViewLegacy {

    protected $items;
    protected $pagination;
    protected $state;

    /**
     * Display the view
     */
    public function display($tpl = null) {
        $this->state = $this->get('State');
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->total = $this->getTotalProducts();

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }

        WarrantyHelper::addSubmenu('products');

        $this->addToolbar();

        $this->sidebar = JHtmlSidebar::render();
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @since	1.6
     */
    protected function addToolbar() {
        require_once JPATH_COMPONENT . '/helpers/warranty.php';

        $state = $this->get('State');
        $canDo = WarrantyHelper::getActions($state->get('filter.category_id'));

        JToolBarHelper::title(JText::_('COM_WARRANTY_TITLE_PRODUCTS'), 'mobile');

        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/product';
        if (file_exists($formPath)) {

            if ($canDo->get('core.create')) {
                JToolBarHelper::addNew('product.add', 'JTOOLBAR_NEW');
            }

            if ($canDo->get('core.edit') && isset($this->items[0])) {
                JToolBarHelper::editList('product.edit', 'JTOOLBAR_EDIT');
            }
        }

        if ($canDo->get('core.edit.state')) {
            JToolBarHelper::divider();
            JToolBarHelper::custom('products.publish', 'publish.png', 'publish_f2.png', 'Active', true);
            JToolBarHelper::custom('products.unpublish', 'unpublish.png', 'unpublish_f2.png', 'Deactive', true);
            JToolBarHelper::divider();
            JToolBarHelper::deleteList('', 'products.delete', 'JTOOLBAR_DELETE');
        }

        if ($canDo->get('core.admin')) {
            JToolBarHelper::preferences('com_warranty');
        }

        //Set sidebar action - New in 3.0
        JHtmlSidebar::setAction('index.php?option=com_warranty&view=products');

        $this->extra_sidebar = '';

        JHtmlSidebar::addFilter(
            JText::sprintf('COM_WARRANTY_FILTER_SELECT_LABEL', JText::_('Manufacturer')),
            'filter_manufacturer',
            JHtml::_('select.options', $this->getManufacturerOptions(), 'value', 'text', $this->state->get('filter.manufacturer'), true)
        );

        JHtmlSidebar::addFilter(
            JText::sprintf('COM_WARRANTY_FILTER_SELECT_LABEL', JText::_('JSTATUS')),
            'filter_status',
            JHtml::_('select.options', $this->getStatusOptions(), 'value', 'text', $this->state->get('filter.status'), true)
        );

        JHtmlSidebar::addFilter(
            JText::sprintf('COM_WARRANTY_FILTER_SELECT_LABEL', JText::_('COM_WARRANTY_SHOP_NAME')),
            'filter_shop_name',
            JHtml::_('select.options', $this->getShopNameOptions(), 'value', 'text', $this->state->get('filter.shop_name'), true)
        );

        JHtmlSidebar::addFilter(
            JText::sprintf('COM_WARRANTY_FILTER_SELECT_LABEL', JText::_('COM_WARRANTY_SHOP_REGION')),
            'filter_shop_region',
            JHtml::_('select.options', $this->getShopRegionOptions(), 'value', 'text', $this->state->get('filter.shop_region'), true)
        );

        JHtmlSidebar::addFilter(
            JText::sprintf('COM_WARRANTY_FILTER_SELECT_LABEL', JText::_('COM_WARRANTY_MODEL')),
            'filter_model',
            JHtml::_('select.options', $this->getModelOptions(), 'value', 'text', $this->state->get('filter.model'), true)
        );

        JHtmlSidebar::addFilter(
            JText::sprintf('COM_WARRANTY_FILTER_SELECT_LABEL', JText::_('COM_WARRANTY_COLOR')),
            'filter_color',
            JHtml::_('select.options', $this->getColorOptions(), 'value', 'text', $this->state->get('filter.color'), true)
        );

        JHtmlSidebar::addFilter(
            JText::sprintf('COM_WARRANTY_FILTER_SELECT_LABEL', JText::_('COM_WARRANTY_SELL_IN')),
            'filter_sell_in',
            JHtml::_('select.options', $this->getSellInOptions(), 'value', 'text', $this->state->get('filter.sell_in'), true)
        );

        JHtmlSidebar::addFilter(
            JText::sprintf('COM_WARRANTY_FILTER_SELECT_LABEL', JText::_('COM_WARRANTY_TITLE_ERROR')),
            'filter_error',
            JHtml::_('select.options', $this->getErrorOptions(), 'value', 'text', $this->state->get('filter.error'), true)
        );

        JHtmlSidebar::addFilter(
            JText::sprintf('COM_WARRANTY_FILTER_SELECT_LABEL', JText::_('Importer')),
            'filter_created_by',
            JHtml::_('select.options', $this->getUserImportOptions(), 'value', 'text', $this->state->get('filter.created_by'), true)
        );
    }

    public static function getManufacturerOptions(){
        $db = JFactory::getDbo();
        $db->setQuery('SELECT DISTINCT manufacturer AS name FROM #__warranty_products');
        $list = $db->loadObjectList();
        $options = array();
        if($list) foreach($list as $item) if($item->name){
            $options[] = JHtml::_('select.option', $item->name, $item->name);
        }
        return $options;
    }

    public static function getStatusOptions(){
        $options = array();
        $options[] = JHtml::_('select.option', 1, 'Active');
        $options[] = JHtml::_('select.option', 0, 'Inactive');
        return $options;
    }

    public function getShopNameOptions(){
        $db = JFactory::getDbo();
        $db->setQuery('SELECT DISTINCT shop_name AS name FROM #__warranty_products');
        $list = $db->loadObjectList();
        $options = array();
        if($list) foreach($list as $item) if($item->name){
            $options[] = JHtml::_('select.option', $item->name, $item->name);
        }
        return $options;
    }

    public function getShopRegionOptions(){
        $db = JFactory::getDbo();
        $db->setQuery('SELECT DISTINCT shop_region AS name FROM #__warranty_products');
        $list = $db->loadObjectList();
        $options = array();
        if($list) foreach($list as $item) if($item->name){
            $options[] = JHtml::_('select.option', $item->name, $item->name);
        }
        return $options;
    }

    public function getModelOptions(){
        $db = JFactory::getDbo();
        $db->setQuery('SELECT DISTINCT model AS name FROM #__warranty_products');
        $list = $db->loadObjectList();
        $options = array();
        if($list) foreach($list as $item) if($item->name){
            $options[] = JHtml::_('select.option', $item->name, $item->name);
        }
        return $options;
    }

    public function getColorOptions(){
        $db = JFactory::getDbo();
        $db->setQuery('SELECT DISTINCT color AS name FROM #__warranty_products');
        $list = $db->loadObjectList();
        $options = array();
        if($list) foreach($list as $item) if($item->name){
            $options[] = JHtml::_('select.option', $item->name, $item->name);
        }
        return $options;
    }

    public function getSellInOptions(){
        $db = JFactory::getDbo();
        $db->setQuery('SELECT DISTINCT sell_in AS name FROM #__warranty_products');
        $list = $db->loadObjectList();
        $options = array();
        if($list) foreach($list as $item) if($item->name){
            $options[] = JHtml::_('select.option', $item->name, $item->name);
        }
        return $options;
    }

    public function getErrorOptions(){
        $db = JFactory::getDbo();
        $db->setQuery('SELECT DISTINCT id, code, desc_vi FROM #__warranty_errors');
        $list = $db->loadObjectList();
        $options = array();
        if($list) foreach($list as $item){
            $options[] = JHtml::_('select.option', $item->id, $item->code.' - '.$item->desc_vi);
        }
        return $options;
    }

    public function getUserImportOptions(){
        $db = JFactory::getDbo();
        $db->setQuery('SELECT DISTINCT a.created_by AS id, us.name FROM #__warranty_products a LEFT JOIN #__users us ON us.id = a.created_by ORDER BY a.created_by ASC');
        $list = $db->loadObjectList();
        $options = array();
        if($list) foreach($list as $item){
            if(!$item->id){
                $options[] = JHtml::_('select.option', $item->id, 'Unknown');
            }else{
                $options[] = JHtml::_('select.option', $item->id, $item->name);
            }
        }
        return $options;
    }

	protected function getSortFields()
	{
		return array(
            'a.status' => JText::_('JSTATUS'),
            'a.shop_name' => JText::_('COM_WARRANTY_SHOP_NAME'),
            'a.shop_region' => JText::_('COM_WARRANTY_SHOP_REGION'),
            'a.imei' => JText::_('COM_WARRANTY_IMEI'),
		    'a.model' => JText::_('COM_WARRANTY_MODEL'),
		    'a.color' => JText::_('COM_WARRANTY_COLOR'),
		    'a.sell_in' => JText::_('COM_WARRANTY_SELL_IN'),
		    'a.customer_name' => JText::_('COM_WARRANTY_CUSTOMER_NAME'),
		    'error_code' => JText::_('COM_WARRANTY_TITLE_ERROR'),
		    'a.id' => JText::_('JGRID_HEADING_ID'),
		);
	}

    protected function getTotalProducts(){
        $db = JFactory::getDbo();
        $db->setQuery('SELECT COUNT(id) FROM #__warranty_products');
        return $db->loadResult();
    }
}
