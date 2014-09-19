<?php
$currency = WPAM_MoneyHelper::getDollarSign();
?>
<script type="text/javascript">
jQuery(function($) {
	var submitConfirm = false;
	$("#confirm-continue-dialog").dialog({
		autoOpen: false,
		buttons: [ {
				  text : '<?php _e( 'Yes, Submit to PayPal', 'wpam' ) ?>',
				  click : function() {
					$(this).dialog('close');
					submitConfirm = true;
					$("#main-form").submit();
				  }
			}, {
				  text : '<?php _e( 'No, Cancel', 'wpam' ) ?>',
				  click : function() {
					$(this).dialog('close');
				  }
			} ],
		modal: true, resizable: false, draggable: false
	});
	$("#main-form").submit(function() {
		if (submitConfirm)
		{
			return true;
		}
		else
		{
			$("#confirm-continue-dialog").dialog('open');
			return false;
		}
	});

});
</script>

<div id="confirm-continue-dialog" style="display: none" title="<?php _e( 'Submit to PayPal?', 'wpam' ) ?>">
	<p><?php _e( 'Are you sure you wish to submit a mass payment to PayPal?', 'wpam' ) ?></p>
	<p><strong><?php echo sprintf( __( 'Your PayPal account will be charged %s!', 'wpam' ), wpam_format_money( $this->viewData['total'], false ) ) ?></strong></p>
</div>

<div class="wrap">
	<h2><?php _e( 'PayPal Mass Pay', 'wpam' ) ?></h2>
	<h3><?php _e( 'Review Your Mass Payment', 'wpam' ) ?></h3>

	<div style="width: 900px;">
	<form id="main-form" method="POST" action="<?php echo admin_url("admin.php?page=wpam-payments&step=submit_to_paypal")?>">
	<table class="widefat" style="width: 900px">
		<thead>
		<tr>
			<th width="25"><?php _e( 'AID', 'wpam' ) ?></th>
			<th width="100"><?php _e( 'First Name', 'wpam' ) ?></th>
			<th width="100"><?php _e( 'Last Name', 'wpam' ) ?></th>
			<th width="100"><?php _e( 'Company', 'wpam' ) ?></th>
			<th width="auto"><?php _e( 'PayPal E-Mail', 'wpam' ) ?></th>
			<th width="60"><?php _e( 'Balance', 'wpam' ) ?></th>
			<th width="5"></th>
			<th width="60"><?php _e( 'Payment', 'wpam' ) ?></th>
			<th width="5"></th>
			<th width="60"><?php _e( 'New Balance', 'wpam' ) ?></th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($this->viewData['affiliates'] as $affiliate) { ?>
			<tr>
				<td><?php echo $affiliate->affiliateId?>
					<input
						type="hidden"
						name="affiliates[<?php echo $affiliate->affiliateId?>][id]"
						value="<?php echo $affiliate->affiliateId?>" />
					<input
						type="hidden"
						name="affiliates[<?php echo $affiliate->affiliateId?>][amount]"
						value="<?php echo $affiliate->paymentAmount?>" />
				</td>
				<td><?php echo $affiliate->firstName?></td>
				<td><?php echo $affiliate->lastName?></td>
				<td><?php echo $affiliate->companyName?></td>
				<td><?php echo $affiliate->paypalEmail?></td>
				<td><?php echo wpam_format_money($affiliate->balance)?></td>
				<td>-</td>
				 <td><?php echo wpam_format_money($affiliate->paymentAmount, false)?></td>
				<td>=</td>
				<td><?php echo wpam_format_money($affiliate->newBalance)?></td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
	<table class="totalsTable" style="position: relative; width: 400px; left: 500px; border: 1px solid #aaa;">
		<tr>
			<td style="width: 250px;"><?php _e( 'Sub-Total', 'wpam' ) ?></td>
	 <td style="width: 150px;" class="moneyCell" id="subTotalCell"><?php echo wpam_format_money($this->viewData['paymentTotal'], false)?></td>
		</tr>
		<tr>
			<td><?php echo sprintf( __( 'PayPal Fee<br /><small>2%% per payment, max %s1 per payment</small>', 'wpam' ), $currency ) ?></td>
	 		<td class="moneyCell" id="paypalFeeCell"><?php echo wpam_format_money($this->viewData['feeTotal'], false)?></td>
		</tr>
		<tr class="totalSeparatorRow"><td colspan="2"></td> </tr>
		<tr class="totalRow">
			<th><?php _e( 'Estimated Total', 'wpam' ) ?></th>
			<th class="moneyCell" id="totalCell"><?php echo wpam_format_money($this->viewData['total'], false)?></th>
		</tr>
	</table>
	<input type="submit" class="button-primary" id="submit-to-paypal-button" style="float: right; margin-top: 20px" value="<?php _e( 'Submit Mass Payment to PayPal', 'wpam' ) ?>"/>
	</form>
	</div>
</div>