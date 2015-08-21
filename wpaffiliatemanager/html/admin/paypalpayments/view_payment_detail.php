<?php
$massPayment = $this->viewData['pplog'];
$affiliates = $this->viewData['affiliates'];
?>
		
<div class="wrap">
	<h2><?php _e('PayPal Mass Pay', 'wpam');?></h2>
	<h3><?php _e('Payment Detail', 'wpam');?></h3>

	<table class="widefat" style="width: 700px">

		<thead>
		<tr><th width="150">&nbsp;</th><th width="500">&nbsp;</th></tr>
		</thead>

		<tr><th><?php _e('Database ID', 'wpam');?></th><td><?php echo $massPayment->paypalLogId?></td></tr>
		<tr><th><?php _e('Date Occurred', 'wpam');?></th><td><?php echo date("m/d/Y H:i:s",$massPayment->dateOccurred)?></td></tr>
		<tr><th><?php _e('PayPal Timestamp', 'wpam');?></th><td><?php echo date("m/d/Y H:i:s", $massPayment->responseTimestamp)?></td></tr>
		<tr><th><?php _e('PayPal Correlation ID', 'wpam');?></th> <td><?php echo $massPayment->correlationId?></td></tr>
		<tr><th><?php _e('Amount', 'wpam');?></th><td><?php echo ($massPayment->amount)?></td></tr>
		<tr><th><?php _e('Fee', 'wpam');?></th><td><?php echo ($massPayment->fee)?></td></tr>
		<tr><th><?php _e('Total Amount', 'wpam');?></th><td><?php echo ($massPayment->totalAmount)?></td></tr>
		<tr class="transaction-<?php echo $massPayment->status?>"><th>Status</th><td><?php echo $massPayment->status?></td></tr>
		<?php if ($massPayment->status == 'pending') {?>
			<tr>
				<th style="vertical-align: top"><?php _e('Reconciliation', 'wpam');?></th>
				<td>
					<div style="margin-left: 25px; margin-top: 25px;">
						<a class="button-secondary" href="<?php echo admin_url('admin.php?page=wpam-payments&step=reconcile_manual&id='.$massPayment->paypalLogId)?>"><?php _e('Manually reconcile payments ... ', 'wpam');?></a><br/><br/>
						<a class="button-secondary" href="<?php echo admin_url('admin.php?page=wpam-payments&step=reconcile_with_file&id='.$massPayment->paypalLogId)?>"><?php _e('Reoncile using PayPal Mass Payment results file ... ', 'wpam');?></a><br/><br/>
					</div>

				</td>
			</tr>
		<?php } else if ($massPayment->status == 'failed') { ?>
			<tr>
				<th style="vertical-align:top"><?php _e('Errors', 'wpam');?></th>
				<td>
				<?php foreach ($massPayment->errors as $error) { ?>
					<strong><?php _e('Code:', 'wpam');?></strong> <?php echo $error->getCode()?><br/>
					<strong><?php _e('Message:', 'wpam');?></strong> <?php echo $error->getLongMessage()?><br/>
					<strong><?php _e('Severity:', 'wpam');?></strong> <?php echo $error->getSeverityCode()?><br/>
					<br/>
				<?php } ?>
				</td>
			</tr>
		<?php } ?>


	</table>

	<h3><?php _e('Associated Transactions', 'wpam');?></h3>
	<table class="widefat" style="width: auto">
		<thead>
		<tr>

			<th width="25"><?php _e('ID', 'wpam');?></th>
			<th width="100"><?php _e('Date Occurred', 'wpam');?></th>
			<th width="150"><?php _e('Affiliate', 'wpam');?></th>
			<th width="150"><?php _e('PayPal E-Mail', 'wpam');?></th>
			<th width="100"><?php _e('Status', 'wpam');?></th>
			<th><?php _e('Description', 'wpam');?></th>
			<th width="100"><?php _e('Amount', 'wpam');?></th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($this->viewData['transactions'] as $transaction) {?>
		<?php $affiliate = $affiliates[$transaction->affiliateId]; ?>
		<tr class="transaction-<?php echo $transaction->status?>">
			<td><?php echo $transaction->transactionId?></td>
			<td><?php echo date("m/d/Y", $transaction->dateCreated)?></td>
			<td><?php echo $affiliate->firstName?> <?php echo $affiliate->lastName?></td>
			<td><?php echo $affiliate->paypalEmail?></td>
			<td><?php echo $transaction->status?></td>
			<td><?php echo $transaction->description?></td>

			<td style="text-align: right"><?php echo wpam_format_money($transaction->amount)?></td>
		</tr>
		<?php } ?>

		</tbody>
	</table>
</div>