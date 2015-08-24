<?php
/* @author  Armande Bayanes
 * */
class Custom_StoreCredit_Block_Adminhtml_Storecredit_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {

        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'storecredit';
        $this->_controller = 'adminhtml_storecredit';
        
        $this->_updateButton('save', 'label', Mage::helper('storecredit')->__('Save'));
        //$this->_updateButton('delete', 'label', Mage::helper('storecredits')->__('Delete'));
	
        $this->_removeButton('delete');
        
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save and Continue giving Credit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('storecredit_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'storecredit_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'storecredit_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText() {

        //if( Mage::registry('storecredits_data') && Mage::registry('storecredits_data')->getId() ) {
            return Mage::helper('storecredit')->__("Enter credit for '%s'", $this->htmlEscape(Mage::registry('storecredit_data')->getName()));
        //} else {
        //    return Mage::helper('storecredits')->__('Enter credit');
        //}
    }
}