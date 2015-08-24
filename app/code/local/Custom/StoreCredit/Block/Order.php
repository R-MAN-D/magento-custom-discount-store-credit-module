<?php
/* @author Armande Bayanes
 *
 * Displays discount in the Customer Account's order information.
 * */
class Custom_StoreCredit_Block_Order extends Mage_Sales_Block_Order_Totals {

    protected function _initTotals() {

        parent::_initTotals();

        $current_order_id = $this->getRequest()->getParam('order_id');
        $discount = Mage::getModel('storecredit/storecredit')->getOrderDiscount($current_order_id);

        if($discount > 0) {

            $this->addTotal(new Varien_Object(array(
                'code' => 'StoreCredit',
                'value' => -$discount,
                'label' => 'Store Credit Discount'
            )), 'discount');
        }

        return $this;
    }
}