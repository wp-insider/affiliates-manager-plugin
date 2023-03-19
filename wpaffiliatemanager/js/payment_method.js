jQuery(function() {
	function updatePaymentMethodDivs()
	{
		if (jQuery("#ddPaymentMethod").val() == 'paypal')
		{
			jQuery("#paypalDetails").show();
			jQuery("#checkDetails").hide();
                        jQuery("#bankDetails").hide();
		}
		else if (jQuery("#ddPaymentMethod").val() == 'check')
		{
			jQuery("#paypalDetails").hide();
			jQuery("#checkDetails").show();
                        jQuery("#bankDetails").hide();
		}
                else if (jQuery("#ddPaymentMethod").val() == 'bank')
		{
			jQuery("#paypalDetails").hide();
			jQuery("#checkDetails").hide();
                        jQuery("#bankDetails").show();
		}
		else
		{
			jQuery("#paypalDetails").hide();
			jQuery("#checkDetails").hide();
                        jQuery("#bankDetails").hide();
		}
	}

	updatePaymentMethodDivs();

	jQuery("#ddPaymentMethod").change(updatePaymentMethodDivs);
});
