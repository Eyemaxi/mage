<?php
/**
 * Created by PhpStorm.
 * User: dev01
 * Date: 25.12.17
 * Time: 13:35
 */
class Web4pro_Fastorder_Block_Cart_Fastorder extends Mage_Core_Block_Template
{
    /**
     * @return mixed
     */
    public function getIsFastorder()
    {
        $baseSubtotal = Mage::helper('checkout')->getQuote()->getShippingAddress()->getData('base_subtotal');
        $minimumSubtotal = Mage::getStoreConfig('sales/fastorder/minimum_subtotal');
        if($baseSubtotal >= $minimumSubtotal)
        {
            return true;
        }
        else
            {
                return false;
            }
    }

    public function getFastOrderUrl()
    {
        return Mage::getUrl('fastorder/index/fastorder');
    }
}