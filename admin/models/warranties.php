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
class WarrantyModelWarranties extends JModelList {

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
                'status', 'a.status',
                'shop_id', 'a.shop_id',
                'imei', 'a.imei',
                'accessories', 'a.accessories',
                'errors', 'a.errors',
                'error_codes', 'a.error_codes',
                'warranty', 'a.warranty',
                'note', 'a.note',
                'received', 'a.received',
                'delivery', 'a.delivery',
                's.code', 's.name', 'p.model'
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
        $status = $app->getUserStateFromRequest($this->context . '.filter.status', 'filter_status');
        $this->setState('filter.status', $status);

        $search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $shop = $app->getUserStateFromRequest($this->context . '.filter.shop', 'filter_shop');
        $this->setState('filter.shop', $shop);

        $received_from_date = $app->getUserStateFromRequest($this->context . '.filter.received_from_date', 'filter_received_from_date');
        $this->setState('filter.received_from_date', $received_from_date);

        $received_to_date = $app->getUserStateFromRequest($this->context . '.filter.received_to_date', 'filter_received_to_date');
        $this->setState('filter.received_to_date', $received_to_date);

        $delivery_from_date = $app->getUserStateFromRequest($this->context . '.filter.delivery_from_date', 'filter_delivery_from_date');
        $this->setState('filter.delivery_from_date', $delivery_from_date);

        $delivery_to_date = $app->getUserStateFromRequest($this->context . '.filter.delivery_to_date', 'filter_delivery_to_date');
        $this->setState('filter.delivery_to_date', $delivery_to_date);

        $created_by = $app->getUserStateFromRequest($this->context . '.filter.created_by', 'filter_created_by', '', 'string');
        $this->setState('filter.created_by', $created_by);

        $print_list = $app->getUserStateFromRequest($this->context . '.filter.print_list', 'filter_print_list');
        $this->setState('filter.print_list', $print_list);

        // Load the parameters.
        $params = JComponentHelper::getParams('com_warranty');
        $this->setState('params', $params);

        // List state information.
        parent::populateState('a.id', 'desc');
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
        $id.= ':' . $this->getState('filter.state');

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
        $query->from('`#__warranty_warranties` AS a');

        $query->select('s.code AS shop_code, s.name AS shop_name, s.phone AS shop_phone')
            ->join('LEFT', '#__warranty_shops s ON s.id=a.shop_id');

        $query->select('p.model, p.id AS product_id')
            ->join('LEFT', '#__warranty_products p ON p.imei=a.imei');

        // Filter by shop
        $shop = $this->getState('filter.shop');
        if (is_numeric($shop)) {
            $query->where('s.id = '.(int) $shop);
        }

        // Filter by published state
        $published = $this->getState('filter.status');
        if (is_numeric($published)) {
            $query->where('a.status = '.(int) $published);
        } else if ($published === '') {
            $query->where('(a.status IN (0, 1, 2))');
        }

        // Filter by received from date
        $received_from_date = $this->getState('filter.received_from_date');
        if (!empty($received_from_date)) {
            $query->where('DATE(a.received)>=DATE(' . $db->quote($received_from_date).')');
        }

        // Filter by received to date
        $received_to_date = $this->getState('filter.received_to_date');
        if (!empty($received_to_date)) {
            $query->where('DATE(a.received)<=DATE(' . $db->quote($received_to_date).')');
        }

        // Filter by delivery from date
        $delivery_from_date = $this->getState('filter.delivery_from_date');
        if (!empty($delivery_from_date)) {
            $query->where('DATE(a.delivery)>=DATE(' . $db->quote($delivery_from_date).')');
        }

        // Filter by delivery to date
        $delivery_to_date = $this->getState('filter.delivery_to_date');
        if (!empty($delivery_to_date)) {
            $query->where('DATE(a.delivery)<=DATE(' . $db->quote($delivery_to_date).')');
        }

        // Filter by importer
        $created_by = $this->getState('filter.created_by');
        if ($created_by != '') {
            $query->where('a.created_by='.(int)$created_by);
        }

        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = ' . (int) substr($search, 3));
            } else {
                $search = $db->Quote('%' . $search . '%');
                $query->where('(a.imei LIKE '.$search.' OR s.code LIKE '.$search.' OR s.name LIKE '.$search.')');
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
