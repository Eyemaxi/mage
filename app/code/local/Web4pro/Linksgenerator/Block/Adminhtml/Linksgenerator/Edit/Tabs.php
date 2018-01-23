<?php
class Web4pro_Linksgenerator_Block_Adminhtml_Linksgenerator_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        $helper = Mage::helper('linksgenerator');

        parent::__construct();
        $this->setId('linksgenerator_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle($helper->__('Link Information'));
    }

    protected function _prepareLayout()
    {
        $helper = Mage::helper('linksgenerator');

        $this->addTab('general_section', array(
            'label' => $helper->__('Link Information'),
            'title' => $helper->__('Link Information'),
            'content' => $this->getLayout()->createBlock('linksgenerator/adminhtml_linksgenerator_edit_tabs_general')->toHtml(),
        ));
        $this->addTab('custom_section', array(
            'label' => $helper->__('Pages'),
            'title' => $helper->__('Pages'),
            'content' => $this->getLayout()->createBlock('linksgenerator/adminhtml_linksgenerator_edit_tabs_custom')->toHtml(),
        ));
        return parent::_prepareLayout();
    }

}