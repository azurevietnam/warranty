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
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

/**
 * Products list controller class.
 */
class WarrantyControllerProducts extends JControllerAdmin
{
    public function __construct($config = array()) {
        parent::__construct($config);

        $this->registerTask('exportAll', 'export');
        $this->registerTask('exportFilter', 'export');
    }
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'product', $prefix = 'WarrantyModel', $config = array()){
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}

    public function import(){
        $file = $_FILES['import_file'];
        if($file && $file['tmp_name']){
            if($file['error']>0 && $file['error']<4){
                switch ($file['error']){
                    case 1:
                        $this->setRedirect('index.php?option=com_warranty&view=products', JText::_( 'File upload large than php.ini allowed' ), 'error');
                        return;
                    case 2:
                        $this->setRedirect('index.php?option=com_warranty&view=products', JText::_( 'File upload large than html allowed' ), 'error');
                        return;
                    case 3:
                        $this->setRedirect('index.php?option=com_warranty&view=products', JText::_( 'File upload error!' ), 'error');
                        return;
                }
            }

            $pathinfo = pathinfo($file['name']);
            $extension = strtolower($pathinfo['extension']);
            if($extension != 'xls' && $extension != 'xlsx'){
                $this->setRedirect('index.php?option=com_warranty&view=products', JText::_( 'Invalid file type (*.xls)!' ), 'error');
                return;
            }

            $path = JPATH_SITE.'/images/com_warranty/excel/';

            $filePath = $path.'import.xlsx';
            if($extension == 'xls') $filePath = $path.'import.xls';

            if(!is_dir($path)) JFolder::create($path);
            else if(is_file($filePath)) JFile::delete($filePath);
            if(!JFile::upload($file['tmp_name'], $filePath)){
                $this->setRedirect('index.php?option=com_warranty&view=products', JText::_( 'Error when upload file!' ), 'error');
                return;
            }

            $data = array();
            if($extension == 'xlsx'){
                require_once(JPATH_COMPONENT_ADMINISTRATOR.'/assets/classes/xlsxreader.class.php');
                $reader = new XLSXReader($filePath);
                //$reader->decodeUTF8(true);
                $reader->read();
                # Get the sheet list
                $sheets = $reader->getSheets();
                foreach ($sheets as $sheet) if(!$data){
                    $data = $reader->getSheetDatas($sheet["id"]);
                }
            }else{
                require_once(JPATH_COMPONENT_ADMINISTRATOR.'/assets/classes/excel_reader2.php');

                $reader = new Spreadsheet_Excel_Reader($filePath, false, "UTF-8");
                if($reader->sheets && count($reader->sheets[0]['cells'])){
                    $data = $reader->sheets[0]['cells'];
                }
            }
            if(count($data)>1){
                $this->setRedirect('index.php?option=com_warranty&view=products&task=ajaxImport&record='.(count($data)-1).'&type='.$extension);
                return;
            }

            $this->setRedirect('index.php?option=com_warranty&view=products', JText::_( 'File is empty!' ), 'error');
            return;
        }

        $this->setRedirect('index.php?option=com_warranty&view=products', JText::_( 'File not found!' ), 'error');
        return;
    }

    public function checkActive(){
        $file = $_FILES['import_file'];
        if($file && $file['tmp_name']){
            if($file['error']>0 && $file['error']<4){
                switch ($file['error']){
                    case 1:
                        $this->setRedirect('index.php?option=com_warranty&view=products', JText::_( 'File upload large than php.ini allowed' ), 'error');
                        return;
                    case 2:
                        $this->setRedirect('index.php?option=com_warranty&view=products', JText::_( 'File upload large than html allowed' ), 'error');
                        return;
                    case 3:
                        $this->setRedirect('index.php?option=com_warranty&view=products', JText::_( 'File upload error!' ), 'error');
                        return;
                }
            }

            $pathinfo = pathinfo($file['name']);
            $extension = strtolower($pathinfo['extension']);
            if($extension != 'xls' && $extension != 'xlsx'){
                $this->setRedirect('index.php?option=com_warranty&view=products', JText::_( 'Invalid file type (*.xls)!' ), 'error');
                return;
            }

            $path = JPATH_SITE.'/images/com_warranty/excel/';

            $filePath = $path.'check.xlsx';
            if($extension == 'xls') $filePath = $path.'check.xls';

            if(!is_dir($path)) JFolder::create($path);
            else if(is_file($filePath)) JFile::delete($filePath);
            if(!JFile::upload($file['tmp_name'], $filePath)){
                $this->setRedirect('index.php?option=com_warranty&view=products', JText::_( 'Error when upload file!' ), 'error');
                return;
            }

            $data = array();
            if($extension == 'xlsx'){
                require_once(JPATH_COMPONENT_ADMINISTRATOR.'/assets/classes/xlsxreader.class.php');
                $reader = new XLSXReader($filePath);
                //$reader->decodeUTF8(true);
                $reader->read();
                # Get the sheet list
                $sheets = $reader->getSheets();
                foreach ($sheets as $sheet) if(!$data){
                    $data = $reader->getSheetDatas($sheet["id"]);
                }
            }else{
                require_once(JPATH_COMPONENT_ADMINISTRATOR.'/assets/classes/excel_reader2.php');

                $reader = new Spreadsheet_Excel_Reader($filePath, false, "UTF-8");
                if($reader->sheets && count($reader->sheets[0]['cells'])){
                    $data = $reader->sheets[0]['cells'];
                }
            }
            if(count($data)>1){
                $this->setRedirect('index.php?option=com_warranty&view=products&task=checkActive&record='.(count($data)-1).'&type='.$extension);
                return;
            }

            $this->setRedirect('index.php?option=com_warranty&view=products', JText::_( 'File is empty!' ), 'error');
            return;
        }

        $this->setRedirect('index.php?option=com_warranty&view=products', JText::_( 'File not found!' ), 'error');
        return;
    }

    public function checkImeiExist($imei){
        $db = JFactory::getDbo();
        $db->setQuery('SELECT * FROM #__warranty_products WHERE imei='.$db->quote($imei));
        if($rs = $db->loadObject()) return $rs;
        return false;
    }

    public function detectUnicode($str){
        return $str;
    }

    public function isFloat($num){
        $floatVal = floatval($num);
        if($floatVal && intval($floatVal) != $floatVal) return true;
        return false;
    }

    public function ajaxImport(){
        $limit = 100;
        $input = JFactory::getApplication()->input;
        $offset = $input->getInt('offset');
        $type = $input->get('type', 'xls');
        $file = JPATH_SITE.'/images/com_warranty/excel/'.'import.xls';
        if($type == 'xlsx') $file = JPATH_SITE.'/images/com_warranty/excel/'.'import.xlsx';

        if(!is_file($file)){
            exit(json_encode(array('rs'=>false, 'msg'=>'File not found!')));
        }

        $db = JFactory::getDbo();
        $inserted = 0;
        $updated = 0;
        $cancelled = 0;
        $complete = false;

        $data = array();
        if($type == 'xlsx'){
            require_once(JPATH_COMPONENT_ADMINISTRATOR.'/assets/classes/xlsxreader.class.php');
            $reader = new XLSXReader($file);
            //$reader->decodeUTF8(true);
            $reader->read();
            # Get the sheet list
            $sheets = $reader->getSheets();
            foreach ($sheets as $sheet) if(!$data){
                $data = $reader->getSheetDatas($sheet["id"]);
            }
        }else{
            require_once(JPATH_COMPONENT_ADMINISTRATOR.'/assets/classes/excel_reader2.php');
            $reader = new Spreadsheet_Excel_Reader($file, false, "UTF-8");
            $data = $reader->sheets[0]['cells'];
        }

        if($offset >= count($data)){
            if(is_file($file)) JFile::delete($file);
            exit(json_encode(array('rs'=>true, 'inserted'=>$inserted, 'updated'=>$updated, 'cancelled'=>$cancelled, 'complete'=>true)));
        }

        $dataProcess = array_slice($data, $offset, $limit);

        if(count($dataProcess) == $limit){
            $nextOffset = $offset + $limit;
        }else{
            $complete = true;
            $nextOffset = count($data);
            if(is_file($file)) JFile::delete($file);
        }

        $index = -1;
        if($type == 'xls') $index = 0;

        $db->setQuery('SELECT id, code FROM #__warranty_errors');
        $errors = $db->loadObjectList();
        $tmp = array();
        foreach($errors as $item){
            $tmp[$item->code] = $item->id;
        }
        $errors = $tmp;
        unset($tmp);

        $date = JFactory::getDate()->toSql();
        $user = JFactory::getUser()->id;

        foreach($dataProcess as $k=>$row){
            $active = isset($row[$index+1]) ? $row[$index+1] : '';
            $shop_name = isset($row[$index+2]) ? (is_numeric($row[$index+2]) ? '' : $row[$index+2]) : '';
            $shop_address = isset($row[$index+3]) ? (($row[$index+3] == 42) ? '#N/A' : (is_numeric($row[$index+3]) ? '' : $row[$index+3])) : '';
            $shop_region = isset($row[$index+4]) ? (($row[$index+4] == 42) ? '#N/A' : $row[$index+4]) : '';
            $sender = isset($row[$index+5]) ? $row[$index+5] : '';
            if(isset($row[$index+6])){
                $imei = $row[$index+6];
                if($imei == '#N/A'){
                    $imei = '';
                }elseif($this->isFloat($imei)){
                    $imei = number_format($row[$index+6],0,'.','');
                }
            }else{
                $imei = '';
            }
            $model = isset($row[$index+7]) ? (($row[$index+7] == 42) ? '#N/A' : $row[$index+7]) : '';
            $color = isset($row[$index+8]) ? (($row[$index+8] == 42) ? '#N/A' : $row[$index+8]) : '';
            $phone_status = isset($row[$index+9]) ? $row[$index+9] : '';
            $sell_in = isset($row[$index+10]) ? (($row[$index+10] == 42) ? '#N/A' : $row[$index+10]) : '';
            $error = isset($row[$index+11]) ? (($row[$index+11] == 42) ? '#N/A' : $row[$index+11]) : '';
            $note = isset($row[$index+12]) ? (($row[$index+12] == 42) ? '#N/A' : $row[$index+12]) : '';
            $status = isset($row[$index+13]) ? ($row[$index+13] ? intval($row[$index+13]) : 0) : 0;
            $manufacturer = isset($row[$index+14]) ? (is_numeric($row[$index+14]) ? '' : $row[$index+14]) : '';
            if($error && isset($errors[$error])) $error = $errors[$error];

            if(!$imei || !$active){
                $cancelled +=1;
                continue;
            }

            $query = $db->getQuery(true)
                ->set('active='.$db->quote($active))
                ->set('shop_name='.$db->quote($shop_name))
                ->set('shop_address='.$db->quote($shop_address))
                ->set('shop_region='.$db->quote($shop_region))
                ->set('sender='.$db->quote($sender))
                ->set('model='.$db->quote($model))
                ->set('color='.$db->quote($color))
                ->set('phone_status='.$db->quote($phone_status))
                ->set('sell_in='.$db->quote($sell_in))
                ->set('error='.$db->quote($error))
                ->set('note='.$db->quote($note))
                ->set('created='.$db->quote($date))
                ->set('created_by='.$db->quote($user))
                ->set('status='.$db->quote($status))
                ->set('manufacturer='.$db->quote($manufacturer));
            if($this->checkImeiExist($row[$index+6])){
                $updated +=1;
                $query->update('#__warranty_products')->where('imei='.$db->quote($imei));
            }else{
                $inserted +=1;
                $query->insert('#__warranty_products')->set('imei='.$db->quote($imei));
            }

            $db->setQuery($query);
            if(!$db->execute()){
                exit(json_encode(array('rs'=>false, 'msg'=>$db->getErrorMsg())));
            }
        }

        exit(json_encode(array('rs'=>true, 'inserted'=>$inserted, 'updated'=>$updated, 'cancelled'=>$cancelled, 'offset'=>$nextOffset, 'complete'=> $complete)));
    }

    public function ajaxCheck(){
        $limit = 100;
        $input = JFactory::getApplication()->input;
        $offset = $input->getInt('offset');
        $type = $input->get('type', 'xls');
        $file = JPATH_SITE.'/images/com_warranty/excel/'.'check.xls';
        if($type == 'xlsx') $file = JPATH_SITE.'/images/com_warranty/excel/'.'check.xlsx';
        $buffer = '';

        if(!is_file($file)){
            exit(json_encode(array('rs'=>false, 'msg'=>'File not found!')));
        }

        $fileRs = JPATH_SITE.'/images/com_warranty/excel/'.'check.txt';
        if($offset == 1){
            if(is_file($fileRs)) JFile::delete($fileRs);
            if(!JFile::write($fileRs, $buffer)){
                exit(json_encode(array('rs'=>false, 'msg'=>'Can not create result file!')));
            };
        }

        $active = 0;
        $inactive = 0;
        $cancelled = 0;
        $complete = false;

        $data = array();
        if($type == 'xlsx'){
            require_once(JPATH_COMPONENT_ADMINISTRATOR.'/assets/classes/xlsxreader.class.php');
            $reader = new XLSXReader($file);
            //$reader->decodeUTF8(true);
            $reader->read();
            # Get the sheet list
            $sheets = $reader->getSheets();
            foreach ($sheets as $sheet) if(!$data){
                $data = $reader->getSheetDatas($sheet["id"]);
            }
        }else{
            require_once(JPATH_COMPONENT_ADMINISTRATOR.'/assets/classes/excel_reader2.php');
            $reader = new Spreadsheet_Excel_Reader($file, false, "UTF-8");
            $data = $reader->sheets[0]['cells'];
        }

        if($offset >= count($data)){
            if(is_file($file)) JFile::delete($file);
            exit(json_encode(array('rs'=>true, 'active'=>$active, 'inactive'=>$inactive, 'cancelled'=>$cancelled, 'complete'=>true)));
        }

        $dataProcess = array_slice($data, $offset, $limit);

        if(count($dataProcess) == $limit){
            $nextOffset = $offset + $limit;
        }else{
            $complete = true;
            $nextOffset = count($data);
            if(is_file($file)) JFile::delete($file);
        }

        $index = -1;
        if($type == 'xls') $index = 0;

        foreach($dataProcess as $k=>$row){
            $imei = isset($row[$index+1]) ? number_format($row[$index+1],0,'.','') : '';

            if(!$imei){
                $cancelled +=1;
                $buffer .= "\n";
                continue;
            }
            $product = $this->checkImeiExist($imei);

            if($product){
                if($product->status)
                    $active +=1;
                else
                    $inactive +=1;

                $buffer .= $imei." 1 ".$product->status."\n";
            }else{
                $inactive +=1;
                $buffer .= $imei." 0 0\n";
            }
        }

        $current = file_get_contents($fileRs);
        $current .= $buffer;

        if(!file_put_contents($fileRs, $current)){
            exit(json_encode(array('rs'=>false, 'msg'=>'Can not write result file!')));
        }

        exit(json_encode(array('rs'=>true, 'active'=>$active, 'inactive'=>$inactive, 'cancelled'=>$cancelled, 'offset'=>$nextOffset, 'complete'=> $complete)));
    }

    public function getCheckResult(){
        require_once JPATH_COMPONENT_ADMINISTRATOR.'/assets/classes/xlsxwriter.class.php';
        $fileRs = JPATH_SITE.'/images/com_warranty/excel/'.'check.txt';
        $current = file_get_contents($fileRs);
        $array = array();
        if($current){
            $array = explode("\n", $current);
        }

        $header = array(
            'IMEI'=>'string',
            'EXIST'=>'string',
            'ACTIVE'=>'string',
        );

        $data = array();
        $writer = new XLSXWriter();

        foreach ($array as $item) {
            if($item) $temp = explode(' ', $item);
            else $temp = array('','','');
            $data[] = $temp;
        }

        $filename = Date('d-m-Y').'-checkActive.xlsx';
        header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        $writer->writeSheet($data, 'CheckActive', $header);
        $writer->writeToStdOut();
        exit();
    }

    public function export(){
        ini_set('memory_limit', '256M');
        require_once JPATH_COMPONENT_ADMINISTRATOR.'/assets/classes/xlsxwriter.class.php';
        JModelLegacy::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . '/models', 'WarrantyModel');

        // Get an instance of the generic products model
        $model = JModelLegacy::getInstance('Products', 'WarrantyModel', array('ignore_request' => true));

        // Set application parameters in model
        $app = JFactory::getApplication('administrator');
        $context = $model->get('context');
        $params = JComponentHelper::getParams('com_warranty');
        $model->setState('params', $params);

        // Set the filters based on the module params
        if($this->getTask() == 'exportFilter'){
            $model->setState('filter.model', $app->getUserState($context . '.filter.model'));
            $model->setState('filter.shop_name', $app->getUserState($context . '.filter.shop_name'));
            $model->setState('filter.shop_region', $app->getUserState($context . '.filter.shop_region'));
            $model->setState('filter.color', $app->getUserState($context . '.filter.color'));
            $model->setState('filter.sell_in', $app->getUserState($context . '.filter.sell_in'));
            $model->setState('filter.search', $app->getUserState($context . '.filter.search'));
            $model->setState('filter.from_date', $app->getUserState($context . '.filter.from_date'));
            $model->setState('filter.to_date', $app->getUserState($context . '.filter.to_date'));
            $model->setState('filter.error', $app->getUserState($context . '.filter.error'));
            $model->setState('filter.created_by', $app->getUserState($context . '.filter.created_by'));
            $model->setState('filter.import_from_date', $app->getUserState($context . '.filter.import_from_date'));
            $model->setState('filter.import_to_date', $app->getUserState($context . '.filter.import_to_date'));
            $model->setState('filter.status', $app->getUserState($context . '.filter.status'));
            $model->setState('filter.manufacturer', $app->getUserState($context . '.filter.manufacturer'));
        }

        $model->setState('list.start', 0);
        $model->setState('list.limit', -1);
        $model->setState('list.ordering', 'a.id');
        $model->setState('list.direction', 'ASC');

        $items = $model->getItems();

        $header = array(
            'TIME'=>'string',
            'NAME'=>'string',
            'ADD'=>'string',
            'REGION'=>'string',
            'SENDER'=>'string',
            'IMEI'=>'string',
            'MODEL'=>'string',
            'COLOR'=>'string',
            'PHONE STATUS'=>'string',
            'SELL IN'=>'string',
            'ERROR'=>'string',
            'NOTE'=>'string',
            'STATUS'=>'string',
            'MANUFACTURER'=>'string',
        );
        $data = array();
        $writer = new XLSXWriter();

        foreach ($items as $item) {
            $temp = array();
            $temp[] = $item->active.'.0';
            $temp[] = $item->shop_name;
            $temp[] = $item->shop_address;
            $temp[] = $item->shop_region;
            $temp[] = $item->sender.' ';
            $temp[] = $item->imei.' ';
            $temp[] = $item->model;
            $temp[] = $item->color;
            $temp[] = str_replace("\n", " ", $item->phone_status);
            $temp[] = $item->sell_in;
            $temp[] = $item->error_code;
            $temp[] = str_replace("\n", " ", $item->note);
            $temp[] = $item->status;
            $temp[] = $item->manufacturer;
            $data[] = $temp;
        }

        $filename = Date('D-M-Y').'-Export.xlsx';
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