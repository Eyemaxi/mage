<?php
class Web4pro_Bonuspayment_Model_Observer
{

    public function createBonusAccount($observer) {
        //$payments = Mage_Payment_Model_Method_Abstract::getConfigData('bonus_account');
        $payments = Mage::getStoreConfig('payment/bonuspayment/bonus_account');
        //$payments = Mage::getModel('payment/bonuspayment')->getConfigData('bonus_account');
        //$bonusAccount = $this->getConfigData('bonus_account');

        $collection = $observer->getData('customer');
        /*$collection = Mage::getModel('customer/customer')->getCollection();
        $customer = Mage::getModel('customer/customer');
        $customer->setBonuspayment($bonusAccount);
        try{
            $customer->save();
        }
        catch (Exception $e){
            Zend_Debug::dump($e->getMessage());
        }*/
        //$collection2 = Mage::getModel('customer/bonuspayment')->getCollection();
        //$collection = Mage::getModel('customer/customer');
        $collection->setBonuspayment($payments)->save();
    }

}