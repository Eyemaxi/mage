<?php
class Web4pro_Mycarrier_Block_Config_Field_Weightprice extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    public function __construct()
    {

        $this->addColumn('weight', array(
            'label' => Mage::helper('web4pro_mycarrier')->__('Weight upper limit'),
            'style' => 'width:120px',
        ));
        $this->addColumn('price', array(
            'label' => Mage::helper('web4pro_mycarrier')->__('Price'),
            'style' => 'width:120px',
        ));
        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('web4pro_mycarrier')->__('Add rate');
        parent::__construct();
    }
}