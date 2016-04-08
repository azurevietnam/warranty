<?php
// No direct access.
defined('_JEXEC') or die;

class WarrantyControllerCompare extends JControllerLegacy
{
    function __construct(){
        $this->input = JFactory::getApplication()->input;
        parent::__construct();
    }

    public function getProduct(){
        if($id = $this->input->getInt('id')){
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
                    $item->images = json_decode($item->images);
                }
                if(is_string($item->attribs)){
                    $item->attribs = json_decode($item->attribs);
                }
                if($item->images->image_intro) $item->images->image_intro = JUri::root().$item->images->image_intro;

                require_once JPATH_SITE . '/components/com_content/helpers/route.php';
                $item->slug    = $item->product . ':' . $item->alias;
                $item->catslug = $item->catid . ':' . $item->category_alias;
                $item->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug));

                exit(json_encode(array('rs' => true, 'product' => $item)));
            }
        }
        exit(json_encode(array('rs' => false, 'msg' => 'Không tìm thấy sản phẩm!')));
    }
}