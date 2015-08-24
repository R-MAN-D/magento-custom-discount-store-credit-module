<?php
/* @author  Armande Bayanes
 * */
class Custom_StoreCredit_Block_Adminhtml_Storecredit_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {

        parent::__construct();
        $this->setId('storecredit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('storecredit')->__('Form'));
    }

    protected function _beforeToHtml() {

        $this->addTab('form_section', array(
            'label'     => Mage::helper('storecredit')->__('Data'),
            'title'     => Mage::helper('storecredit')->__('Data'),
            'content'   => $this->getLayout()->createBlock('storecredit/adminhtml_storecredit_edit_tab_form')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}