<?php
/* @author  Armande Bayanes
 * */

class Custom_StoreCredit_Model_Mysql4_Storecredit_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {

    public function _construct() {

        parent::_construct();
        $this->_init('storecredit/storecredit');
    }
}