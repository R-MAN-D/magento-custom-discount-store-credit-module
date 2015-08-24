<?php
/* @author  Armande Bayanes
 * */
class Custom_StoreCredit_Adminhtml_StorecreditController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction() {

        $this->loadLayout()
            ->_setActiveMenu('storecredit/items')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));

        return $this;
    }

    public function indexAction() {
        $this->_initAction()
            ->renderLayout();
    }

    public function editAction() {

        $id = $this->getRequest()->getParam('id');
        $customer = Mage::getModel("customer/customer")->load($id);

        if($customer->getId()) {

            $storecredit = Mage::getModel('storecredit/storecredit')->getCollection();
            $storecredit->getSelect()->where("customer_id = '" . $customer->getId() . "'");

            $sc = $storecredit->toArray();
            $sc = $sc['items'][0];

            if(! empty($sc)) $customer->addData($sc);

            Mage::register('storecredit_data', $customer);

            $this->loadLayout();
            $this->_setActiveMenu('storecredit/items');

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('storecredit/adminhtml_storecredit_edit'))
                ->_addLeft($this->getLayout()->createBlock('storecredit/adminhtml_storecredit_edit_tabs'));

            $this->renderLayout();
        }
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function saveAction() {

        $customer_id = (int) $this->getRequest()->getParam('id');

        if(($data = $this->getRequest()->getPost()) && $customer_id > 0) {

            /* 0 = Admin store
            * 1 = Default store
            * */
            $store_id = Mage::app()->getStore()->getId();

            $resource = Mage::getSingleton('core/resource');
            $write = $resource->getConnection('core_write');
            $read = $resource->getConnection('core_read');

            $data['credit_value'] = (float) $data['credit_value'];

            $sql = "SELECT storecredit_id FROM storecredit WHERE customer_id='" . $customer_id . "'";
            if(! $read->fetchOne($sql)) $sql = "INSERT INTO storecredit SET store_id='" . $store_id . "',customer_id='" . $customer_id . "',credit_earned='" . $data['credit_value'] . "',credit_remaining='" . $data['credit_value'] . "',created_time=NOW()";
            else $sql = "UPDATE storecredit SET credit_earned=credit_earned+" . $data['credit_value'] . ",credit_remaining=credit_remaining+" . $data['credit_value'] . ",update_time=NOW() WHERE customer_id='" . $customer_id . "'";

            $write->query($sql);

            $sql =  "INSERT INTO storecredit_history(customer_id,credit,description,created) VALUES(:customer_id,:credit,:description,NOW())";
            $binds = array(
                'customer_id'   => $customer_id,
                'credit'        => $data['credit_value'],
                'description'   => $data['credit_description']
            ); $write->query($sql, $binds);

            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('storecredit')->__('Item was successfully saved'));
            Mage::getSingleton('adminhtml/session')->setFormData(false);

            if($this->getRequest()->getParam('back')) {
                $this->_redirect('*/*/edit', array('id' => $customer_id));
                return;
            }

            $this->_redirect('*/*/');
            return;
        }

        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('storecredit')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
    }

    public function massDeleteAction() {

        $storecreditsIds = $this->getRequest()->getParam('storecredit');

        if(! is_array($storecreditsIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($storecreditsIds as $storecreditsId) {
                    $storecredits = Mage::getModel('storecredit/storecredit')->load($storecreditsId);
                    $storecredits->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($storecreditsIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction() {

        $storecreditsIds = $this->getRequest()->getParam('storecredit');

        if(! is_array($storecreditsIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($storecreditsIds as $storecreditsId) {
                    $storecredits = Mage::getSingleton('storecredit/storecredit')
                        ->load($storecreditsId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($storecreditsIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function exportCsvAction() {

        $fileName   = 'storecredits.csv';
        $content    = $this->getLayout()->createBlock('storecredit/adminhtml_storecredit_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction() {

        $fileName   = 'storecredit.xml';
        $content    = $this->getLayout()->createBlock('storecredit/adminhtml_storecredit_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream') {

        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
}