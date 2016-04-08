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
 * error Table class
 */
class WarrantyTableerror extends JTable
{

	public function __construct(&$db)
	{
		parent::__construct('#__warranty_errors', 'id', $db);
	}

    public function bind($array, $ignore = array())
    {
        return parent::bind($array, $ignore);
    }

    public function store($updateNulls = false)
    {
        // Verify that the imei is unique
        $table = JTable::getInstance('Error', 'WarrantyTable');
        if ($table->load(array('code' => $this->code)) && ($table->id != $this->id || $this->id == 0))
        {
            $this->setError(JText::_('COM_WARRANTY_ERROR_UNIQUE_CODE'));

            return false;
        }

        return parent::store($updateNulls);
    }

    public function check()
    {
        return true;
    }

    public function delete($pk = null){
        if(parent::delete($pk)){
            return true;
        }
        return false;
    }

}
