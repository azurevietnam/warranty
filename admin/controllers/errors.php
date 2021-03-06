<?php
/**
 * @version     1.0.0
 * @package     com_warranty
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Chau Dang Khoa <boyupdatetofuture@gmail.com> - 
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * Errors list controller class.
 */
class WarrantyControllerErrors extends JControllerAdmin
{
    public function __construct($config = array()) {
        parent::__construct($config);
    }
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'error', $prefix = 'WarrantyModel', $config = array()){
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
}