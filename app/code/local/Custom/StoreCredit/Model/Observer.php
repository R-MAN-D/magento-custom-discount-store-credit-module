<?php
/* @author  Armande Bayanes
 * */

class Custom_StoreCredit_Model_Observer {

    public function _getSession() {

        return Mage::getSingleton('customer/session');
    }
    
    public function _getResource() {

        $resource = Mage::getSingleton('core/resource');
        
        return array($resource->getConnection('core_read'), $resource->getConnection('core_write'));
    }
    
    /* sales_quote_collect_totals_after */
    public function discountCheck($observer) {
        
        $event = $observer->getEvent();
        $quote = $event->getQuote();
        
        if(! $this->_getSession()->isLoggedIn()) return;
        
        $credit_used = (float) $this->_getSession()->getData('store_credit');
        if(! $credit_used) return;
        
        /* $credit_used > 0 = If has credit used. */
        $grandtotal = (float) $quote->getGrandTotal();
        if($grandtotal < 0) {
            
            /* Don't apply credit when grand total reaches
             * a negative value (caused by using another
             * discount options (coupons, reward points, etc ...)). */
            Mage::getModel('customer/session')->unsetData('store_credit');
            Mage::app()->getResponse()->setRedirect(Mage::getBaseUrl() . 'checkout/cart');
        }
    }
    
    /* sales_order_place_after */
    public function creditUpdate($observer) {
        
        if(! $this->_getSession()->isLoggedIn()) return;
        
        /* START: Ensures that this will be executed only when Store Credit is applied. */
        $credit_used = (float) $this->_getSession()->getData('store_credit');
        if($credit_used <= 0) return;
        /* END: Ensures that this will be executed only when Store Credit is applied. */
        
        if(($customer_id = $this->_getSession()->getId()) > 0) {
            
            list($read, $write) = $this->_getResource();
            
            $sql = "SELECT credit_remaining FROM storecredit WHERE customer_id='" . $this->_getSession()->getId() . "'";
            $credit_remaining = (float) $read->fetchOne($sql);
        
            $sql = "UPDATE storecredit SET credit_remaining='" . ($credit_remaining - $credit_used) . "',credit_spent=credit_spent+" . $credit_used . ",update_time=NOW() WHERE customer_id='" . $customer_id . "'";
            $write->query($sql);
            
            $session= Mage::getSingleton('checkout/session');
            foreach($session->getQuote()->getAllItems() as $item) { $products[] = $item->getName(); }

            $event = $observer->getEvent();

            $sql = "INSERT INTO storecredit_history SET customer_id='" . $customer_id . "',order_id='" . $event->getOrder()->getId() . "',credit='" . ($credit_remaining - $credit_used) . "',spent='" . $credit_used . "',description='Purchased : " . implode(', ', $products) . "',created=NOW()";
            $write->query($sql);
        }
        
        Mage::getModel('customer/session')->setData('store_credit_for_paypal', $credit_used);
        Mage::getModel('customer/session')->unsetData('store_credit');        
    }
    
    /* paypal_prepare_line_items */
    public function prepareItemsForPaypal($observer) {
        
        $credit_used = (float) $this->_getSession()->getData('store_credit_for_paypal');
        if($credit_used <= 0) return;
        
        $cart = $observer->getPaypalCart();
        $cart->_totals['discount'] = ((float) $cart->_totals['discount'] + $credit_used);
        
        Mage::getModel('customer/session')->unsetData('store_credit_for_paypal');
    }
}