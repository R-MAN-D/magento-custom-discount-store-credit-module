<?php
/* @author  Armande Bayanes
 * */

class Custom_StoreCredit_Model_Storecredit extends Mage_Core_Model_Abstract {

    public function _construct() {
        
        parent::_construct();
        $this->_init('storecredit/storecredit');
    }

    private function _getSession() {

        return Mage::getSingleton('customer/session');
    }

    public function getCustomerCredit() {

        $credit_remaining = array();

        if(! $this->_getSession()->isLoggedIn())
            return $credit_remaining;

        // Currently logged customer ID.
        $customer_id = (int) $this->_getSession()->getId();

        // <resourceModel>storecredit_mysql4</resourceModel> from config.xml.
        // $store_credit = Mage::getModel('storecredit_mysql4/storecredit');

        // Must define Model/Storecredit.php to access getCollection().
        $collection = Mage::getModel('storecredit/storecredit')->getCollection();
        $collection->getSelect()->where('customer_id = ' . $customer_id);

        if($collection->getSize()) {
            $data = $collection->getData();

            $credit_remaining = $data[0];
        }

        return $credit_remaining;
    }

    public function getCustomerCreditRemaining() {

        $credit_remaining = $this->getCustomerCredit();
        return $credit_remaining['credit_remaining'];
    }

    public function getOrderDiscount($order_id) {

        $collection = Mage::getModel('storecredit/history')->getCollection();
        $collection->getSelect()->where("order_id ='" . $order_id . "'");

        $discount = '0.00';
        if($collection->getSize()) {

            $data = $collection->getData();
            $discount = $data[0]['spent'];
        }

        return (float) $discount;
    }
}