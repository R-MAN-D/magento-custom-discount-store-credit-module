<?php
/* @author  Armande Bayanes
 * */
class Custom_StoreCredit_Block_Adminhtml_Storecredit_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {

        parent::__construct();

        $this->setId('storecreditGrid');
        $this->setDefaultSort('storecredit_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {

        $collection = Mage::getResourceModel('customer/customer_collection')->addNameToSelect();
        $collection->getSelect()
            ->joinLeft(
                array('sc' => 'storecredit'),
                'e.entity_id=sc.customer_id'
            );

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('storecredit')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'entity_id',
        ));

        $this->addColumn('name', array(
            'header'    => Mage::helper('storecredit')->__('Name'),
            'align'     =>'left',
            'width'     => '170px',
            'index'     => 'name',
        ));

        $this->addColumn('credit_earned', array(
            'header'    => Mage::helper('storecredit')->__('Total credits earned'),
            'align'     =>'left',
            'index'     => 'credit_earned',
        ));

        $this->addColumn('credit_spent', array(
            'header'    => Mage::helper('storecredit')->__('Total credits spent'),
            'align'     =>'left',
            'index'     => 'credit_spent',
        ));

        $this->addColumn('credit_remaining', array(
            'header'    => Mage::helper('storecredit')->__('Total credits remaining'),
            'align'     =>'left',
            'index'     => 'credit_remaining',
        ));

        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('storecredit')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('storecredit')->__('Give credit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
        
        $this->addExportType('*/*/exportCsv', Mage::helper('storecredit')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('storecredit')->__('XML'));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction() {

        $this->setMassactionIdField('storecredit_id');
        $this->getMassactionBlock()->setFormFieldName('storecredit');

        return $this;
    }

    public function getRowUrl($row) { return $this->getUrl('*/*/edit', array('id' => $row->getId())); }

}