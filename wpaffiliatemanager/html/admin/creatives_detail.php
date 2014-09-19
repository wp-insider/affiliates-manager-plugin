<?php
$model = $this->viewData['creative'];

function formatType($type)
{
	switch ($type)
	{
		case 'image': return __( 'Image', 'wpam' );
		case 'text': return __( 'Text Link', 'wpam' );
		default: wp_die( __('Unknown creative type.', 'wpam' ) );
	}
}
?>


<style type="text/css">
	.ui-progressbar-value {
		background-image: url(<?php echo WPAM_URL . "/images/pbar-ani.gif"?>);
		height: 22px;
	}

</style>

<script type="text/javascript">

	jQuery(document).ready(function() {
		function jsonFinished(data)
		{
			if (data['status'] == 'OK')
			{
				window.location = '<?php echo admin_url('admin.php?page=wpam-creatives&action=viewDetail&creativeId='.$model->creativeId)?>';
			}
			else
			{
				//alert('fail: ' + data['status']);
				jQuery("#errorMsg").html(data['message']);
				jQuery("#dialog-error").dialog('open');
				jQuery("#dialog-loading").dialog('close');
			}
		}

		function showLoad()
		{
			jQuery("#dialog-loading").dialog("open");
			jQuery("#progressbar").show();

			jQuery(".ui-dialog-titlebar").hide();
		}

		function cancelClicked() {
			jQuery(this).dialog('close');
		}
		function activateConfirmClicked() {
			jQuery(this).dialog('close');
			jQuery.getJSON(
				ajaxurl,
				{
					'action' : 'wpam-ajax_request',
					'handler' : 'setCreativeStatus',
					'creativeId' : <?php echo $model->creativeId?>,
					'status' : 'active'
				},
				jsonFinished
			);
			showLoad();
		}
		function deactivateConfirmClicked() {
			jQuery(this).dialog('close');
			jQuery.getJSON(
				ajaxurl,
				{
					'action' : 'wpam-ajax_request',
					'handler' : 'setCreativeStatus',
					'creativeId' : <?php echo $model->creativeId?>,
					'status' : 'inactive'
				},
				jsonFinished
			);
			showLoad();
		}

		var activateButtons = [ {
				text : '<?php _e( 'Cancel', 'wpam' ) ?>',
				click : cancelClicked
			}, {
				text : '<?php _e( 'Yes, ACTIVATE.', 'wpam' ) ?>',
				click : activateConfirmClicked
			}
		];

		var declineButtons = [ {
			  text : '<?php _e( 'Cancel', 'wpam' ) ?>',
			  click : cancelClicked
			}, {
			  text : '<?php _e( 'Yes, DEACTIVATE.', 'wpam' ) ?>',
			  click : deactivateConfirmClicked
			}
		];

		jQuery("#dialog-confirm").dialog({
			resizable: false,
			height: 200,
			modal:true,
			draggable: false,
			autoOpen: false
		});
		jQuery("#dialog-error").dialog({
			resizable: false,
			height: 100,
			autoOpen: false,
			modal: true,
			draggable: false,
			buttons: [ {
				  text : '<?php _e( 'OK', 'wpam' ) ?>',
				  click : function() { jQuery(this).dialog('close'); }
			} ]
		});
		jQuery("#dialog-loading").dialog({
			resizable: false,
			height: 120,
			width: 500,
			closeOnEscape: false,
			modal:true,
			draggable:false,
			autoOpen:false
		});

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

		jQuery("#actButton").click(function() {
			jQuery("#actionSpan").html('<?php _e( 'ACTIVATE', 'wpam' ) ?>');
			jQuery("#dialog-confirm").dialog('option', 'buttons', activateButtons);
			jQuery("#dialog-confirm").dialog('open');
		});
		jQuery("#deactButton").click(function() {
			jQuery("#actionSpan").html('<?php _e( 'DEACTIVATE', 'wpam' ) ?>');
			jQuery("#dialog-confirm").dialog('option', 'buttons', declineButtons);
			jQuery("#dialog-confirm").dialog('open');
		});

		jQuery("#previewButton").click(function() {
			jQuery("#dialog-preview").dialog('open');
		});

	});
