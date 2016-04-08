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
class WarrantyViewWarranties extends JViewLegacy {

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

        WarrantyHelper::addSubmenu('warranties');

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

        JToolBarHelper::title(JText::_('COM_WARRANTY_TITLE_WARRANTIES'), 'star');

        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/warranty';
        if (file_exists($formPath)) {

            if ($canDo->get('core.create')) {
                JToolBarHelper::addNew('warranty.add', 'JTOOLBAR_NEW');
            }

            if ($canDo->get('core.edit') && isset($this->items[0])) {
                JToolBarHelper::editList('warranty.edit', 'JTOOLBAR_EDIT');
            }
        }

        if ($canDo->get('core.edit.state')) {
            JToolBarHelper::divider();
            JToolBarHelper::custom('warranties.wait', 'archive.png', 'archive.png', 'COM_WARRANTY_WAIT', true);
            JToolBarHelper::custom('warranties.accept', 'publish.png', 'publish_f2.png', 'COM_WARRANTY_ACCEPT', true);
            JToolBarHelper::custom('warranties.deny', 'unpublish.png', 'unpublish_f2.png', 'COM_WARRANTY_DENY', true);
            JToolBarHelper::divider();
            JToolBarHelper::deleteList('', 'warranties.delete', 'JTOOLBAR_DELETE');
        }

        if ($canDo->get('core.admin')) {
            JToolBarHelper::preferences('com_warranty');
        }

        //Set sidebar action - New in 3.0
        JHtmlSidebar::setAction('index.php?option=com_warranty&view=warranties');

        $this->extra_sidebar = '';

        JHtmlSidebar::addFilter(
            JText::sprintf('COM_WARRANTY_FILTER_SELECT_LABEL', JText::_('JSTATUS')),
            'filter_status',
            JHtml::_('select.options', $this->getStatusOptions(), 'value', 'text', $this->state->get('filter.status'), true)
        );

        JHtmlSidebar::addFilter(
            JText::sprintf('COM_WARRANTY_FILTER_SELECT_LABEL', JText::_('COM_WARRANTY_TITLE_SHOP')),
            'filter_shop',
            JHtml::_('select.options', $this->getShopOptions(), 'value', 'text', $this->state->get('filter.shop'), true)
        );

        JHtmlSidebar::addFilter(
            JText::sprintf('COM_WARRANTY_FILTER_SELECT_LABEL', JText::_('Importer')),
            'filter_created_by',
            JHtml::_('select.options', $this->getUserImportOptions(), 'value', 'text', $this->state->get('filter.created_by'), true)
        );
    }

    public static function getStatusOptions(){
        $options = array();
        $options[] = JHtml::_('select.option', 0, 'COM_WARRANTY_WAIT');
        $options[] = JHtml::_('select.option', 1, 'COM_WARRANTY_ACCEPT');
        $options[] = JHtml::_('select.option', 2, 'COM_WARRANTY_DENY');
        return $options;
    }

    public static function getShopOptions(){
        $db = JFactory::getDbo();
        $db->setQuery("SELECT id, CASE code WHEN '' THEN CONCAT(id, ' - ', name, ' - ', phone) ELSE CONCAT(code, ' - ', name, ' - ', phone) END AS name FROM #__warranty_shops");
        $list = $db->loadObjectList();
        $options = array();
        if($list) foreach($list as $item) if($item->name){
            $options[] = JHtml::_('select.option', $item->id, $item->name);
        }
        return $options;
    }

    public function getUserImportOptions(){
        $db = JFactory::getDbo();
        $db->setQuery('SELECT DISTINCT a.created_by AS id, us.name FROM #__warranty_warranties a LEFT JOIN #__users us ON us.id = a.created_by ORDER BY a.created_by ASC');
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
            'a.received' => JText::_('COM_WARRANTY_RECEIVED'),
            's.name' => JText::_('COM_WARRANTY_SHOP'),
            'a.imei' => JText::_('COM_WARRANTY_IMEI'),
            'a.accessories' => JText::_('COM_WARRANTY_ACCESSORIES'),
            'a.errors' => JText::_('COM_WARRANTY_ERRORS'),
            'a.error_codes' => JText::_('COM_WARRANTY_ERROR_CODES'),
            'a.delivery' => JText::_('COM_WARRANTY_DELIVERY'),
            'a.id' => JText::_('JGRID_HEADING_ID'),
		);
	}

    protected function getTotalProducts(){
        $db = JFactory::getDbo();
        $db->setQuery('SELECT COUNT(id) FROM #__warranty_warranties');
        return $db->loadResult();
    }
}
