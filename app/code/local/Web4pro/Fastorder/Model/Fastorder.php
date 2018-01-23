<?php

class Web4pro_Fastorder_Model_Fastorder extends Mage_Sales_Model_Service_Quote
{

    protected $_quote;

    /**
     * Get customer data for shipping address and billing address
     * @param Mage_Customer_Model_Customer $customer
     * @param array $fastOrderCurrent
     * @return array
     */
    protected function getDataCustomer(Mage_Customer_Model_Customer $customer, array $fastOrderCurrent)
    {
        $customerId = $_SESSION['customer_base']['id'];
        $customer->load($customerId);

        $billing = $customer->getDefaultBillingAddress();

        $dataCustomer = [];

        $dataCustomer['customer_id'] = $customerId;
        $dataCustomer['store_id'] = $customer->getStoreId();
        $dataCustomer['customer_address_id'] = $customer->getCustomerAddressId();
        $dataCustomer['currency'] = 'USD';
        $dataCustomer['group_id'] = $customer->getGroupId();

        $dataCustomer['firstname'] = $customer->getFirstname();
        $dataCustomer['lastname'] = $customer->getLastname();
        $dataCustomer['middlename'] = ''/*$customer->getMiddlename()*/;
        $dataCustomer['email'] = $customer->getEmail();

        if($billing)
        {
            $dataCustomer['street'] = array($billing->getData('street'),''); // if Street is NULL, returned street('0' => null, '1' => "")
            $dataCustomer['city'] = $billing->getData('city');
            $dataCustomer['country_id'] = $billing->getData('country_id');
            $dataCustomer['region'] = $billing->getData('region');
            $dataCustomer['region_id'] = $billing->getData('region_id');
            $dataCustomer['postcode'] = $billing->getData('postcode');
            $dataCustomer['telephone'] = $billing->getData('telephone');
            $dataCustomer['fax'] = $billing->getData('fax');
        }


        /** @var $session Mage_Customer_Model_Session  */
        $session = Mage::getSingleton('customer/session');
        if ($session->isLoggedIn())
        {
            $dataCustomer['paymentMethod'] = 'bonuspayment';
        }
        else
        {
            $dataCustomer['paymentMethod'] = 'checkmo';
        }

        $dataCustomer['shippingMethod'] = 'web4pro_mycarrier_default';


        if(empty($dataCustomer['firstname']))
        {
            $dataCustomer['firstname'] = 'Name';
        }

        if(empty($dataCustomer['lastname']))
        {
            $dataCustomer['lastname'] = 'Last_name';
        }

        if(empty($dataCustomer['middlename']))
        {
            $dataCustomer['middlename'] = '';
        }

        if(empty($dataCustomer['telephone']))
        {
            $dataCustomer['telephone'] = '+0(000)-000-00-00';
        }

        if (isset($fastOrderCurrent['full_name'])) {
            $full_name = explode(' ', $fastOrderCurrent['full_name']);
            foreach ($full_name as $key => $namePart) {
                if ($key == 0) {
                    $dataCustomer['lastname'] = $full_name[$key];
                }

                if ($key == 1) {
                    $dataCustomer['firstname'] = $full_name[$key];
                }

                if ($key == 2) {
                    $dataCustomer['middlename'] = $full_name[$key];
                }
            }
        }

        if (isset($fastOrderCurrent['email'])) {
            $dataCustomer['email'] = $fastOrderCurrent['email'];
        }

        if (isset($fastOrderCurrent['phone_number'])) {
            $dataCustomer['telephone'] = $fastOrderCurrent['phone_number'];
        }

        return $dataCustomer;
    }

