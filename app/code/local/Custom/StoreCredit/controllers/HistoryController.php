<?php
/**
 * @category    Custom
 * @package     Custom_StoreCredit
 * @author      Armande Bayanes
 * @license
 */

class Custom_StoreCredit_HistoryController extends Mage_Core_Controller_Front_Action {

    public function _getSession() {

        return Mage::getSingleton('customer/session');
    }

    public function indexAction() {

        if(! $this->_getSession()->isLoggedIn()) {

            Mage::app()->getResponse()->setRedirect(Mage::getBaseUrl() . 'customer/account/login');
            return;
        }

        $this->loadLayout();
        $this->renderLayout();
    }
}