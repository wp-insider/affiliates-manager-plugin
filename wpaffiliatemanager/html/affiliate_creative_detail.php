<?php
$model = $this->viewData['creative'];

function formatType($type)
{
	switch ($type)
	{
		case 'image': return __( 'Image', 'wpam' );
		case 'text': return __( 'Text Link', 'wpam' );
		default: wp_die( __( 'unknown creative type.', 'wpam' ) );
	}
}
?>


<script type="text/javascript">

	jQuery(document).ready(function() {
		jQuery("#dialog-preview").dialog({
			resizable: true,
			width: 640,
			height: 480,
			closeOnEscape: true,
			modal: true,
			draggable: true,
			autoOpen: false,
			buttons: [ {
				text : '<?php _e( 'OK', 'wpam' ) ?>',
			  	click : function() { jQuery(this).dialog('close'); }
			} ]
		});

		jQuery("#previewButton").click(function() {
			jQuery("#dialog-preview").dialog('open');
		});

	});
</script>


<div id="dialog-preview" title="<?php _e( 'Preview', 'wpam' ) ?>" style="display: none">
	<?php echo $this->viewData['htmlPreview']?>
</div>

<?php
echo '<div class="aff-wrap">';
include WPAM_BASE_DIRECTORY . "/html/affiliate_cp_nav.php";
?>

<div class="wrap">

	 <h2><?php _e( 'Creative:', 'wpam' ) ?> <?php echo $model->name?></h2>

	 <p align="center"><button type="button" name="preview" id="previewButton" class="button-secondary"><?php _e( 'Preview', 'wpam' ) ?></button></p>

	 <h2><?php _e( 'Your Affiliate-Specific HTML snippet', 'wpam' ) ?></h2>
	<textarea rows="5" cols="50"><?php echo htmlspecialchars($this->viewData['htmlSnippet']); ?></textarea>

<p/>
	<?php if ($model->type == 'text') { ?>

	<table class="widefat">
		<thead>
		<tr>
			<th colspan="2"><?php _e( 'Text Link Properties', 'wpam' ) ?></th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td width="150"><?php _e( 'Link Text', 'wpam' ) ?></td>
			<td><?php echo $model->linkText?></td>
		</tr>
		<tr>
			<td><?php _e( 'Alt Text', 'wpam' ) ?></td>
			<td>
				<?php echo $model->altText?>
			</td>
		</tr>
		</tbody>
	</table>

	<?php } else if ($model->type == 'image') { ?>
	<table class="widefat">
		<thead>
		<tr>
			<th colspan="2"><?php _e( 'Image Properties', 'wpam' ) ?></th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td width="150"><?php _e( 'Image', 'wpam' ) ?></td>
			<td><img src="<?php
				$url = wp_get_attachment_image_src($model->imagePostId);
				echo $url[0];?>" style="max-width: 200px; max-height: 200px;"/></td>
		</tr>
		<tr>
			<td><?php _e( 'Alt Text', 'wpam' ) ?></td>
			<td><?php echo $model->altText?></td>
		</tr>
		</tbody>
	</table>
	<?php } ?>
</div>
</div>