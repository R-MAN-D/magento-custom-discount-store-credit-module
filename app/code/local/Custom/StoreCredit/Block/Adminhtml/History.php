<?php
/* @author  Armande Bayanes
 * */

class Custom_StoreCredit_Block_Adminhtml_History extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {

        $this->_controller = 'adminhtml_history';
        $this->_blockGroup = 'storecredit';
        $this->_headerText = Mage::helper('storecredit')->__('Store Credits History');

        parent::__construct();
        
        $this->_removeButton('add'); /* Removes add ("Add Item") button since it's not necessary for this module. */
    }    
}