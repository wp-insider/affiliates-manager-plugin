<script type="text/javascript">
	jQuery(function($){
		function updateFilters()
		{
			var statusVal = jQuery("#ddFilterStatus").val();
			var payoutVal = jQuery("#chkOverPayout").is(':checked');
			window.location = "<?php echo admin_url('admin.php?page=wpam-affiliates&statusFilter=') ?>" + statusVal + "&overPayout=" + payoutVal;
		}
		jQuery("#ddFilterStatus").change(function(){
			updateFilters();
		});
		jQuery("#chkOverPayout").change(function(){
			updateFilters();
		});
	});
</script>

<div class='wrap'>
<h2><?php _e( 'My Affiliates', 'wpam' ) ?></h2>
<h3></h3>

<table class="widefat">
	<thead>
	<tr>
		<th><?php _e( 'Filter', 'wpam' ) ?></th>
	</tr>
	</thead>
	<tr>
		<td>
			<label for="ddFilterStatus"><?php _e( 'Status:', 'wpam' ) ?></label>
			<select id="ddFilterStatus">

				<?php foreach ($this->viewData['statusFilters'] as $key => $val) { ?>

						<option value="<?php echo $key?>" <?php echo ($this->viewData['request']['statusFilter'] === $key ? 'selected="selected"' : '')?>><?php echo $val?></option>

				<?php } ?>

			</select>
			<input type="checkbox" id="chkOverPayout" name="chkOverPayout" <?php echo (isset($this->viewData['request']['overPayout']) && $this->viewData['request']['overPayout'] === 'true' ? 'checked="checked"' : '')?>/>
	 <label for="chkOverPayout"><?php echo sprintf( __( 'Only over payout minimum. (Balance >= %s)', 'wpam' ), wpam_format_money( $this->viewData['minPayoutAmount'], false ) )?></label>
		</td>
	</tr>
</table>

	<br/><br/>
<?php

		$columns = array('affiliateId' => array('name' => __( 'AID', 'wpam' ),
														'width' => 50,
														'desc_first' => true),
								 'status'  => array('name' => __( 'Status', 'wpam' ),
													'width' => 100,
													'desc_first' => false),
								 'balance'  => array('name' => __( 'Balance', 'wpam' ),
													 'width' => 100,
													 'desc_first' => false),
								 'earnings'  => array('name' => __( 'Earnings', 'wpam' ),
													  'width' => 100,
													  'desc_first' => false),
								 'firstName'  => array('name' => __( 'First Name', 'wpam' ),
													   'width' => 75,
													   'desc_first' => false),
								 'lastName'  => array('name' => __( 'Last Name', 'wpam' ),
													  'width' => 75,
													  'desc_first' => false),
								 'email'  => array('name' => __( 'Email', 'wpam' ),
												   'width' => 75,
												   'desc_first' => false),
								 'companyName'  => array('name' => __( 'Company', 'wpam' ),
														 'width' => 150,
														 'desc_first' => false),
								 'dateCreated'  => array('name' => __( 'Date Joined', 'wpam' ),
														 'width' => 100,
														 'desc_first' => false),
								 'websiteUrl'  => array('name' => __( 'Website', 'wpam' ),
														'desc_first' => false),
								 );								 

function wpam_print_column_headers($viewData, $column, $info) {				

	$width = isset( $info['width'] ) ? " width='{$info['width']}'" : '';
	$desc_first = $info['desc_first'];
	$class = array();

	//#30 removed sort classes (which add arrows) due to lack of real estate
	if ( $viewData['current_orderby'] == $column ) {
		$order = 'asc' == $viewData['current_order'] ? 'desc' : 'asc';
		//$class[] = 'sorted';
		//$class[] = $viewData['current_order'];
	} else {
		$order = $desc_first ? 'desc' : 'asc';
		//$class[] = 'sortable';
		//$class[] = $desc_first ? 'asc' : 'desc';
	}
	
	if ( !empty( $class ) )
		$class = " class='" . join( ' ', $class ) . "'";
	else
		$class = '';

	echo "<th{$width}{$class}><a href='" . admin_url( "admin.php?page=wpam-affiliates&orderby={$column}&order={$order}" ) . "'><span>{$info['name']}</span><span class='sorting-indicator'></span></a></th>\n";
}
				
?>
<table class="widefat">
	<thead>
		<tr>
			<th width="75"></th>

<?php
foreach ( $columns as $column => $info):
	wpam_print_column_headers($this->viewData, $column, $info);
endforeach;
?>	
		</tr>
	</thead>
	<tbody>
	<?php foreach ( $this->viewData['affiliates'] as $a ):?>

		<tr>

			<td><a class="button-secondary" href="<?php echo admin_url( "admin.php?page=wpam-affiliates&viewDetail={$a->affiliateId}") ?>"><?php _e( 'View', 'wpam' ) ?></a></td>
			<td><?php echo $a->affiliateId?></td>
			<td><span class="status_<?php echo $a->status?>"><?php echo wpam_format_status( $a->status ) ?></span></td>
			<td><?php echo wpam_format_money($a->balance)?></td>
			<td><?php echo wpam_format_money($a->earnings)?></td>
			<td><?php echo $a->firstName?></td>
			<td><?php echo $a->lastName?></td>
			<td><?php echo $a->email?></td>
			<td><?php echo $a->companyName?></td>
			<td><?php echo date("m/d/Y", strtotime($a->dateCreated))?></td>
			<td>
				<?php echo $a->websiteUrl?>
			</td>
		</tr>

	<?php endforeach; ?>

	</tbody>
</table>
</div>
