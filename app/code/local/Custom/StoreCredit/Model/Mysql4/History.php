<?php
/* @author  Armande Bayanes
 * */

class Custom_StoreCredit_Model_Mysql4_History extends Mage_Core_Model_Mysql4_Abstract {

    public function _construct() {

        /* The `id` refers to the primary key field in the database table `history`. */
        $this->_init('storecredit/history', 'id');
    }
}