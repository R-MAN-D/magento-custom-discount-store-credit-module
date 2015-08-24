<?php
/* @author  Armande Bayanes
 * */

class Custom_StoreCredit_Block_Adminhtml_History_Grid extends Mage_Adminhtml_Block_Widget_Grid {
    
    public function __construct() {
        parent::__construct();

        $this->setId('historyGrid');
        $this->setDefaultSort('id'); /* `id` in / of `storecredit_history` table. */
        $this->setDefaultDir('DESC');
        
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        
        $collection = Mage::getModel('storecredit/history')->getCollection();

        /* Note: Magento 1.9. 5 for firstname while 7 for lastname.
         * Must edit this to match with the proper attribute IDs for the firstname & lastname.
         *
         * Prior versions, 1 for firstname while 2 for lastname.
         */
        $methodWhereFirstname = "ce1.attribute_id = '5'";
        $methodWhereLastname = "ce2.attribute_id = '7'";

        /* Note: http://www.magentocommerce.com/boards/viewthread/197044/ */
        $collection
            ->getSelect()
            ->join( array('ce1' => 'customer_entity_varchar'), 'ce1.entity_id=main_table.customer_id', array('firstname' => 'value'))
                ->where($methodWhereFirstname)
            ->join( array('ce2' => 'customer_entity_varchar'), 'ce2.entity_id=main_table.customer_id', array('lastname' => 'value'))
                ->where($methodWhereLastname)
            ->columns(new Zend_Db_Expr("CONCAT(`ce1`.`value`, ' ',`ce2`.`value`) AS fullname"));

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {

        $this->addColumn('id', array(
            'header'    => Mage::helper('storecredit')->__('ID'),
            'align'     => 'right',
            'width'     => '50px',
            'index'     => 'id',
        ));

        $this->addColumn('fullname', array(
            'header'    => Mage::helper('storecredit')->__('Name'),
            'align'     => 'right',
            'width'     => '50px',
            'index'     => 'fullname',
            'filter_condition_callback' => array($this, 'callbackFilterByFullname')
        ));

        $this->addColumn('credit', array(
            'header'    => Mage::helper('storecredit')->__('Credit Amount / Value (AVAILABLE)'),
            'align'     => 'left',
            'width'     => '170px',
            'index'     => 'credit',
        ));
        
        $this->addColumn('spent', array(
            'header'    => Mage::helper('storecredit')->__('Credit Amount / Value (SPENT)'),
            'align'     => 'left',
            'width'     => '170px',
            'index'     => 'spent',
        ));
        
        $this->addColumn('description', array(
            'header'    => Mage::helper('storecredit')->__('Description'),
            'align'     => 'left',
            'width'     => '170px',
            'index'     => 'description',
        ));
        
        $this->addExportType('*/*/exportCsv', Mage::helper('storecredit')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('storecredit')->__('XML'));

        return parent::_prepareColumns();
    }
    
    public function callbackFilterByFullname($collection, $column) {
        
        if(! $value = trim($column->getFilter()->getValue())) { return; }
        
        $collection
            ->getSelect()
            ->where("CONCAT(`ce1`.`value`, ' ',`ce2`.`value`) LIKE '%" . $value . "%'");        
    }
}