<?php
class Web4pro_Linksgenerator_Block_Adminhtml_Linksgenerator_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

    protected function _construct()
    {
        $this->_blockGroup = 'linksgenerator';
        $this->_controller = 'adminhtml_linksgenerator';
    }

    public function getHeaderText()
    {
        $helper = Mage::helper('linksgenerator');
        $model = Mage::registry('current_linksgenerator');

        if ($model->getId()) {
            return $helper->__("Edit Link item '%s'", $this->escapeHtml($model->getTitle()));
        } else {
            return $helper->__("Add Link item");
        }
    }

}