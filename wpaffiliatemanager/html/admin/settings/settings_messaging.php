<script type="text/javascript" >
jQuery(function($) {
	var messageEditorCurrentMessageContent;
	var messageEditorCurrentMessageName;

	$(".wpam-js-edit-message").click(function() {
		messageEditorCurrentMessageContent = $(this).siblings('input[type=hidden][name*=content]');
		messageEditorCurrentMessageName = $(this).siblings('input[type=hidden][name*=name]');

		$("#messageEditorTextArea").text(messageEditorCurrentMessageContent.val());
		$("#messageEditorUse").text($(this).siblings('input[type=hidden][name*=use]').val());
		$("#messageEditorStatus").html('Editing: '+messageEditorCurrentMessageName.val());
		$("#messageEditorDialog").dialog('open');
	});

	$("#messageEditorDialog").dialog({
		resizable: true,
		height: 480,
		width: 640,
		modal: true,
		draggable: true,
		autoOpen: false,
		resize: function (event, ui) {
			$("#messageEditorTextArea").css('height', ui.size.height-(480-225));
		},
		buttons: [ {
				  text : 'Cancel',
				  click : function() { jQuery(this).dialog('close'); }
			 }, {
				  text : 'OK',
				  click : function() {
				$(this).dialog('close');
				messageEditorCurrentMessageContent.val($("#messageEditorTextArea").val());
				messageEditorCurrentMessageContent
					.closest('tr')
					.children('td')
					.eq(1)
					.css('color', 'red')
					.css('font-weight', 'bold')
					.children('span')
					.show();
				$("#messageEditorModifiedWarning").slideDown(500);
			}
		} ]
	});
});
</script>

<style type="text/css">
	.dragging {
		background-color: #efe;
	}
	.dragging td {

	}
	.showDragHandle {
		background-image: url(<?php echo WPAM_URL."/images/move_grip_icon.jpg"?>);
		background-repeat: no-repeat;
		background-position: center center;
		cursor: move;
	}
</style>


<p>
	<?php _e( 'These settings allow you to control text that is displayed to affiliates at various points in the affiliate plug-in, as well as e-mails that are sent.', 'wpam' ) ?>
</p>

<div id="messageEditorDialog" style="display: none">
	<div id="messageEditorStatus" style="font-weight: bold"></div>
	<div style="margin-top: 6px"><?php _e( 'Use:', 'wpam' ) ?></div>
	<div id="messageEditorUse" style="border: 1px solid #e0e0e0; height: 50px; overflow:hidden;">

	</div>
	<div style="margin-top: 6px"><?php _e( 'Content:', 'wpam' ) ?></div>
	<textarea id="messageEditorTextArea" name="messageEditorTextArea" style="width: 100%; height: 225px"></textarea>

</div>

<div>
	<table class="widefat">
		<thead>
		<tr>
			<th width="50"></th>
			<th width="250"><?php _e( 'Name', 'wpam' ) ?></th>
			<th width="85"><?php _e( 'Type', 'wpam' ) ?></th>
			<th width="300"><?php _e( 'Use', 'wpam' ) ?></th>
			<th><?php _e( 'Content', 'wpam' ) ?></th>
		</tr>
		</thead>
		<?php foreach ($this->viewData['messages'] as $message) { ?>
		<tr>
			<td>
				<img class="wpam-js-edit-message wpam-action-icon wpam-edit-icon" src="<?php echo $ICON_EDIT?>" />
				<input type="hidden" name="messages[<?php echo $message->name?>][content]" value="<?php echo htmlentities($message->content)?>" />
				<input type="hidden" name="messages[<?php echo $message->name?>][name]" value="<?php echo $message->name?>" />
				<input type="hidden" name="messages[<?php echo $message->name?>][modified]" value="0" />
				<input type="hidden" name="messages[<?php echo $message->name?>][use]" value="<?php echo $message->use?>" />
			</td>
			<td style="white-space: nowrap"><?php echo $message->name?><span style="display: none"> *</span>
			</td>
			<td style="white-space: nowrap;">
				<img src="<?php
					if ($message->type == 'web')
						echo WPAM_URL . "/images/icon_world.gif";
					else if ($message->type == 'email')
						echo WPAM_URL . "/images/icon_mail.gif";
				?>" style="float:left; margin-right: 4px;" />
				<?php echo strtoupper($message->type)?>
			</td>
			<td><?php echo $message->use?></td>

			<td><em><?php
				$encodedContent = htmlentities($message->content);
				if (strlen($encodedContent) < 520)
					echo $encodedContent;
				else
					echo sprintf( __( '%s(More)', 'wpam' ), substr($encodedContent, 0, 500) );
			?></em></td>
		</tr>
		<?php } ?>
	</table>
</div>
<div id="messageEditorModifiedWarning" style="display: none; padding: 20px;">
	<span style="color: red"><?php _e( "* Some messages have been modified. These will <strong>NOT</strong> be saved to the database until you click 'Save Settings' button below.", 'wpam' ) ?></span>
</div>
