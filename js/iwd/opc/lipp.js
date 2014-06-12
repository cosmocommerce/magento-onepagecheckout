;
var IWD=IWD||{};
$j = jQuery;
IWD.LIPP = {
	config: null,
	
	init: function(){
		if (typeof(lippConfig)!="undefined"){
			this.config = $j.parseJSON(lippConfig);
			if (this.config.paypalLightBoxEnabled==true){
				this.initOnCart();
			}
		}
	}, 
	
	initOnCart: function(){
		$j('.checkout-types .paypal-logo a').click(function(e){
			e.preventDefault();
			IWD.LIPP.prepareToken();
		});
	},
	
	prepareToken: function(){
		IWD.LIPP.showLoader();
		$j.post(IWD.LIPP.config.baseUrl + 'onepage/express/start',{}, IWD.LIPP.prepareTokenResponse,'json');
	},
	
	prepareTokenResponse: function(response){
		IWD.LIPP.hideLoader();
		if (typeof(response.error)!="undefined"){
			if (response.error==false){
				PAYPAL.apps.Checkout.startFlow(IWD.LIPP.config.paypalexpress + response.token);
			}
			
			if (response.error==true){
				alert(response.message);
			}
		}
	},
	
	showLoader: function(){
		$j('.opc-ajax-loader').show();
	},
	
	hideLoader: function(){
		$j('.opc-ajax-loader').hide();
	},
};

$j(document).ready(function(){
	IWD.LIPP.init();
});