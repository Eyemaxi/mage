<?php
/**
 * Created by PhpStorm.
 * User: dev01
 * Date: 22.12.17
 * Time: 17:05
 */
class Web4pro_Fastorder_IndexController extends Mage_Core_Controller_Front_Action
{
    protected $validFullName = "/([A-Z][a-z]+[\-\s]?){1,}/";
    protected $validNumberPhone = "/^(\+[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{3,20}$/";
    protected $validEmail = "/[a-zA-Z1-9\-\._]+@[a-z1-9]+(.[a-z1-9]+){1,}/";

    public function indexAction()
    {
        $this->loadLayout();
        $layoutHandles = $this->getLayout()->getUpdate()->getHandles();
        echo '<pre>' . print_r($layoutHandles, true) . '</pre>';
    }

    public function fastOrderAction()
    {
        if(isset($_POST['fastorder_full_name']))
        {
            $arrErrors = array();
            $fastOrderCurrent = array();

            if(!preg_match($this->validFullName, $_POST['fastorder_full_name']))
            {
                array_push($arrErrors,"The field 'Full name' is incorrect. The first letter of the name must be in uppercase. For example: Ivanov Ivan Ivanovich");
            }
            else
            {
                $fastOrderCurrent['full_name'] = $_POST['fastorder_full_name'];
            }

            if(empty($_POST['fastorder_email']) && empty($_POST['fastorder_phone_number']))
            {
                array_push($arrErrors,"At least one field ('Phone number' or 'E-mail') must be field in.");
            }
            else
            {
                if(!empty($_POST['fastorder_phone_number']) && !preg_match($this->validNumberPhone, $_POST['fastorder_phone_number']))
                {
                    array_push($arrErrors,"The field 'Number phone' is incorrect. For example: +7(798)-765-43-21");
                }
                elseif(!empty($_POST['fastorder_phone_number']))
                {
                    $fastOrderCurrent['phone_number'] = $_POST['fastorder_phone_number'];
                }

                if(!empty($_POST['fastorder_email']) && !preg_match($this->validEmail, $_POST['fastorder_email']))
                {
                    array_push($arrErrors,"The field 'E-mail' is incorrect. For example: example@gmail.com");
                }
                elseif(!empty($_POST['fastorder_email']))
                {
                    $fastOrderCurrent['email'] = $_POST['fastorder_email'];
                }
            }

            if(!empty($arrErrors))
            {
                $_SESSION['fastorder_errors'] = $arrErrors;
                if(isset($_SESSION['fastorder_form_url']))
                {
                    $this->_redirect($_SESSION['fastorder_form_url']);
                }

            }
            else
            {
                unset($_SESSION['fastorder_errors']);
                //$this->_redirect('fastorder/fastorder/index');
                $fastorder = Mage::getModel('fastorder/fastorder');
                $resultCreateOrder = $fastorder->createOrder($fastOrderCurrent);
                $session = Mage::getSingleton('checkout/session');

                if($resultCreateOrder['result'])
                {
                    $lastOrderId = $session->getLastOrderId();
                    $customerName = $resultCreateOrder['quote']['customer_firstname'];

                    if(!empty($resultCreateOrder['quote']['customer_middlename'])) {
                        $customerName.= ' ' . $resultCreateOrder['quote']['customer_middlename'];
                    }

                    $customerName.= ' ' . $resultCreateOrder['quote']['customer_lastname'];

                    $successMessage = $customerName . ', you have successfully created an order. Your order # is: ' . $lastOrderId . '.';

                    if(!empty($resultCreateOrder['quote']['customer_email'])) {
                        $successMessage.= ' Your e-mail is: ' . $resultCreateOrder['quote']['customer_email'] . '.';
                    }

                    $session->addSuccess(Mage::helper('checkout')->__($successMessage));
                    $this->_redirect('checkout/onepage/success');
                    //$this->_redirect('*/*');
                    return $this;
                }
                else
                    {
                        $session->addError(Mage::helper('checkout')->__($resultCreateOrder['message']));
                        $this->_redirect('checkout/cart');
                    }
            }

        }
        elseif(isset($_POST['fastorder_checkout']))
        {
            $arrErrors = array("The field 'Full name' is empty.");
            $_SESSION['fastorder_errors'] = $arrErrors;
            if(isset($_SESSION['fastorder_form_url']))
            {
                $this->_redirect($_SESSION['fastorder_form_url']);
            }
        }
        return $this;
    }
}