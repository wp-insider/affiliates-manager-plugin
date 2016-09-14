<style type="text/css">
DIV#previewImageDiv.loading {
  background: url(<?php echo WPAM_URL . "/images/ajaxSpinner.gif"?>) no-repeat center center;
}
</style>


<script type="text/javascript">
	jQuery(function($) {
		function updateTypeDivs()
		{
			var setting = $("#ddType").val();
			if (setting == 'image')
			{
				$("#imageDiv").show();
				$("#textLinkDiv").hide();
			}
			else if (setting == 'text')
			{
				$("#imageDiv").hide();
				$("#textLinkDiv").show();
			}
			else
			{
				$("#imageDiv").hide();
				$("#textLinkDiv").hide();
			}
		}

		function updatePreview()
		{
			if (!isNaN($("#ddFileImage").val()) && $("#ddFileImage").val().length != 0)
			{
				$("#fileImageNew").val('');
				$('#imagePreview').show();
				$.getJSON(
					ajaxurl,
					{
						'action' : 'wpam-ajax_request',
						'handler' : 'getPostImageElement',
						'postId' : $("#ddFileImage").val()
					},
					function(result) {
						if (result['status'] == 'OK')
						{
							$("#previewError").html("");
							$("#previewImageDiv").addClass("loading");
							$("#previewImageElement").hide();
							$("#previewImageElement").attr("src", result['data']);
						}
						else
						{
							$("#previewError").html(result['message']);
						}
					}
				);
			} else {
				$('#imagePreview').hide();
			}

		}
		
		$("#ddType").change(updateTypeDivs);
		$("#previewImageElement").load(function() {
			$("#previewImageDiv").removeClass("loading");
			$("#previewImageElement").fadeIn();
		});

		$("#ddFileImage").change(updatePreview);

		updateTypeDivs();
		updatePreview();

		var dialog = {
		  resizable: false,
		  height: 300,
		  width: 500,
		  autoOpen: false,
		  modal: true,
		  draggable: false,
		  buttons: [ {
			  text : '<?php _e( 'OK', 'affiliates-manager' ) ?>',
			  click : function() { $(this).dialog('close'); }
			} ]
		};
	
		$("#image_help").dialog(dialog);

		$("#imageInfo").click(function() {
			$("#image_help").dialog('open');
		});

		$('#fileImageNew').change(function(){
			$('#imagePreview').hide();
			$("#ddFileImage").val('');
		});

	});
</script>

<div id="image_help" style="display: none;">
        <p>
<?php _e('This list contains images from the media library. If you upload a new image it is added to the media library and you can reuse images on multiple creatives by selecting it from this list. If a new image file is added, it will be uploaded and will override the currently selected media library image for this creative link. However, the old image will still remain in the media library for future use.', 'affiliates-manager' ) ?>
       </p>
</div>

<div class="wrap">
	 <h2><?php echo $this->viewData['request']['action'] == 'edit' ? __( 'Edit', 'affiliates-manager' ) : __( 'New', 'affiliates-manager' ) ?> <?php _e( 'Creative', 'affiliates-manager' ) ?></h2>

<?php
require_once WPAM_BASE_DIRECTORY . "/html/widget_form_errors_panel.php";
$home_url = home_url('/');
$aff_landing_page = get_option(WPAM_PluginConfig::$AffLandingPageURL);
if(isset($aff_landing_page) && !empty($aff_landing_page)){
    $home_url = trailingslashit($aff_landing_page);
}
?>
	
