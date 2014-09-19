<script type="text/javascript">
jQuery(function($) {
	if (!$('#chkEnablePaypalMassPay').is(':checked'))
		$('#paypalAPIConfiguration').hide();

	$('#chkEnablePaypalMassPay').change(function() {
		if ($(this).is(':checked'))
			$('#paypalAPIConfiguration').show();
		else
			$('#paypalAPIConfiguration').hide();
	});

});
</script>
		<div class="wpam-tip" style="margin: 10px">
			<?php _e( 'Looking for help? Check out the bundled step-by-step guide for setting up PayPal Mass Pay:', 'wpam' ) ?>
			<div style="margin-left: 30px;  margin-top: 10px;height: 20px;">
				<div style="float: left; width: 20px;"><img src="<?php echo WPAM_URL . "/images/icon-acrobat.png"?>" style="display: ;"/></div>
				<div style="float: left; margin-left: 5px;">
				<?php echo sprintf( __( '<a href="%s">How to Setup PayPal Mass Pay</a> (PDF)', 'wpam' ), WPAM_URL . "/doc/How to Setup PayPal Mass Pay.pdf" ) ?>
				</div>
			</div>

		</div>
<input type="checkbox" name="chkEnablePaypalMassPay" id="chkEnablePaypalMassPay"
<?php if ($this->viewData['request']['chkEnablePaypalMassPay']) {
	echo 'checked="checked"';
}?>
/>&nbsp;<label for="chkEnablePaypalMassPay"><?php _e( 'Enable PayPal Mass Pay &#0153;<br /><small>NOTE: Requires a PayPal Premier or PayPal Business account</small>', 'wpam' ) ?></label>

<fieldset id="paypalAPIConfiguration" style="border: 1px solid #ddd; padding: 10px; margin: 5px;">
	<legend><?php _e( 'PayPal API Configuration', 'wpam' ) ?></legend>
	<table class="wpam-form-table">
		<tr>
			<th><label for="txtPaypalAPIUser"><?php _e( 'API Username', 'wpam' ) ?></label></th>
			<td><input type="text" id="txtPaypalAPIUser" name="txtPaypalAPIUser" value="<?php echo $this->viewData['request']['txtPaypalAPIUser']?>"/></td>
		</tr>
		<tr>
			<th><label for="txtPaypalAPIPassword"><?php _e( 'API Password', 'wpam' ) ?></label></th>
			<td><input type="password" id="txtPaypalAPIPassword" name="txtPaypalAPIPassword"  value="<?php echo $this->viewData['request']['txtPaypalAPIPassword']?>"/></td>
		</tr>
		<tr>
			<th><label for="txtPaypalAPISignature"><?php _e( 'Signature', 'wpam' ) ?></label></th>
			<td><input type="text" id="txtPaypalAPISignature" name="txtPaypalAPISignature" value="<?php echo $this->viewData['request']['txtPaypalAPISignature']?>"/></td>
		</tr>
		<tr>
			<th><label for="ddPaypalAPIEndPoint"><?php _e( 'API End Point', 'wpam' ) ?></label><br/><span style="font-size:0.8em"><?php _e( "WARNING: Set to LIVE if you don't know what this is for!", 'wpam' ) ?></span></th>
			<td valign="top">
				<select name="ddPaypalAPIEndPoint" id="ddPaypalAPIEndPoint">
					<option value="live" <?php echo ($this->viewData['request']['ddPaypalAPIEndPoint'] == 'live' ? 'selected="selected"' : '')?>><?php echo sprintf( __( 'Live (%s)', 'wpam' ), WPAM_PayPal_Service::PAYPAL_API_ENDPOINT_LIVE ) ?></option>
					<option value="dev"  <?php echo ($this->viewData['request']['ddPaypalAPIEndPoint'] == 'dev'  ? 'selected="selected"' : '')?>><?php echo sprintf( __( 'Sandbox - TESTING (%s)', 'wpam' ), WPAM_PayPal_Service::PAYPAL_API_ENDPOINT_SANDBOX ) ?></option>
				</select>
			</td>
		</tr>
	</table>

</fieldset>



