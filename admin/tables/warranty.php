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
 * Type Table class
 */
class WarrantyTableWarranty extends JTable
{

	public function __construct(&$db)
	{
        $this->created = JFactory::getDate()->toSql();
        $this->created_by = JFactory::getUser()->id;
		parent::__construct('#__warranty_warranties', 'id', $db);
	}

    public function publish($pks = null, $state = 1, $userId = 0) {
        // Initialise variables.
        $k = $this->_tbl_key;

        // Sanitize input.
        JArrayHelper::toInteger($pks);
        $userId = (int) $userId;
        $state  = (int) $state;

        // If there are no primary keys set check to see if the instance key is set.
        if (empty($pks)) {
            if ($this->$k) {
                $pks = array($this->$k);
            }
            // Nothing to set publishing state on, return false.
            else {
                $this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
                return false;
            }
        }

        // Build the WHERE clause for the primary keys.
        $where = $k . '=' . implode(' OR ' . $k . '=', $pks);

        // Determine if there is checkin support for the table.
        if (!property_exists($this, 'checked_out') || !property_exists($this, 'checked_out_time')) {
            $checkin = '';
        } else {
            $checkin = ' AND (checked_out = 0 OR checked_out = ' . (int) $userId . ')';
        }

        // Update the publishing state for rows with the given primary keys.
        $this->_db->setQuery(
            'UPDATE `' . $this->_tbl . '`' .
            ' SET `status` = ' . (int) $state .
            ' WHERE (' . $where . ')' .
            $checkin
        );
        $this->_db->execute();

        // If checkin is supported and all rows were adjusted, check them in.
        if ($checkin && (count($pks) == $this->_db->getAffectedRows())) {
            // Checkin each row.
            foreach ($pks as $pk) {
                $this->checkin($pk);
            }
        }

        // If the JTable instance value is in the list of primary keys that were set, set the instance.
        if (in_array($this->$k, $pks)) {
            $this->status = $state;
        }

        $this->setError('');

        return true;
    }

    public function bind($array, $ignore = array())
    {
        return parent::bind($array, $ignore);
    }

    public function store($updateNulls = false)
    {
        // Verify that the code is unique
        $table = JTable::getInstance('Product', 'WarrantyTable');
        if (!$table->load(array('imei' => $this->imei)) || ($table->load(array('imei' => $this->imei)) && !$table->id))
        {
            $this->setError(JText::_('COM_WARRANTY_ERROR_IMEI_NOT_FOUND'));
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
