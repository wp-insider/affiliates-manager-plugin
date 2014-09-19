<div class="aff-wrap">
<?php
include WPAM_BASE_DIRECTORY . "/html/affiliate_cp_nav.php";
?>
		
<div class="wrap">

	 <h3><?php _e( 'The following creatives are available for publication.', 'wpam' ) ?></h3>

	<table class="widefat">
		<thead>
			<tr>
			 <th width="75"><?php _e( 'Type', 'wpam' ) ?></th>
			 <th><?php _e( 'Name', 'wpam' ) ?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($this->viewData['creatives'] as $creative) {?>
			<tr>
				<td><?php echo $creative->type?></td>
				<td><a href="?page_id=<?php echo the_ID()?>&sub=creatives&action=detail&creativeId=<?php echo $creative->creativeId?>"><?php echo $creative->name?></a></td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
</div>
</div>