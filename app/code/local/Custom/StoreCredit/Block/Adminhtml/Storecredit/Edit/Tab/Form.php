<?php
/* @author  Armande Bayanes
 * */
class Custom_StoreCredit_Block_Adminhtml_Storecredit_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {

        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('storecredit_form', array('legend'=>Mage::helper('storecredit')->__('Enter credit')));

        $fieldset->addField('credit_value', 'text', array(
            'label'       => Mage::helper('storecredit')->__('Enter credit'),
            'required'    => true,
            'class'     => 'required-entry',
            'style'       => 'width: 100px',
            'name'        => 'credit_value',
        ));

        $fieldset->addField('credit_description', 'textarea', array(
            'label'       => Mage::helper('storecredit')->__('Description'),
            'style'       => 'resize: none',
            'required'    => false,
            'name'        => 'credit_description',
        ));


        if(Mage::getSingleton('adminhtml/session')->getStorecreditData()) {

            $form->setValues(Mage::getSingleton('adminhtml/session')->getStorecreditData());
            Mage::getSingleton('adminhtml/session')->setStorecreditData(null);

        } elseif(Mage::registry('storecredits_data')) {

            $form->setValues(Mage::registry('storecredit_data')->getData());

        }

        return parent::_prepareForm();
    }
}