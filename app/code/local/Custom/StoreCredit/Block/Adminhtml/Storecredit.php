<?php
/* @author  Armande Bayanes
 * */

class Custom_StoreCredit_Block_Adminhtml_Storecredit extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {

        $this->_controller = 'adminhtml_storecredit';
        $this->_blockGroup = 'storecredit';
        $this->_headerText = Mage::helper('storecredit')->__('Store Credits Manager');
        
        parent::__construct();
        
        $this->_removeButton('add'); /* Removes add ("Add Item") button since it's not necessary for this module. */
    }    
}