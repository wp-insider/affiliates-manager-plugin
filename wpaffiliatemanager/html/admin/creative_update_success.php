
<div class="wrap">

	<h2><?php echo $this->viewData['request']['action'] === 'edit' ? __( 'Edit', 'wpam' ) : __( 'New', 'wpam' ) ?> <?php _e( 'Creative', 'wpam' ) ?></h2>

	<h3>
		<?php
			if ($this->viewData['request']['action'] === 'edit') {
				_e( 'Creative updated.', 'wpam' );
			} else if ($this->viewData['request']['action'] === 'new') {
				_e( 'New creative created.  This creative is not yet active.', 'wpam' );
			} 
		?>
	</h3>

	<a href="<?php echo admin_url( 'admin.php?page=wpam-creatives' ) ?>"><?php _e( 'Return to Creatives', 'wpam' ) ?></a>

</div>