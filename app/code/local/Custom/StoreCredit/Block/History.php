<?php
/**
 * @author      Armande Bayanes
 * @category    Front-end
 *
 * Blocks are used to pass data to the View.
 */

class Custom_StoreCredit_Block_History extends Mage_Core_Block_Template {
    
    private function _getSession() {
        return Mage::getSingleton('customer/session');
    }
    
    public function _prepareLayout() {
        return parent::_prepareLayout();
    }
    
    /*public function getStoreCredits() {
        
        if(! $this->_getSession()->isLoggedIn())
            return;
        
        $collection = Mage::getModel('storecredit/storecredit')->getCollection();
        $collection->getSelect()->where('main_table.customer_id=' . $this->_getSession()->getId());
        
        $data = array();
        if($collection->getSize()) {
            $data = $collection->getData();
            $data = $data[0];
        }
        
        return $data;
    }*/
}