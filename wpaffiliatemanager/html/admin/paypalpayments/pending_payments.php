<div class="wrap">

	<h2>PayPal Mass Pay</h2>
	<h3>View Existing Payments</h3>

	<table class="widefat" style="width: auto">
		<thead>
		<tr>
			<th width="50"></th>
			<th width="20">ID</th>
			<th width="150">Date Posted</th>
			<th width="130">PayPal ID</th>
			<th width="100">Amount</th>
			<th width="100">Fee</th>
			<th width="100">Total</th>
			<th width="120">Status</th>
		</tr>
		</thead>
		<tbody>
		<?php if (count($this->viewData['logs']) > 0) { ?>
			<?php foreach ($this->viewData['logs'] as $massPayment) { ?>
					<tr class="transaction-<?php echo $massPayment->status?>">
						<td><a class="button-secondary" href="<?php echo admin_url('admin.php?page=wpam-payments&step=view_payment_detail&id='.$massPayment->paypalLogId)?>">View</a></td>
						<td><?php echo $massPayment->paypalLogId?></td>
						<td><?php echo date("m/d/Y H:i:s",$massPayment->dateOccurred)?></td>
						<td><?php echo $massPayment->correlationId?></td>
						<td><?php echo $massPayment->amount?></td>
						<td><?php echo $massPayment->fee?></td>
						<td><?php echo $massPayment->totalAmount?></td>
						<td><?php echo $massPayment->status?></td>
					</tr>
			<?php } ?>
		<?php } else { ?>
			<tr>
				<td colspan="100" style="text-align: center; vertical-align: middle; font-style: italic;">
					(No Mass Payments on Record)
				</td>
			</tr>
		<?php } ?>
		</tbody>
	</table>


</div>

