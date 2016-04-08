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
class WarrantyViewRequests extends JViewLegacy {

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

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }

        WarrantyHelper::addSubmenu('requests');

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

        JToolBarHelper::title(JText::_('COM_WARRANTY_TITLE_REQUESTS'));

        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/request';
        /*if (file_exists($formPath)) {

            if ($canDo->get('core.create')) {
                JToolBarHelper::addNew('request.add', 'JTOOLBAR_NEW');
            }

            if ($canDo->get('core.edit') && isset($this->items[0])) {
                JToolBarHelper::editList('request.edit', 'JTOOLBAR_EDIT');
            }
        }*/

        if ($canDo->get('core.edit.state')) {
            JToolBarHelper::divider();
            JToolBarHelper::custom('requests.publish', 'publish.png', 'publish_f2.png', 'Processed', true);
            JToolBarHelper::divider();
            JToolBarHelper::deleteList('', 'requests.delete', 'JTOOLBAR_DELETE');
        }

        if ($canDo->get('core.admin')) {
            JToolBarHelper::preferences('com_warranty');
        }

        //Set sidebar action - New in 3.0
        JHtmlSidebar::setAction('index.php?option=com_warranty&view=requests');

        $this->extra_sidebar = '';

        JHtmlSidebar::addFilter(
            JText::sprintf('COM_WARRANTY_FILTER_SELECT_LABEL', JText::_('JSTATUS')),
            'filter_status',
            JHtml::_('select.options', $this->getStatusOptions(), 'value', 'text', $this->state->get('filter.status'), true)
        );
    }

    public static function getStatusOptions(){
        $options = array();
        $options[] = JHtml::_('select.option', 0, 'Pending');
        $options[] = JHtml::_('select.option', 1, 'Processed');
        return $options;
    }

	protected function getSortFields()
	{
		return array(
            'a.status' => JText::_('JSTATUS'),
            'us.name' => JText::_('COM_WARRANTY_CUSTOMER_NAME'),
            'a.imei' => JText::_('COM_WARRANTY_PRODUCT'),
            'a.created' => JText::_('COM_WARRANTY_CREATED'),
            'a.id' => JText::_('JGRID_HEADING_ID'),
		);
	}
}
