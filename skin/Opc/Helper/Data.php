<?php
class IWD_Opc_Helper_Data extends Mage_Core_Helper_Abstract{
	
	private $_version = 'CE';
	
	const XML_PATH_VAT_FRONTEND_VISIBILITY = 'customer/create_account/vat_frontend_visibility';
	
	const XML_PATH_SHIPPING_VISIBILITY = 'opc/default/show_shipping';
	
	const XML_PATH_TERMS_TYPE = 'opc/default/terms_type';
	
	const XML_PATH_COMMENT = 'opc/default/comment';
	
	const XML_PAYPAL_LIGHTBOX_SANDBOX = 'opc/paypal/sandbox';
	
	const XML_PAYPAL_LIGHTBOX_ENABLED = 'opc/paypal/status';
	
	public function isAvailableVersion(){
	
		$mage  = new Mage();
		if (!is_callable(array($mage, 'getEdition'))){
			$edition = 'Community';
		}else{
			$edition = Mage::getEdition();
		}
		unset($mage);
			
		if ($edition=='Enterprise' && $this->_version=='CE'){
			return false;
		}
		return true;
	
	}
	
	public function isEnable(){
		$status = Mage::getStoreConfig('opc/global/status');		
		return $status;
	}
	
	/**
	 * Get string with frontend validation classes for attribute
	 *
	 * @param string $attributeCode
	 * @return string
	 */
	public function getAttributeValidationClass($attributeCode){
		/** @var $attribute Mage_Customer_Model_Attribute */
		$attribute = isset($this->_attributes[$attributeCode]) ? $this->_attributes[$attributeCode]
		: Mage::getSingleton('eav/config')->getAttribute('customer_address', $attributeCode);
		$class = $attribute ? $attribute->getFrontend()->getClass() : '';
	
		if (in_array($attributeCode, array('firstname', 'middlename', 'lastname', 'prefix', 'suffix', 'taxvat'))) {
			if ($class && !$attribute->getIsVisible()) {
				$class = ''; // address attribute is not visible thus its validation rules are not applied
			}
	
			/** @var $customerAttribute Mage_Customer_Model_Attribute */
			$customerAttribute = Mage::getSingleton('eav/config')->getAttribute('customer', $attributeCode);
			$class .= $customerAttribute && $customerAttribute->getIsVisible()
			? $customerAttribute->getFrontend()->getClass() : '';
			$class = implode(' ', array_unique(array_filter(explode(' ', $class))));
		}
	
		return $class;
	}
	
	public function isVatAttributeVisible(){
		return (bool)Mage::getStoreConfig(self::XML_PATH_VAT_FRONTEND_VISIBILITY);
	}
	
	
	public function isEnterprise(){
		return Mage::getConfig()->getModuleConfig('Enterprise_Enterprise') && Mage::getConfig()->getModuleConfig('Enterprise_AdminGws') && Mage::getConfig()->getModuleConfig('Enterprise_Checkout') && Mage::getConfig()->getModuleConfig('Enterprise_Customer');
	}
	
	
	public function isShowShippingForm(){
		return (bool) Mage::getStoreConfig(self::XML_PATH_SHIPPING_VISIBILITY);
	}
	
	public function getTermsType(){
		return Mage::getStoreConfig(self::XML_PATH_TERMS_TYPE);
	}
	
	public function isShowComment(){
		return Mage::getStoreConfig(self::XML_PATH_COMMENT);
	}
	
	
	public function getPayPalExpressUrl(){
		if (Mage::getStoreConfig(self::XML_PAYPAL_LIGHTBOX_SANDBOX)){
			return 'https://www.sandbox.paypal.com/checkoutnow?token=';
		}else{
			return 'https://www.paypal.com/checkoutnow?token=';
		}
	
	}
	
	public function getPayPalLightboxEnabled(){
		return (bool)Mage::getStoreConfig(self::XML_PAYPAL_LIGHTBOX_ENABLED);
	}
}