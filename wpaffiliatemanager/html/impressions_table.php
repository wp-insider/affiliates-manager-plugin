		<p><?php printf(__( 'Displaying %1$d of %2$d impressions', 'wpam' ), count($this->viewData['impressions']), $this->viewData['impressionCount']); ?></p>

		<table class="widefat">
			<thead>
			<tr>
				 <th width="25"><?php _e( 'ID', 'wpam' ) ?></th>
				 <th width="200"><?php _e( 'Date Occurred', 'wpam' ) ?></th>
				 <th width="100"><?php _e( 'Creative', 'wpam' ) ?></th>
				 <th><?php _e( 'Referrer', 'wpam' ) ?></th>
			</tr>
			</thead>
			<tbody>
			<?php
			$creativeNames = $this->viewData['creativeNames'];

			foreach ( $this->viewData['impressions'] as $impression ) {
			?>
			<tr class="impression">
				<td><?php echo $impression->impressionId?></td>
				<td><?php echo date("m/d/Y H:i:s", $impression->dateCreated)?></td>
				<td><?php echo $creativeNames[$impression->sourceCreativeId]?></td>
				<td><?php echo $impression->referer?></td>
			</tr>
			<?php } ?>

			</tbody>
		</table>
		<?php
		 if ( ! count( $this->viewData['impressions'] ) ):
		?>
			 <div class="daterange-form"><p><?php _e( 'No records found for the date range selected.', 'wpam' ) ?></p></div>
		<?php endif; ?>
