<?php
/* @author  Armande Bayanes
 * */

class Custom_StoreCredit_Model_Deduction extends Mage_Sales_Model_Quote_Address_Total_Abstract {
    
    public function __construct() {

        $this->setCode('StoreCredit');
    }
    
    public function _getResource() {
        
        $resource = Mage::getSingleton('core/resource');        
        return array($resource->getConnection('core_read'), $resource->getConnection('core_write'));
    }
    
    public function _getSession() {

        return Mage::getSingleton('customer/session');
    }
    
    public function collect(Mage_Sales_Model_Quote_Address $address) {
        
        if($address->getData('address_type') == 'billing')
            return $this;

        // Fetch if "Store Credit" was applied by checking if "store_credit" session is not empty.
        $raw = (float) $this->_getSession()->getData('store_credit');
        if(! $raw) return $this;
        
        /* START: Perform a re-check / validation in the credit's session.
         * This will ensure that session has 
         * */
        if(($customer_id = $this->_getSession()->getId()) > 0) {
            
            list($read, $write) = $this->_getResource();
            
            $sql = "SELECT credit_remaining FROM storecredit WHERE customer_id='" . $customer_id . "'";
            $credit_remaining = (float) $read->fetchOne($sql);
            if($credit_remaining >= $raw) {
                
                /* $credit_remaining must be always greater or equal than $raw (credit to use). */                
            } else return $this;
            
        } /* END: Perform a re-check / validation in the credit's session. */
        
        $discount = (float) Mage::app()->getStore()->convertPrice($raw);

        $address->setStoreCreditDiscount($discount);

        $address->setBaseGrandTotal($address->getBaseGrandTotal() - $raw);
        $address->setGrandTotal($address->getGrandTotal() - $discount);
        
        return $this;
    }
 
    public function fetch(Mage_Sales_Model_Quote_Address $address) {

        if ($address->getData('address_type') == 'billing')
            return $this;

        $amount = (float) $address->getStoreCreditDiscount();

        if($amount > 0) {
            
            $address->addTotal(array(
                'code' => $this->getCode(),
                'title' => 'Store Credit Discount (' . $amount . ' credit used)',
                'value' => '-' . $amount
            ));
        }
            
        return $address;
    }
}