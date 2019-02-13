<?php
/**
 * @Author       : Apptha team
 * @package      : Apptha_Request_Coupon
 * @copyright    : Copyright (c) 2011 (www.apptha.com)
 * @license      : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @Date         : July 2012
 */
/**
 * Admin Cusomter request block
 */
class Apptha_Reqforcoupon_Block_Adminhtml_Customerreq_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('reqforcouponGrid');
        $this->setDefaultSort('reqforcoupon_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    //Display all the product details in the grid
    protected function _prepareCollection() {
        $collection = Mage::getModel('reqforcoupon/reqforcoupon')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('reqforcoupon_id', array(
            'header' => Mage::helper('reqforcoupon')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'reqforcoupon_id',
        ));//Incremental Id

        $this->addColumn('name', array(
            'header' => Mage::helper('reqforcoupon')->__('Customer Name'),
            'align' => 'left',
            'index' => 'name',
        ));//Customer name 

        $this->addColumn('email', array(
            'header' => Mage::helper('reqforcoupon')->__('Customer Email'),
            'align' => 'left',
            'index' => 'email',
        ));//Customer Email

        $this->addColumn('product_id', array(
            'header' => Mage::helper('reqforcoupon')->__('Product Id'),
            'align' => 'left',
            'index' => 'product_id',
        ));//product id 
        
        $this->addColumn('product_name', array(
            'header' => Mage::helper('reqforcoupon')->__('Product Name'),
            'align' => 'left',
            'index' => 'product_name',
            'width' => '200px',
        ));//product name
        
        $this->addColumn('message', array(
            'header' => Mage::helper('reqforcoupon')->__('Customer Message'),
            'align' => 'left',
            'index' => 'message',
        ));//Customer message


        $this->addColumn('status', array(
            'header' => Mage::helper('reqforcoupon')->__('Status'),
            'align' => 'left',
            'width' => '150px',
            'index' => 'status',
            'type' => 'options',
            'options' => array(
                0 => 'Yet to send',
                1 => 'Email sent',
            ),
        ));//email reply status

        $this->addColumn('action', array(
            'header' => Mage::helper('reqforcoupon')->__('Action'),
            'width' => '150',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => Mage::helper('reqforcoupon')->__('Click to send Email'),
                    'url' => array('base' => '*/*/edit'),
                    'field' => 'id'
                )
            ),
            'filter' => false,
            'sortable' => false,
            'index' => 'stores',
            'is_system' => true,
        ));//send the email

        $this->addExportType('*/*/exportCsv', Mage::helper('reqforcoupon')->__('CSV'));//export data in the csv file
        $this->addExportType('*/*/exportXml', Mage::helper('reqforcoupon')->__('XML'));//export data in the xml file

        return parent::_prepareColumns();
    }
   /**
    * Delete selectd record in grid
    * 
    * @return Apptha_Reqforcoupon_Block_Adminhtml_Customerreq_Grid
    */
    protected function _prepareMassaction() {
        // reqforcoupon_id is unique identifier
        $this->setMassactionIdField('reqforcoupon_id');
        //Form field name
        $this->getMassactionBlock()->setFormFieldName('reqforcoupon');
        //To redirect to same page
        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('reqforcoupon')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('reqforcoupon')->__('Are you sure?')
        ));


        return $this;
    }
    /**
     * Edit link rowurl 
     */
    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}