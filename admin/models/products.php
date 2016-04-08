<?php

/**
 * @version     1.0.0
 * @package     com_warranty
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Chau Dang Khoa <boyupdatetofuture@gmail.com> - 
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Warranty records.
 */
class WarrantyModelProducts extends JModelList {

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'shop_name', 'a.shop_name',
                'shop_address', 'a.shop_address',
                'shop_region', 'a.shop_region',
                'sender', 'a.sender',
                'customer_name', 'a.customer_name',
                'customer_address', 'a.customer_address',
                'customer_phone', 'a.customer_phone',
                'customer_note', 'a.customer_note',
                'active', 'a.active',
                'model', 'a.model',
                'color', 'a.color',
                'imei', 'a.imei',
                'note', 'a.note',
                'image', 'a.image',
                'phone_status', 'a.phone_status',
                'sell_in', 'a.sell_in',
                'error', 'a.error', 'error_code',
                'status', 'a.status',
                'manufacturer', 'a.manufacturer'
            );
        }

        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     */
    protected function populateState($ordering = null, $direction = null) {
        // Initialise variables.
        $app = JFactory::getApplication('administrator');

        // Load the filter state.
        $search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $published = $app->getUserStateFromRequest($this->context . '.filter.status', 'filter_status', '', 'string');
        $this->setState('filter.status', $published);

        $from_date = $app->getUserStateFromRequest($this->context . '.filter.from_date', 'filter_from_date');
        $this->setState('filter.from_date', $from_date);

        $to_date = $app->getUserStateFromRequest($this->context . '.filter.to_date', 'filter_to_date');
        $this->setState('filter.to_date', $to_date);

        $shop_name = $app->getUserStateFromRequest($this->context . '.filter.shop_name', 'filter_shop_name', '', 'string');
        $this->setState('filter.shop_name', $shop_name);

        $shop_region = $app->getUserStateFromRequest($this->context . '.filter.shop_region', 'filter_shop_region', '', 'string');
        $this->setState('filter.shop_region', $shop_region);

        $model = $app->getUserStateFromRequest($this->context . '.filter.model', 'filter_model', '', 'string');
        $this->setState('filter.model', $model);

        $color = $app->getUserStateFromRequest($this->context . '.filter.color', 'filter_color', '', 'string');
        $this->setState('filter.color', $color);

        $sell_in = $app->getUserStateFromRequest($this->context . '.filter.sell_in', 'filter_sell_in', '', 'string');
        $this->setState('filter.sell_in', $sell_in);

        $error = $app->getUserStateFromRequest($this->context . '.filter.error', 'filter_error', '', 'string');
        $this->setState('filter.error', $error);

        $created_by = $app->getUserStateFromRequest($this->context . '.filter.created_by', 'filter_created_by', '', 'string');
        $this->setState('filter.created_by', $created_by);

        $import_from_date = $app->getUserStateFromRequest($this->context . '.filter.import_from_date', 'filter_import_from_date');
        $this->setState('filter.import_from_date', $import_from_date);

        $import_to_date = $app->getUserStateFromRequest($this->context . '.filter.import_to_date', 'filter_import_to_date');
        $this->setState('filter.import_to_date', $import_to_date);

        $manufacturer = $app->getUserStateFromRequest($this->context . '.filter.manufacturer', 'filter_manufacturer');
        $this->setState('filter.manufacturer', $manufacturer);

        // Load the parameters.
        $params = JComponentHelper::getParams('com_warranty');
        $this->setState('params', $params);

        // List state information.
        parent::populateState('a.id', 'asc');
    }

    /**
     * Method to get a store id based on model configuration state.
     *
     * This is necessary because the model is used by the component and
     * different modules that might need different sets of data or different
     * ordering requirements.
     *
     * @param	string		$id	A prefix for the store id.
     * @return	string		A store id.
     * @since	1.6
     */
    protected function getStoreId($id = '') {
        // Compile the store id.
        $id.= ':' . $this->getState('filter.search');
        $id.= ':' . $this->getState('filter.status');

        return parent::getStoreId($id);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return	JDatabaseQuery
     * @since	1.6
     */
    protected function getListQuery() {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
            $this->getState(
                    'list.select', 'DISTINCT a.*'
            )
        );
        $query->from('`#__warranty_products` AS a');

        $query->select('e.code AS error_code')
            ->join('LEFT', '#__warranty_errors e ON e.id=a.error');

        // Filter by published state
        $published = $this->getState('filter.status');
        if (is_numeric($published)) {
            $query->where('a.status = '.(int) $published);
        } else if ($published === '') {
            $query->where('(a.status IN (0, 1))');
        }

        // Filter by shop name
        $shop_name = $this->getState('filter.shop_name');
        if (!empty($shop_name)) {
            $query->where('a.shop_name = ' . $db->quote($shop_name));
        }

        // Filter by shop region
        $shop_region = $this->getState('filter.shop_region');
        if (!empty($shop_region)) {
            $query->where('a.shop_region = ' . $db->quote($shop_region));
        }

        // Filter by model
        $model = $this->getState('filter.model');
        if (!empty($model)) {
            $query->where('a.model = ' . $db->quote($model));
        }

        $color = $this->getState('filter.color');
        if (!empty($color)) {
            $query->where('a.color = ' . $db->quote($color));
        }

        $sell_in = $this->getState('filter.sell_in');
        if (!empty($sell_in)) {
            $query->where('a.sell_in = ' . $db->quote($sell_in));
        }

        // Filter by from date
        $from_date = $this->getState('filter.from_date');
        if (!empty($from_date)) {
            $query->where('DATE(a.active)>=DATE(' . $db->quote($from_date).')');
        }

        // Filter by to date
        $to_date = $this->getState('filter.to_date');
        if (!empty($to_date)) {
            $query->where('DATE(a.active)<=DATE(' . $db->quote($to_date).')');
        }

        // Filter by error
        $error = $this->getState('filter.error');
        if (!empty($error)) {
            $query->where('a.error='.(int)$error);
        }

        // Filter by importer
        $created_by = $this->getState('filter.created_by');
        if ($created_by != '') {
            $query->where('a.created_by='.(int)$created_by);
        }

        // Filter by import from date
        $import_from_date = $this->getState('filter.import_from_date');
        if (!empty($import_from_date)) {
            $query->where('DATE(a.created)>=DATE(' . $db->quote($import_from_date).')');
        }

        // Filter by import to date
        $import_to_date = $this->getState('filter.import_to_date');
        if (!empty($import_to_date)) {
            $query->where('DATE(a.created)<=DATE(' . $db->quote($import_to_date).')');
        }

        // Filter by manufacturer
        $manufacturer = $this->getState('filter.manufacturer');
        if ($manufacturer != '') {
            $query->where('a.manufacturer='.$db->quote($manufacturer));
        }

        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = ' . (int) substr($search, 3));
            } else {
                $search = $db->Quote('%' . $search . '%');
                $query->where('( (a.imei LIKE '.$search.') OR (CAST(a.active AS CHAR) LIKE '.$search.') OR (a.shop_name LIKE '.$search.') OR (a.shop_address LIKE '.$search.'))');
            }
        }

        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');
        if ($orderCol && $orderDirn) {
            $query->order($db->escape($orderCol . ' ' . $orderDirn));
        }

        return $query;
    }

    public function getItems() {
        $items = parent::getItems();

        return $items;
    }

}
