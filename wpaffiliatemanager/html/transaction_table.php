	<script>
	jQuery(function($) {
		var dates = $( "#from, #to" ).datepicker({
			numberOfMonths: 2,
			onSelect: function( selectedDate ) {
				var option = this.id == "from" ? "minDate" : "maxDate",
					instance = $( this ).data( "datepicker" ),
					date = $.datepicker.parseDate(
						instance.settings.dateFormat ||
						$.datepicker._defaults.dateFormat,
						selectedDate, instance.settings );
				dates.not( this ).datepicker( "option", option, date );
			}
		});


		$('#reset').click(function() {
			$('#to').val('');
			$('#from').val('');
			$('#dateRange').submit();
		});
			
	});
   	</script>
	<div class="daterange-form"><form method="post" id="dateRange">
		<div>
<p><?php _e( 'Date Range:', 'wpam' ) ?><br/>
<label for="from"><?php _e( 'From', 'wpam' ) ?></label>
<input type="text" id="from" name="from" value="<?php echo $this->viewData['from']; ?>"/>
<label for="to">to</label>
<input type="text" id="to" name="to" value="<?php echo $this->viewData['to']; ?>"/>
			 <input type="submit" name="apply" value="<?php _e( 'Apply', 'wpam' ) ?>" />
			 <input type="button" name="clear" value="<?php _e( 'Clear', 'wpam' ) ?>" id="reset" />
			 </p>
		</div>
	</form></div>
		<table class="widefat">
			<thead>
			<tr>
				 <th width="25"><?php _e( 'ID', 'wpam' ) ?></th>
				 <th width="80"><?php _e( 'Type', 'wpam' ) ?></th>
				 <th width="100"><?php _e( 'Date Occurred', 'wpam' ) ?></th>
				 <th width="100"><?php _e( 'Status', 'wpam' ) ?></th>
				 <th><?php _e( 'Description', 'wpam' ) ?></th>
				 <th width="100"><?php _e( 'Reference ID', 'wpam' ) ?></th>
				 <th width="100"><?php _e( 'Amount', 'wpam' ) ?></th>
				<?php if( ! empty( $this->viewData['showBalance'] ) ): ?>
					 <th width="100"><?php _e( 'Balance', 'wpam' ) ?></th>
				<?php endif; ?>
			</tr>
			</thead>
			<tbody>
			<?php foreach ( $this->viewData['transactions'] as $transaction ) {?>
			<tr class="transaction-<?php echo $transaction->status?>">
				<td><?php echo $transaction->transactionId?></td>
				<td><?php echo $transaction->type?></td>
				<td><?php echo date("m/d/Y", $transaction->dateCreated)?></td>
				<td><?php echo $transaction->status?></td>
				<td><?php echo $transaction->description?></td>
				<td><?php echo $transaction->referenceId?></td>
				<td style="text-align: right"><?php echo wpam_format_money($transaction->amount)?></td>
				<?php if( $this->viewData['showBalance'] ): ?>
				<td style="text-align: right"><?php echo wpam_format_money($transaction->balance)?></td>
				<?php endif; ?>
			</tr>
			<?php } ?>

			</tbody>
		</table>
		<?php
		 if ( ! count( $this->viewData['transactions'] ) ):
		?>
			 <div class="daterange-form"><p><?php _e( 'No records found for the date range selected.', 'wpam' ) ?></p></div>
		<?php endif; ?>
