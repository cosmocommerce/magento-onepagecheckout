<?php
class IWD_Opc_Block_Wrapper extends  Mage_Core_Block_Template{
	
	
	const XML_PATH_DEFAULT_SHIPPING = 'opc/default/shipping';
	
	const XML_PATH_GEO_COUNTRY = 'opc/geo/country';
	
	const XML_PATH_GEO_CITY = 'opc/geo/city';
	
	
	
	/**
	 * Get one page checkout model
	 *
	 * @return Mage_Checkout_Model_Type_Onepage
	 */
	public function getOnepage(){
		return Mage::getSingleton('checkout/type_onepage');
	}
	
	protected function _getReviewHtml(){
		//clear cache aftr change collection - if no magento can't find product in review block
		Mage::app()->getCacheInstance()->cleanType('layout');

		$layout = $this->getLayout();
		$update = $layout->getUpdate();
		$update->load('checkout_onepage_review');
		$layout->generateXml();
		$layout->generateBlocks();
		$review = $layout->getBlock('root');
		$review->setTemplate('opc/onepage/review/info.phtml');
		
		return $review->toHtml();
	}
	
	protected function _getCart(){
		return Mage::getSingleton('checkout/cart');
	}

	
	public function getJsonConfig() {
	
		$config = array ();
		$params = array (
				'_secure' => true
		);
		
		$base_url = Mage::getBaseUrl('link', true);

		// protocol for ajax urls should be the same as for current page
		$http_protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS']==='on')?'https':'http';
		if ($http_protocol == 'https')
			$base_url = str_replace('http:', 'https:', $base_url);
		else
			$base_url = str_replace('https:', 'http:', $base_url);
		//////

		$config['baseUrl'] = $base_url;
		$config['isLoggedIn'] = (int) Mage::getSingleton('customer/session')->isLoggedIn();
		$config['comment'] = Mage::helper('opc')->isShowComment();
		$config['paypalLightBoxEnabled'] = Mage::helper('opc')->getPayPalLightboxEnabled();

		$config['agree_error'] = $this->__('Please agree to all the terms and conditions before placing the order.');
		
		return Mage::helper ( 'core' )->jsonEncode ( $config );
	}
	
	
}