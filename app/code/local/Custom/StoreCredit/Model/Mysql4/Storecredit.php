<?php
/* @author  Armande Bayanes
 * */

class Custom_StoreCredit_Model_Mysql4_Storecredit extends Mage_Core_Model_Mysql4_Abstract {

    public function _construct() {

        /* The `storecredit_id` refers to the primary key field in the database table `storecredit`. */
        $this->_init('storecredit/storecredit', 'storecredit_id');
    }
}