<?php
class Web4pro_Linksgenerator_Block_Adminhtml_Linksgenerator_Edit_Tabs_Custom extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('cmsPageGrid');
        $this->setDefaultSort('identifier');
        $this->setDefaultDir('ASC');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('cms/page')->getCollection();
        /* @var $collection Mage_Cms_Model_Mysql4_Page_Collection */
        $collection->setFirstStoreFlag(true);
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function getSelectedValues(){
        /** @var Web4pro_Linksgenerator_Model_Resource_Bind_Collection $collection_bind */
        $collection_bind = Mage::getModel('linksgenerator/bind')->getCollection();
        //$collection_page = Mage::getModel('cms/page')->getCollection();
        //$pages = $collection_page->addFieldToFilter('page_id','1');
        $link_id = $this->getRequest()->getParam('id');

        $collection_bind->addFieldToFilter('link_id', $link_id);

        $pages = [];

        foreach ($collection_bind as $bindItem){

                array_push($pages, $bindItem->getPageId());
        }

        //print_r($pages);

        /*$arr1 = [
            '1' => '4',
        ];

        $arr2 = null;

        $result = array_diff($arr1, $arr2);

        print_r($result);*/

        return $pages;
    }

    protected function _prepareColumns()
    {

        $baseUrl = $this->getUrl();

        $this->addColumn('page_id', array(
            'header'    => Mage::helper('page')->__('Pages'),
            'header_css_class' => 'a-center',
            'index'     => 'page_id',
            'type'      => 'checkbox',
            'align'     => 'center',
            'field_name'      => 'page_ids[]',
            'values'    => $this->getSelectedValues(),
        ));

        $this->addColumn('title', array(
            'header'    => Mage::helper('cms')->__('Title'),
            'align'     => 'left',
            'index'     => 'title',
        ));

        $this->addColumn('identifier', array(
            'header'    => Mage::helper('cms')->__('URL Key'),
            'align'     => 'left',
            'index'     => 'identifier'
        ));



        $this->addColumn('root_template', array(
            'header'    => Mage::helper('cms')->__('Layout'),
            'index'     => 'root_template',
            'type'      => 'options',
            'options'   => Mage::getSingleton('page/source_layout')->getOptions(),
        ));

        /**
         * Check is single store mode
         */
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'        => Mage::helper('cms')->__('Store View'),
                'index'         => 'store_id',
                'type'          => 'store',
                'store_all'     => true,
                'store_view'    => true,
                'sortable'      => false,
                'filter_condition_callback'
                => array($this, '_filterStoreCondition'),
            ));
        }

        $this->addColumn('is_active', array(
            'header'    => Mage::helper('cms')->__('Status'),
            'index'     => 'is_active',
            'type'      => 'options',
            'options'   => Mage::getSingleton('cms/page')->getAvailableStatuses()
        ));

        $this->addColumn('creation_time', array(
            'header'    => Mage::helper('cms')->__('Date Created'),
            'index'     => 'creation_time',
            'type'      => 'datetime',
        ));

        $this->addColumn('update_time', array(
            'header'    => Mage::helper('cms')->__('Last Modified'),
            'index'     => 'update_time',
            'type'      => 'datetime',
        ));

        $this->addColumn('page_actions', array(
            'header'    => Mage::helper('cms')->__('Action'),
            'width'     => 10,
            'sortable'  => false,
            'filter'    => false,
            'renderer'  => 'adminhtml/cms_page_grid_renderer_action',
        ));

        return parent::_prepareColumns();
    }

    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }

        $this->getCollection()->addStoreFilter($value);
    }

    /**
     * Row click url
     *
     * @return string
     */


}