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

/**
 * Warranty helper.
 */
class WarrantyHelper {

    /**
     * Configure the Linkbar.
     */
    public static function addSubmenu($vName = '') {
        JHtmlSidebar::addEntry(
            JText::_('COM_WARRANTY_TITLE_PRODUCTS'),
            'index.php?option=com_warranty&view=products',
            $vName == 'products'
        );
        JHtmlSidebar::addEntry(
            JText::_('COM_WARRANTY_TITLE_SPECS'),
            'index.php?option=com_warranty&view=specs',
            $vName == 'specs'
        );
        JHtmlSidebar::addEntry(
            JText::_('COM_WARRANTY_TITLE_MODELS'),
                'index.php?option=com_warranty&view=types',
            $vName == 'types'
        );
        JHtmlSidebar::addEntry(
            JText::_('COM_WARRANTY_TITLE_ERRORS'),
            'index.php?option=com_warranty&view=errors',
            $vName == 'errors'
        );
        JHtmlSidebar::addEntry(
            JText::_('COM_WARRANTY_TITLE_REQUESTS').self::getRequestPending(),
            'index.php?option=com_warranty&view=requests',
            $vName == 'requests'
        );
        JHtmlSidebar::addEntry(
            JText::_('COM_WARRANTY_TITLE_SHOPS').self::getRequestPending(),
            'index.php?option=com_warranty&view=shops',
            $vName == 'shops'
        );
        JHtmlSidebar::addEntry(
            JText::_('COM_WARRANTY_TITLE_WARRANTIES'),
            'index.php?option=com_warranty&view=warranties',
            $vName == 'warranties'
        );
        /*JHtmlSidebar::addEntry(
            JText::_('COM_WARRANTY_TITLE_PAPERS').self::getRequestPending(),
            'index.php?option=com_warranty&view=papers',
            $vName == 'papers'
        );*/
        JHtmlSidebar::addEntry(
            JText::_('COM_WARRANTY_TITLE_HELPS'),
            'index.php?option=com_warranty&view=helps',
            $vName == 'helps'
        );

    }

    public static function getRequestPending(){
        $db = JFactory::getDbo();
        $db->setQuery('SELECT COUNT(id) FROM #__warranty_requests WHERE status = 0');
        $rs = $db->loadResult();
        if($rs) return ' ('.$rs.') ';
        return '';
    }
    /**
     * Gets a list of the actions that can be performed.
     *
     * @return	JObject
     * @since	1.6
     */
    public static function getActions() {
        $user = JFactory::getUser();
        $result = new JObject;

        $assetName = 'com_warranty';

        $actions = array(
            'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
        );

        foreach ($actions as $action) {
            $result->set($action, $user->authorise($action, $assetName));
        }

        return $result;
    }


}
