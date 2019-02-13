<?php

/**
 * @Author       : Apptha team
 * @package      : Apptha_Request_Coupon
 * @copyright    : Copyright (c) 2011 (www.apptha.com)
 * @license      : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @Date         : July 2012
 */
class Apptha_Reqforcoupon_Block_Adminhtml_Reqforcoupon_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    /**
     * Display the product in the descending manner in the grid
     */
    public function __construct() {
        parent::__construct();
        $this->setId('productGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * Get the store Id
     *
     * @return int 
     */
    protected function _getStore() {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    /**
     * Collection of all the product details
     * 
     * @return array $this 
     */
    protected function _prepareCollection() {
        $store = $this->_getStore();
        $collection = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToSelect('sku')
                ->addAttributeToSelect('name')
                ->addAttributeToSelect('attribute_set_id')
                ->addAttributeToSelect('type_id');

        if (Mage::helper('catalog')->isModuleEnabled('Mage_CatalogInventory')) {
            $collection->joinField('qty', 'cataloginventory/stock_item', 'qty', 'product_id=entity_id', '{{table}}.stock_id=1', 'left');
        }
        if ($store->getId()) {
            $adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
            $collection->addStoreFilter($store);
            $collection->joinAttribute('name', 'catalog_product/name', 'entity_id', null, 'inner', $adminStore);
            $collection->joinAttribute('custom_name', 'catalog_product/name', 'entity_id', null, 'inner', $store->getId());
            $collection->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner', $store->getId());
            $collection->joinAttribute('visibility', 'catalog_product/visibility', 'entity_id', null, 'inner', $store->getId());
            $collection->joinAttribute('price', 'catalog_product/price', 'entity_id', null, 'left', $store->getId());
            $collection->joinAttribute('reqcoupon_status', 'catalog_product/reqcoupon_status', 'entity_id', null, 'left');
        } else {
            $collection->addAttributeToSelect('price');
            $collection->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner');
            $collection->joinAttribute('visibility', 'catalog_product/visibility', 'entity_id', null, 'inner');
            $collection->joinAttribute('reqcoupon_status', 'catalog_product/reqcoupon_status', 'entity_id', null, 'left');
        }

        $this->setCollection($collection);

        parent::_prepareCollection();
        $this->getCollection()->addWebsiteNamesToResult();
        return $this;
    }

    protected function _addColumnFilterToCollection($column) {
        if ($this->getCollection()) {
            if ($column->getId() == 'websites') {
                $this->getCollection()->joinField('websites', 'catalog/product_website', 'website_id', 'product_id=entity_id', null, 'left');
            }
        }
        return parent::_addColumnFilterToCollection($column);
    }

    /**
     * Show all the details in the table formate
     * 
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns() {
        $this->addColumn('entity_id', array(
            'header' => Mage::helper('catalog')->__('ID'),
            'width' => '50px',
            'index' => 'entity_id',
        )); //Product Id
        $this->addColumn('name', array(
            'header' => Mage::helper('catalog')->__('Name'),
            'index' => 'name',
        )); //Product name

        $store = $this->_getStore();
        if ($store->getId()) {
            $this->addColumn('custom_name', array(
                'header' => Mage::helper('catalog')->__('Name in %s', $store->getName()),
                'index' => 'custom_name',
            ));
        }//Store Information 

        $this->addColumn('type', array(
            'header' => Mage::helper('catalog')->__('Type'),
            'width' => '60px',
            'index' => 'type_id',
            'type' => 'options',
            'options' => Mage::getSingleton('catalog/product_type')->getOptionArray(),
        )); //Product Type

        $sets = Mage::getResourceModel('eav/entity_attribute_set_collection')
                ->setEntityTypeFilter(Mage::getModel('catalog/product')->getResource()->getTypeId())
                ->load()
                ->toOptionHash();

        $this->addColumn('sku', array(
            'header' => Mage::helper('catalog')->__('SKU'),
            'width' => '80px',
            'index' => 'sku',
        )); //Product unique id SKU

        $store = $this->_getStore();
        $this->addColumn('price', array(
            'header' => Mage::helper('catalog')->__('Price'),
            'type' => 'price',
            'currency_code' => $store->getBaseCurrency()->getCode(),
            'index' => 'price',
        )); //Product Price



        $this->addColumn('reqcoupon_status', array(
            'header' => Mage::helper('catalog')->__('Request Coupon Status'),
            'width' => '70px',
            'index' => 'reqcoupon_status',
            'type' => 'options',
            'options' => Mage::getSingleton('catalog/product_status')->getOptionArray(),
        )); //Status of the Product 

        /**
         * To export the Grid date in XML / CSV file
         */
        $this->addExportType('*/*/exportCsv', Mage::helper('reqforcoupon')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('reqforcoupon')->__('XML'));

        return parent::_prepareColumns();
    }

    /**
     * Enable / Disable request option to specified products
     * 
     * @return  Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareMassaction() {
        //entity_id is unique identifier 
        $this->setMassactionIdField('entity_id');
        //Form field name
        $this->getMassactionBlock()->setFormFieldName('product');
        //status enable/disable
        $this->getMassactionBlock()->addItem('status', array(
                    'label' => Mage::helper('catalog')->__('Enable Coupon Request '),
                    'url' => $this->getUrl('*/*/massEnable', array('_current' => true)),
                ))
                ->addItem('status1', array(
                    'label' => Mage::helper('catalog')->__('Disable Coupon Request'),
                    'url' => $this->getUrl('*/*/massDisable', array('_current' => true)),
                ));


        return $this;
    }

}