<form method="post" action="admin.php?page=wpam-creatives" enctype="multipart/form-data">

	<input type="hidden" name="action" value="<?php echo $this->viewData['request']['action']?>" />
	<input type="hidden" name="post" value="true"/>
	<?php if ($this->viewData['request']['action'] === 'edit') { ?>
		<input type="hidden" name="creativeId" value="<?php echo $this->viewData['request']['creativeId']?>" />
	<?php } ?>

	<div id="mainForm" style="max-width: 1000px">
		<table class="widefat">
			<thead>
			<tr>
				<th colspan="2"><?php _e( 'General', 'affiliates-manager' ) ?></th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td>
					<label for="txtName">
						<?php _e( 'Name', 'affiliates-manager' ) ?>
					</label>
				</td>
				<td>
					<input type="text" id="txtName" name="txtName" size="30" value="<?php echo isset($this->viewData['request']['txtName']) ? $this->viewData['request']['txtName'] : ''; ?>" />
				</td>
			</tr>

			<tr>
				<td>
					<label for="txtSlug"><?php _e( 'Landing Page', 'affiliates-manager' ) ?></label>
				</td>
				<td id="landing-page-slug">
					<?php echo esc_url($home_url) ?><input type="text" id="txtSlug" name="txtSlug" size="30" value="<?php echo isset($this->viewData['request']['txtSlug']) ? $this->viewData['request']['txtSlug'] : ''; ?>" />
				</td>
			</tr>			
			<tr>
				<td width="200"><label for="ddType"><?php _e( 'Type', 'affiliates-manager' ) ?></label></td>
				<td>
					<select id="ddType" name="ddType" style="width:150px">
						<?php foreach ($this->viewData['creativeTypes'] as $value => $name) { ?>
				<option value="<?php echo $value?>" <?php echo isset($this->viewData['request']['ddType']) && $this->viewData['request']['ddType'] === $value ? 'selected="selected"' : ''; ?>><?php echo $name?></option>
						<?php } ?>
					</select>
				</td>
			</tr>

			</tbody>
		</table>
	</div>

	<br/>

	<div id="imageDiv" style="display: none; width: 700px; ">
		<table class="widefat">
			<theaD>
			<tr>
				<th colspan="2"><?php _e( 'Image Parameters', 'affiliates-manager' ) ?></th>
			</tr>
			</theaD>
			<tbody>
			<tr>
				<td width="200">
					<label for="fileImageNew"><?php _e( 'New Image File', 'affiliates-manager' ) ?></label>
				</td>
				<td>
					<input type="file" id="fileImageNew" name="fileImageNew" />
				</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;<strong>or</strong></td>
			</tr>
			<tr>
				<td width="200">
					<label for="ddFileImage"><?php _e( 'Image File from Media Library', 'affiliates-manager' ) ?></label>
					<img id="imageInfo" style="cursor: pointer;" src="<?php echo WPAM_URL."/images/info_icon.png"?>"/>
				</td>
				<td>
					<select id="ddFileImage" name="ddFileImage">
						<?php foreach ($this->viewData['images'] as $imageId => $image) {?>
					<option value="<?php echo $imageId?>" <?php echo (isset($this->viewData['request']['ddFileImage']) && $this->viewData['request']['ddFileImage'] == $imageId ? 'selected="selected"' : '')?>><?php echo $image?></option>
						<?php } ?>
					</select>&nbsp;&nbsp;&nbsp;&nbsp;
					<div id="imagePreview" style="display: none;">
					<span><?php _e( 'Preview:', 'affiliates-manager' ) ?></span> <span style="color: red" id="previewError"></span>
					<div id="previewImageDiv" style="margin: 5px; width: 400px; height: 300px; padding: 20px; border: 1px solid #ddd;">
						<img id="previewImageElement" style="max-width: 400px; max-height: 300px;"/>
					</div>
					</div>
				</td>
			</tr>

			<tr>
				<td width="200">
					<label for="txtImageAltText">
						<?php _e( 'Alt Text', 'affiliates-manager' ) ?>
					</label>
				</td>
				<td>
					<input id="txtImageAltText" name="txtImageAltText" type="text" size="40" value="<?php echo isset($this->viewData['request']['txtImageAltText']) ? $this->viewData['request']['txtImageAltText'] : ''; ?>" />
				</td>
			</tr>
			</tbody>
		</table>

	</div>

	<div id="textLinkDiv" style="display: none; width: 700px;">
		<table class="widefat">
			<thead>
			<tr>
				<th colspan="2"><?php _e( 'Text Link Parameters', 'affiliates-manager' ) ?></th>
			</tr>
			</thead>

			<tbody>
			<tr>
				<td width="200">
					<label for="txtLinkText">
						<?php _e( 'Link Text', 'affiliates-manager' ) ?>
					</label>
				</td>
				<td>
					<input id="txtLinkText" name="txtLinkText" type="text" size="30" value="<?php echo isset($this->viewData['request']['txtLinkText']) ? $this->viewData['request']['txtLinkText'] : ''; ?>"/>
				</td>
			</tr>

			<tr>
				<td width="200">
					<label for="txtAltText">
						<?php _e( 'Alt Text', 'affiliates-manager' ) ?>
					</label>
				</td>
				<td>
					<input id="txtAltText" name="txtAltText" type="text" size="40" value="<?php echo isset($this->viewData['request']['txtAltText']) ? $this->viewData['request']['txtAltText'] : ''; ?>"/>
				</td>
			</tr>
			</tbody>
		</table>

	</div>
<br />
	<div style="width: 700px; text-align: center">
	<input class="button-primary" type="submit" id="btnSubmit" name="btnSubmit" value="<?php _e( 'Save Creative', 'affiliates-manager' ) ?>" />	
	</div>


</form>

</div>