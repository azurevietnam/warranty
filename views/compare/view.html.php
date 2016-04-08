<?php
// No direct access
defined('_JEXEC') or die;

class WarrantyViewCompare extends JViewLegacy
{
	public function display($tpl = null)
	{
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }
        $this->item = $this->getItem();
        $this->items = $this->getItems();

        parent::display($tpl);
	}

    public function getItem(){
        if($id = JFactory::getApplication()->input->getInt('id')){
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('a.*')
                ->from('`#__warranty_specs` AS a')
                ->select('c.title AS product_title, c.images, c.attribs, c.alias, c.catid, cat.alias AS category_alias')
                ->join('LEFT', '#__content c ON c.id = a.product')
                ->join('LEFT', '#__categories cat ON cat.id = c.catid')
                ->where('a.product='.$id);

            $db->setQuery($query);
            if($item = $db->loadObject()){
                if(is_string($item->images)){
                    $data = new JRegistry;
                    $data->loadString($item->images);
                    $item->images = $data;
                }
                if(is_string($item->attribs)){
                    $data = new JRegistry;
                    $data->loadString($item->attribs);
                    $item->attribs = $data;
                }

                require_once JPATH_SITE . '/components/com_content/helpers/route.php';
                $item->slug    = $item->product . ':' . $item->alias;
                $item->catslug = $item->catid . ':' . $item->category_alias;
                $item->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug));

                return $item;
            }
        }
        return false;
    }

    public function getItems(){
        if($id = JFactory::getApplication()->input->getInt('id')){
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('a.product')
                ->from('`#__warranty_specs` AS a')
                ->select('c.title AS product_title')
                ->join('LEFT', '#__content c ON c.id = a.product')
                ->where('a.product!='.$id);

            $db->setQuery($query);
            if($items = $db->loadObjectList()){
                return $items;
            }
        }
        return false;
    }
}

