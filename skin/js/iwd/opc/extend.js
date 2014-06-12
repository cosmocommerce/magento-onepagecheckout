;
//DUMMY FOR EE CHECKOUT
var checkout =  {
		steps : new Array("login", "billing", "shipping", "shipping_method", "payment", "review"),
		
		gotoSection: function(section){
			IWD.OPC.backToOpc();
		},
		accordion:{
			
		}
};


IWD.OPC.prepareExtendPaymentForm =  function(){
	$j('.opc-col-left').hide();
	$j('.opc-col-center').hide();
	$j('.opc-col-right').addClass('full-page');
	$j('#checkout-review-table-wrapper').hide();
	$j('#checkout-review-submit').hide();
	$j('.opc-newsletter').hide();
	$j('.text-login').hide();
	
};

IWD.OPC.backToOpc =  function(){
	$j('.opc-col-left').show();
	$j('.opc-col-center').show();
	$j('.opc-col-right').removeClass('full-page');
	$j('#checkout-review-table-wrapper').show();
	$j('#checkout-review-submit').show();
	$j('#payflow-advanced-iframe').hide();
	$j('#hss-iframe').hide();
	$j('.opc-newsletter').show();
	$j('.text-login').show();
	IWD.OPC.saveOrderStatus = false;
	
};



IWD.OPC.Plugin = {
		
		observer: {},
		
		
		dispatch: function(event, data){
				
			
			if (typeof(IWD.OPC.Plugin.observer[event]) !="undefined"){
				
				var callback = IWD.OPC.Plugin.observer[event];
				callback(data);
				
			}
		},
		
		event: function(eventName, callback){
			IWD.OPC.Plugin.observer[eventName] = callback;
		}
};

/** 3D Secure Credit Card Validation - CENTINEL **/
IWD.OPC.Centinel = {
	init: function(){
		IWD.OPC.Plugin.event('savePaymentAfter', IWD.OPC.Centinel.validate);
	},
	
	validate: function(){
		if (typeof(CentinelAuthenticateController) != "undefined"){
			$j('.opc-col-left').hide();
			$j('.opc-col-center').hide();
			$j('.opc-col-right').addClass('full-page');
		}
	},
	
	success: function(){
		if (typeof(CentinelAuthenticateController) != "undefined"){
			$j('.opc-col-right').removeClass('full-page');
			$j('.opc-col-left').show();
			$j('.opc-col-center').show();
		}
	}
	
};


/** PAYPAL EXPRESS CHECKOUT LIGHTBOX **/
IWD.OPC.Lipp = {
		init: function(){
			if (IWD.OPC.Checkout.config.paypalLightBoxEnabled==true){
				IWD.OPC.Plugin.event('redirectPayment', IWD.OPC.Lipp.checkPaypalExpress);
			}
		},
		
		checkPaypalExpress:function(url){
			IWD.OPC.Checkout.showLoader();
			try{
				if (url.match(/paypal\/express\/start/i)){
					IWD.OPC.Checkout.xhr = true;
					IWD.OPC.Lipp.prepareToken();
				}
			
			}catch(e){
				IWD.OPC.Checkout.xhr = null;
			}
		},
		
		prepareToken: function(){
			$j.post(IWD.OPC.Checkout.config.baseUrl + 'onepage/express/start',{"redirect":'onepage'}, IWD.OPC.Lipp.prepareTokenResponse,'json');
		},
		
		prepareTokenResponse: function(response){
			if (typeof(response.error)!="undefined"){
				if (response.error==false){
					IWD.OPC.Checkout.hideLoader();
					PAYPAL.apps.Checkout.startFlow(IWD.OPC.Checkout.config.paypalexpress + response.token);
				}
				
				if (response.error==true){
					alert(response.message);
				}
			}
		}
		
		
}

function toggleContinueButton(){}//dummy

$j(document).ready(function(){
	IWD.OPC.Centinel.init();
	IWD.OPC.Lipp.init(); 
});
