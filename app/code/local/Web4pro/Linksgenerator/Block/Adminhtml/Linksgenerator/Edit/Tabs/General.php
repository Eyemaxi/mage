<?php
class Web4pro_Linksgenerator_Block_Adminhtml_Linksgenerator_Edit_Tabs_General extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {

        $helper = Mage::helper('linksgenerator');
        $model = Mage::registry('current_linksgenerator');


        $form = new Varien_Data_Form();
        $fieldset = $form->addFieldset('general_form', array(
            'legend' => $helper->__('Link Information')
        ));


        $fieldset->addField('link_text', 'text', array(
            'label' => $helper->__('Link Text'),
            'required' => true,
            'name' => 'link_text',
        ));

        $fieldset->addField('link_state', 'select', array(
            'label' => $helper->__('Link Status'),
            'title' => $helper->__('Link Status'),
            'required' => true,
            'name' => 'link_state',
            'options'   => array(
                '1' => $helper->__('Enabled'),
                '0' => $helper->__('Disabled'),
            ),
        ));



        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

}