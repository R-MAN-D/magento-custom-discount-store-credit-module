<?php
/**
 * @category    Custom
 * @package     Custom_StoreCredit
 * @author      Armande Bayanes
 * @license
 */

class Custom_StoreCredit_DiscountController extends Mage_Core_Controller_Front_Action {

    public function _getSession() {

        return Mage::getSingleton('customer/session');
    }

    public function indexAction() {}

    // Apply discount if Customer wants it by triggering the Form from the Cart page.
    public function applyAction() {

        $redirect = Mage::getBaseUrl() . 'checkout/cart';

        if(! $this->_getSession()->isLoggedIn() || ! $this->getRequest()->isPost()) {

            Mage::app()->getResponse()->setRedirect($redirect);
            return; // Important, else won't redirect.
        }

        $credit_remaining = Mage::getModel('storecredit/storecredit')->getCustomerCreditRemaining();

        $credit_to_use = 0;
        if($credit_remaining > 0) {

            $totals = Mage::getSingleton('checkout/session')->getQuote()->getTotals();
            $subtotal = $totals['subtotal']->getValue(); /* Initial item total. */
            $grandtotal = $totals['grand_total']->getValue(); /* Discounted item total. */

            if($credit_remaining > 0 && $subtotal > 0 && $grandtotal > 0) {
                /* $credit_remaining == 0, No credit to apply.
                 * $subtotal == 0, Cart is empty. Must not proceed at all.
                 * $grandtotal == 0, Maybe empty or already discounted.
                 * */

                if($credit_remaining > $grandtotal) $credit_to_use = $grandtotal;
                else $credit_to_use = $credit_remaining;
            }
        }

        if($credit_to_use) {

            Mage::getSingleton('core/session')->addSuccess($this->__('Great! You have successfully applied %s Store Credit.', $credit_to_use));
            /* Store in Customer's session to indicate that Customer is using Store Credit. */
            Mage::getModel('customer/session')->setData('store_credit', $credit_to_use);
        }

        Mage::app()->getResponse()->setRedirect($redirect);
    }

    // Remove discount if Customer wants it by triggering the Form from the Cart page.
    public function removeAction() {

        if($this->_getSession()->isLoggedIn() && $this->getRequest()->isPost()) {

            Mage::getSingleton('core/session')->addNotice($this->__('Great! You have successfully removed Store Credit.'));
            Mage::getModel('customer/session')->unsetData('store_credit');
        }

        Mage::app()->getResponse()->setRedirect(Mage::getBaseUrl() . 'checkout/cart');
    }
}