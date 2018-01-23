<?php
class Web4pro_Mycarrier_Model_Carrier extends Mage_Shipping_Model_Carrier_Abstract implements Mage_Shipping_Model_Carrier_Interface
{
    protected $_code = 'web4pro_mycarrier';

    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        /*if (!$this->getConfigFlag('active')) {
            return false;
        }*/

        /** @var $result Mage_Shipping_Model_Rate_Result */
        $result = Mage::getModel('shipping/rate_result');

        $shippingPrice = $this->_getDeliveryPriceByWeight($request->getPackageWeight()); // dummy price

            //$warehouseId = 1; // dummy warehouse ID
            $warehouseName = 'Склад №1'; // dummy warehouse name

            /** @var $method Mage_Shipping_Model_Rate_Result_Method */
            $method = Mage::getModel('shipping/rate_result_method');
            $method->setCarrier($this->_code)
                ->setCarrierTitle($this->getConfigData('name'))
                ->setMethod('default')
                ->setMethodTitle($warehouseName)
                ->setPrice($shippingPrice)
                ->setCost($shippingPrice);

            $result->append($method);


        return $result;
    }

    public function isTrackingAvailable()
    {
        return true;
    }

    public function getAllowedMethods(){
        return true;
    }

    protected function _getWeightPriceMap()
    {
        $weightPriceMap = $this->getConfigData('weight_price');
        if (empty($weightPriceMap)) {
            return array();
        }

        return unserialize($weightPriceMap);
    }

    /**
     * @param $packageWeight
     *
     * @return float
     */
    protected function _getDeliveryPriceByWeight($packageWeight)
    {
        $weightPriceMap = $this->_getWeightPriceMap();
        $resultingPrice = 0.00;
        if (empty($weightPriceMap)) {
            return $resultingPrice;
        }

        arsort($weightPriceMap);

        $minimumDefaultPrice = $this->getConfigData('minimum_default_price');

        /*$minimumWeight = 1000000000;
        foreach ($weightPriceMap as $weightPrice) {
            if ($packageWeight <= $weightPrice['weight'] && $weightPrice['weight'] <= $minimumWeight) {
                $minimumWeight = $weightPrice['weight'];
                $resultingPrice = $weightPrice['price'];
            }
        }*/
        if(!empty($minimumDefaultPrice)) {
            $resultingPrice = $minimumDefaultPrice;
        }
        else {
            $resultingPrice = 0;
        }

        foreach ($weightPriceMap as $weightPrice) {
                if ($packageWeight >= $weightPrice['weight']) {
                    $resultingPrice = $weightPrice['price'];
                    break;
                }
            }



        return $resultingPrice;
    }




}