</script>

<div id="dialog-loading" style="display:none">
	<div style="text-align: center"><?php _e( 'Updating, please wait ... ', 'wpam' ) ?></div><br />
	<div id="progressbar" class="ui-progressbar-value">

	</div>
</div>
<div id="dialog-confirm" title="<?php _e( 'Are you sure?', 'wpam' ) ?>" style="display: none">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><?php _e( 'Are you sure you wish to', 'wpam' ) ?> <span id="actionSpan"></span> <?php _e( 'this creative?', 'wpam' ) ?></p>
</div>
<div id="dialog-error" title="<?php _e( 'Error', 'wpam' ) ?>" style="display: none">
	<p><?php _e( 'ERROR:', 'wpam' ) ?> <span id="errorMsg"></span></p>
</div>


<div id="dialog-preview" title="<?php _e( 'Preview', 'wpam' ) ?>" style="display: none">
<?php if ($model->type === 'image') { ?>
<a href="" title="<?php echo $model->altText?>"><img src="<?php
				$url = wp_get_attachment_image_src($model->imagePostId);
				echo $url[0];
?>" /></a>
<?php } else if ($model->type === 'text') { ?>
	<a href="" title="<?php echo $model->altText?>"><?php echo $model->linkText?></a>
<?php } ?>
	<div id="textLinkPreview">

	</div>
	<div id="imagePreview" style="display: none">

	</div>
</div>


<div class="wrap">

	<h2><?php _e( 'Creative:', 'wpam' ) ?> <?php echo $model->name?></h2>
	<?php if (isset($this->viewData['updateMessage'])) {?>
		<div class="updated">
			<p><?php echo $this->viewData['updateMessage']?></p>
		</div>
	<?php }?>

<br />
	<table class="widefat">
		<thead>
		<tr>
			<th width="150">
				<?php _e( 'Actions', 'wpam' ) ?>
			</th>
			<th>
				<?php if ($model->status === 'active') { ?>
					<a id="deactButton" class="button-secondary"><?php _e( 'Deactivate', 'wpam' ) ?></a>
				<?php } else if ($model->status === 'inactive') {?>
					<a id="actButton" class="button-secondary"><?php _e( 'Activate', 'wpam' ) ?></a>
				<?php } ?>

	  &nbsp;&nbsp;&nbsp;<a id="previewButton" class="button-secondary"><?php _e( 'Preview', 'wpam' ) ?></a>
	  &nbsp;&nbsp;&nbsp;<a id="editButton" class="button-secondary" href="<?php echo admin_url( "admin.php?page=wpam-creatives&action=edit&creativeId={$model->creativeId}" ) ?>"><?php _e( 'Edit', 'wpam' ) ?></a>
			</th>
		</tr>
		</thead>
	</table>
	<br/>

	<table class="widefat">
		<thead>
		<tr>
			<th colspan="2">
				<?php _e( 'General', 'wpam' ) ?>
			</th>
		</tr>
		</thead>
		<tbody>
			<tr>
				<td width="150">
					<?php _e( 'ID', 'wpam' ) ?>
				</td>
				<td><?php echo $model->creativeId?></td>
			</tr>
			<tr>
				<td><?php _e( 'Status', 'wpam' ) ?></td>
				<td><?php echo $model->status?></td>
			</tr>			
			<tr>
				<td><?php _e( 'Name', 'wpam' ) ?></td>
				<td><?php echo $model->name?></td>
			</tr>
			<tr>
				<td><?php _e( 'Type', 'wpam' ) ?></td>
				<td><?php echo formatType($model->type)?></td>
			</tr>
			<tr>
				<td><?php _e( 'Landing Page', 'wpam' ) ?></td>
				<td><?php echo site_url( $model->slug ) ?></td>
			</tr>

		</tbody>
	</table>
	<br/>
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