    public function createOrder(array $fastOrderCurrent)
    {
        $customer = Mage::getModel('customer/customer');
        $dataCustomer = $this->getDataCustomer($customer, $fastOrderCurrent);

        $storeId = $dataCustomer['store_id'];
        $reservedOrderId = Mage::getSingleton('eav/config')->getEntityType('order')->fetchNewIncrementId($storeId);


        $order = Mage::getModel('sales/order')
            ->setIncrementId($reservedOrderId)
            ->setStoreId($storeId)
            ->setQuoteId(0)
            ->setGlobal_currency_code($dataCustomer['currency'])
            ->setBase_currency_code($dataCustomer['currency'])
            ->setStore_currency_code($dataCustomer['currency'])
            ->setOrder_currency_code($dataCustomer['currency']);


        /* add customer start here */
        $customer->setWebsiteId(Mage::app()->getWebsite()->getId());

        $cart = Mage::getSingleton('checkout/cart')->getQuote();

        $products = array();

        foreach ($cart->getAllItems() as $item) {
            $productId = $item->getProduct()->getId();
            $productQty = $item->getData('qty');
            $products[$productId] = $productQty;
        }

// Start New Sales Order Quote
        $quote = Mage::getModel('sales/quote');
        $quote->setStoreId($dataCustomer['store_id']);
        //$quote->setCurrency($dataCustomer['currency']);
        $quote->setCustomerId($dataCustomer['customer_id']);
        $quote->setCustomerFirstname($dataCustomer['firstname']);
        $quote->setCustomerMiddlename($dataCustomer['middlename']);
        $quote->setCustomerLastname($dataCustomer['lastname']);
        $quote->setCustomerEmail($dataCustomer['email']);
        $quote->setCustomerGroupId($dataCustomer['group_id']);

// Set Sales Order Quote Currency
        $quote->setCurrency($order->AdjustmentAmount->currencyID);

// Assign Customer To Sales Order Quote
        //$quote->assignCustomer($customer);

// Configure Notification
        $quote->setSendCconfirmation(1);
        foreach ($products as $id => $qty) {
            $product = Mage::getModel('catalog/product')->load($id);
            $currentProductQty = $product->getStockItem()-> getQty();
            if($currentProductQty <= $qty)
            {
                return array( 'result' => false, 'message' => (int)$currentProductQty - 1 . ' items remaining in stock.');
            }

            $quote->addProduct($product, new Varien_Object(array('qty' => $qty)));
        }

        // Set Sales Order Billing Address
        $billingAddress = $quote->getBillingAddress()->addData(array(
            'customer_address_id' => $dataCustomer['customer_address_id'],
            'prefix' => '',
            'firstname' => $dataCustomer['firstname'],
            'middlename' => $dataCustomer['middlename'],
            'lastname' => $dataCustomer['lastname'],
            'suffix' => '',
            'company' => '',
            'street' => $dataCustomer['street'],
            'city' => $dataCustomer['city'],
            'country_id' => $dataCustomer['country_id'],
            'region' => $dataCustomer['region'],
            'region_id' => $dataCustomer['region_id'],
            'postcode' => $dataCustomer['postcode'],
            'telephone' => $dataCustomer['telephone'],
            'fax' => $dataCustomer['fax'],
            'vat_id' => '',
            'save_in_address_book' => 1
        ));
        $order->setBillingAddress($billingAddress);

// Set Sales Order Shipping Address
        $shippingAddress = $quote->getShippingAddress()->addData(array(
            'customer_address_id' => $dataCustomer['customer_address_id'],
            'prefix' => '',
            'firstname' => $dataCustomer['firstname'],
            'middlename' => $dataCustomer['middlename'],
            'lastname' => $dataCustomer['lastname'],
            'suffix' => '',
            'company' => '',
            'street' => $dataCustomer['street'],
            'city' => $dataCustomer['city'],
            'country_id' => $dataCustomer['country_id'],
            'region' => $dataCustomer['region'],
            'region_id' => $dataCustomer['region_id'],
            'postcode' => $dataCustomer['postcode'],
            'telephone' => $dataCustomer['telephone'],
            'fax' => $dataCustomer['fax'],
            'vat_id' => '',
            'save_in_address_book' => 1
        ));

// Collect Rates and Set Shipping & Payment Method

        $shippingAddress
            ->setShippingMethod($dataCustomer['shippingMethod'])
            ->setPaymentMethod($dataCustomer['paymentMethod']);

        $shippingAddress->setCollectShippingRates(true)
            ->collectShippingRates();

// Set Sales Order Payment
        $quote->getPayment()->importData(array('method' => $dataCustomer['paymentMethod']));

// Collect Totals & Save Quote
        $quote->collectTotals()->save();

        try {
            // Create Order From Quote
            //$service = Mage::getModel('sales/service_quote', $quote);
            $this->_quote = $quote;
            $this->submitAll();
            $increment_id = $this->getOrder()->getRealOrderId();

            $cart = Mage::getSingleton('checkout/cart');
            $quoteItems = Mage::getSingleton('checkout/session')
                ->getQuote()
                ->getItemsCollection();

            foreach( $quoteItems as $item ){
                $cart->removeItem( $item->getId() );
            }
            $cart->save();
            $session = Mage::getSingleton('checkout/session');
            $session ->setLastOrderId($increment_id);
        } catch (Exception $ex) {
            echo $ex->getMessage();
        } catch (Mage_Core_Exception $e) {
            echo $e->getMessage();
        }

// Finished

        return array( 'result' => true, 'quote' => $quote->getData());
    }

    /**
     * Overriding method Mage_Sales_Model_Service_Quote::_validate
     * Validate = true
     * @return $this
     */
    protected function _validate()
    {
        if (!$this->getQuote()->isVirtual()) {
            $address = $this->getQuote()->getShippingAddress();
            $addressValidation = true;
            if ($addressValidation !== true) {
                Mage::throwException(
                    Mage::helper('sales')->__('Please check shipping address information. %s', implode(' ', $addressValidation))
                );
            }
            $method= $address->getShippingMethod();
            $rate  = true;
            if (!$this->getQuote()->isVirtual() && (!$method || !$rate)) {
                Mage::throwException(Mage::helper('sales')->__('Please specify a shipping method.'));
            }
        }

        $addressValidation = true;
        if ($addressValidation !== true) {
            Mage::throwException(
                Mage::helper('sales')->__('Please check billing address information. %s', implode(' ', $addressValidation))
            );
        }

        if (!($this->getQuote()->getPayment()->getMethod())) {
            Mage::throwException(Mage::helper('sales')->__('Please select a valid payment method.'));
        }

        return $this;
    }

}