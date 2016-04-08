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

jimport('joomla.application.component.controllerform');

/**
 * Warranty controller class.
 */
class WarrantyControllerShop extends JControllerForm
{

    function __construct() {
        $this->view_list = 'shops';
        parent::__construct();
    }

}