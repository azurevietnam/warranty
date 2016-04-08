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
class WarrantyViewErrors extends JViewLegacy {

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

        WarrantyHelper::addSubmenu('errors');

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

        JToolBarHelper::title(JText::_('COM_WARRANTY_TITLE_ERRORS'));

        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/error';
        if (file_exists($formPath)) {

            if ($canDo->get('core.create')) {
                JToolBarHelper::addNew('error.add', 'JTOOLBAR_NEW');
            }

            if ($canDo->get('core.edit') && isset($this->items[0])) {
                JToolBarHelper::editList('error.edit', 'JTOOLBAR_EDIT');
            }
        }

        if ($canDo->get('core.edit.state')) {
            JToolBarHelper::deleteList('', 'errors.delete', 'JTOOLBAR_DELETE');
        }

        if ($canDo->get('core.admin')) {
            JToolBarHelper::preferences('com_warranty');
        }

        //Set sidebar action - New in 3.0
        JHtmlSidebar::setAction('index.php?option=com_warranty&view=errors');

        $this->extra_sidebar = '';
    }

	protected function getSortFields()
	{
		return array(
            'a.code' => JText::_('COM_WARRANTY_CODE'),
            'a.desc_tw' => JText::_('COM_WARRANTY_DESCRIPTION_TW'),
            'a.desc_en' => JText::_('COM_WARRANTY_DESCRIPTION_EN'),
            'a.desc_vi' => JText::_('COM_WARRANTY_DESCRIPTION_VI'),
            'a.id' => JText::_('JGRID_HEADING_ID'),
		);
	}
}
