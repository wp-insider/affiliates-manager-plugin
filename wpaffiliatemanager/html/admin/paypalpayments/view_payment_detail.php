<?php
$massPayment = $this->viewData['pplog'];
$affiliates = $this->viewData['affiliates'];
?>
		
<div class="wrap">
	<h2>PayPal Mass Pay</h2>
	<h3>Payment Detail</h3>

	<table class="widefat" style="width: 700px">

		<thead>
		<tr><th width="150">&nbsp;</th><th width="500">&nbsp;</th></tr>
		</thead>

		<tr><th>Database ID</th><td><?php echo $massPayment->paypalLogId?></td></tr>
		<tr><th>Date Occurred</th><td><?php echo date("m/d/Y H:i:s",$massPayment->dateOccurred)?></td></tr>
		<tr><th>PayPal Timestamp</th><td><?php echo date("m/d/Y H:i:s", $massPayment->responseTimestamp)?></td></tr>
		<tr><th>PayPal Correlation ID</th> <td><?php echo $massPayment->correlationId?></td></tr>
		<tr><th>Amount</th><td><?php echo ($massPayment->amount)?></td></tr>
		<tr><th>Fee</th><td><?php echo ($massPayment->fee)?></td></tr>
		<tr><th>Total Amount</th><td><?php echo ($massPayment->totalAmount)?></td></tr>
		<tr class="transaction-<?php echo $massPayment->status?>"><th>Status</th><td><?php echo $massPayment->status?></td></tr>
		<?php if ($massPayment->status == 'pending') {?>
			<tr>
				<th style="vertical-align: top">Reconciliation</th>
				<td>
					<div style="margin-left: 25px; margin-top: 25px;">
						<a class="button-secondary" href="<?php echo admin_url('admin.php?page=wpam-payments&step=reconcile_manual&id='.$massPayment->paypalLogId)?>">Manually reconcile payments ... </a><br/><br/>
						<a class="button-secondary" href="<?php echo admin_url('admin.php?page=wpam-payments&step=reconcile_with_file&id='.$massPayment->paypalLogId)?>">Reoncile using PayPal Mass Payment results file ... </a><br/><br/>
					</div>

				</td>
			</tr>
		<?php } else if ($massPayment->status == 'failed') { ?>
			<tr>
				<th style="vertical-align:top">Errors</th>
				<td>
				<?php foreach ($massPayment->errors as $error) { ?>
					<strong>Code:</strong> <?php echo $error->getCode()?><br/>
					<strong>Message:</strong> <?php echo $error->getLongMessage()?><br/>
					<strong>Severity:</strong> <?php echo $error->getSeverityCode()?><br/>
					<br/>
				<?php } ?>
				</td>
			</tr>
		<?php } ?>


	</table>

	<h3>Associated Transactions</h3>
	<table class="widefat" style="width: auto">
		<thead>
		<tr>

			<th width="25">ID</th>
			<th width="100">Date Occurred</th>
			<th width="150">Affiliate</th>
			<th width="150">PayPal E-Mail</th>
			<th width="100">Status</th>
			<th>Description</th>
			<th width="100">Amount</th>
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