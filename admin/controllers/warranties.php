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
 * Products list controller class.
 */
class WarrantyControllerWarranties extends JControllerAdmin
{
    public function __construct($config = array()) {
        parent::__construct($config);

		$this->registerTask('wait', 'publish');
		$this->registerTask('accept', 'publish');
		$this->registerTask('deny', 'publish');

		$this->registerTask('exportAll', 'export');
		$this->registerTask('exportFilter', 'export');
    }
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'warranty', $prefix = 'WarrantyModel', $config = array()){
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}

	public function publish()
	{
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

		// Get items to publish from the request.
		$cid = JFactory::getApplication()->input->get('cid', array(), 'array');
		$data = array('wait' => 0, 'accept' => 1, 'deny' => 2);
		$task = $this->getTask();
		$value = JArrayHelper::getValue($data, $task, 0, 'int');

		if (empty($cid))
		{
			JLog::add(JText::_($this->text_prefix . '_NO_ITEM_SELECTED'), JLog::WARNING, 'jerror');
		}
		else
		{
			// Get the model.
			$model = $this->getModel();

			// Make sure the item ids are integers
			JArrayHelper::toInteger($cid);

			// Publish the items.
			try
			{
				$model->publish($cid, $value);

				if ($value == 1)
				{
					$ntext = $this->text_prefix . '_N_ITEMS_ACCEPT';
				}
				elseif ($value == 0)
				{
					$ntext = $this->text_prefix . '_N_ITEMS_WAIT';
				}
				else
				{
					$ntext = $this->text_prefix . '_N_ITEMS_DENY';
				}
				$this->setMessage(JText::plural($ntext, count($cid)));
			}
			catch (Exception $e)
			{
				$this->setMessage($e->getMessage(), 'error');
			}

		}
		$extension = $this->input->get('extension');
		$extensionURL = ($extension) ? '&extension=' . $extension : '';
		$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list . $extensionURL, false));
	}

	public function export(){
		ini_set('memory_limit', '256M');
		require_once JPATH_COMPONENT_ADMINISTRATOR.'/assets/classes/xlsxwriter.class.php';
		JModelLegacy::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . '/models', 'WarrantyModel');

		// Get an instance of the generic products model
		$model = JModelLegacy::getInstance('Warranties', 'WarrantyModel', array('ignore_request' => true));

		// Set application parameters in model
		$app = JFactory::getApplication('administrator');
		$context = $model->get('context');
		$params = JComponentHelper::getParams('com_warranty');
		$model->setState('params', $params);

		// Set the filters based on the module params
		if($this->getTask() == 'exportFilter'){
			$model->setState('filter.status', $app->getUserState($context . '.filter.status'));
			$model->setState('filter.search', $app->getUserState($context . '.filter.search'));
			$model->setState('filter.shop', $app->getUserState($context . '.filter.shop'));
			$model->setState('filter.received_from_date', $app->getUserState($context . '.filter.received_from_date'));
			$model->setState('filter.received_to_date', $app->getUserState($context . '.filter.received_to_date'));
			$model->setState('filter.delivery_from_date', $app->getUserState($context . '.filter.delivery_from_date'));
			$model->setState('filter.delivery_to_date', $app->getUserState($context . '.filter.delivery_to_date'));
			$model->setState('filter.delivery_to_date', $app->getUserState($context . '.filter.delivery_to_date'));
			$model->setState('filter.created_by', $app->getUserState($context . '.filter.created_by'));
		}

		$model->setState('list.start', 0);
		$model->setState('list.limit', -1);
		$model->setState('list.ordering', 'a.id');
		$model->setState('list.direction', 'ASC');

		$items = $model->getItems();

		$header = array(
			'Received'=>'string',
			'Customer Code'=>'string',
			'Customer Name'=>'string',
			'Customer Phone'=>'string',
			'Model'=>'string',
			'Imei'=>'string',
			'Accessories'=>'string',
			'Errors'=>'string',
			'Error Codes'=>'string',
			'Delivery'=>'string',
			'Warranty'=>'string',
			'Note'=>'string',
		);
		$data = array();
		$writer = new XLSXWriter();

		foreach ($items as $item) {
			$temp = array();
			$temp[] = $item->received;
			$temp[] = $item->shop_code;
			$temp[] = $item->shop_name;
			$temp[] = $item->shop_phone.' ';
			$temp[] = $item->model;
			$temp[] = $item->imei.' ';
			$temp[] = str_replace("\n", " ", $item->accessories);
			$temp[] = str_replace("\n", " ", $item->errors);
			$temp[] = $item->error_codes;
			if($item->delivery == '0000-00-00') $temp[] = '';
			else $temp[] = $item->delivery;
			$temp[] = str_replace("\n", " ", $item->warranty);
			$temp[] = str_replace("\n", " ", $item->note);
			$data[] = $temp;
		}

		$filename = Date('Y-m-d').'-WarrantyExport.xlsx';
		header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
		header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
		header('Content-Transfer-Encoding: binary');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');

		$writer->writeSheet($data, 'Export', $header);
		$writer->writeToStdOut();
		exit();
	}
}