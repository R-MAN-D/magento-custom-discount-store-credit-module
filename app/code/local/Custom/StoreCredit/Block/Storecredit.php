<?php
/**
 * @author      Armande Bayanes
 * @category    Front-end
 *
 * Blocks are used to pass data to the View.
 */

class Custom_StoreCredit_Block_Storecredit extends Mage_Core_Block_Template {
    
    public function _prepareLayout() {

        return parent::_prepareLayout();
    }

    public function _getSession() {

        return Mage::getSingleton('customer/session');
    }

    public function getCustomerCredit() {

        return Mage::getModel('storecredit/storecredit')->getCustomerCredit();
    }

    public  function getCustomerCreditRemaining() {

        return Mage::getModel('storecredit/storecredit')->getCustomerCreditRemaining();
    }
}