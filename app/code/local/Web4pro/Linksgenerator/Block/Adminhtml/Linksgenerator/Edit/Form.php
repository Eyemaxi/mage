<?php

class Web4pro_Linksgenerator_Block_Adminhtml_Linksgenerator_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
        //$model = Mage::registry('current_linksgenerator');

        //$form = new Varien_Data_Form(array('id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post'));
        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/save', array(
                'id' => $this->getRequest()->getParam('id')
            )),
            'method' => 'post',
            'enctype' => 'multipart/form-data'
        ));



        $form->setUseContainer(true);
        $this->setForm($form);

        /*$fieldset = $form->addFieldset('linksgenerator_form', array('legend'=>Mage::helper('linksgenerator')->__('Add New Attribute')));
        $fieldset->addField('bind', 'checkbox', array(
            'checked'   => $model->getBannerGral()==1 ? 'true' : 'false',
        ));*/

        return parent::_prepareForm();
    }

}