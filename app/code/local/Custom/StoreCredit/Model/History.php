<?php
/* @author  Armande Bayanes
 * */

class Custom_StoreCredit_Model_History extends Mage_Core_Model_Abstract {

    public function _construct() {
        
        parent::_construct();
        $this->_init('storecredit/history');
    }
}