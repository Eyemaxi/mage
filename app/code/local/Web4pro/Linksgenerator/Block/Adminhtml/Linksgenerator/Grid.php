<?php
class Web4pro_Linksgenerator_Block_Adminhtml_Linksgenerator_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function _construct(array $attributes = array())
    {
        parent::_construct($attributes);
        $this->setId('linksgeneratorGrid');
        $this->setDefaultSort('linksgenerator_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('linksgenerator/link')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {

        $helper = Mage::helper('linksgenerator');

        $this->addColumn('link_id', array(
            'header' => $helper->__('Link ID'),
            'index' => 'link_id'
        ));


        $this->addColumn('link_text', array(
            'header' => $helper->__('Link Text'),
            'index' => 'link_text',
            'type' => 'text',
        ));

        $this->addColumn('link_state', array(
            'header' => $helper->__('Link Status'),
            'index' => 'link_state',
            'type' => 'options',
            'options'   => Mage::getSingleton('cms/page')->getAvailableStatuses()
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($model)
    {
        return $this->getUrl('*/*/edit', array(
            'id' => $model->getId(),
        ));
    }



}