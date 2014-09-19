<script type="text/javascript">
jQuery(function($){

	var dialog = {
		resizable: false,
		height: 500,
		width: 500,
		autoOpen: false,
		modal: true,
		draggable: false,
		buttons: [ {
			  text : 'OK',
			  click : function() { $(this).dialog('close'); }
		} ]
	};

	$("#Login_Page").dialog(dialog);

	$("#LoginPage").click(function()
        {
                    $("#Login_Page").dialog('open');
        });

});
</script>

<div id="Login_Page" style="display: none;">
	This is the URL of Affiliate Login page
</div>

<table class="form-table">
	<tr>
		<th width="200">
			<label for="affLoginPage">
				Login Page
			</label>
                        <img id="LoginPage" style="cursor: pointer;" src="<?php echo WPAM_URL . "/images/info_icon.png"?>" />
		</th>
		<td>
			<input type="text" size="60" name="affLoginPage" id="affLoginPage" value="<?php echo $this->viewData['request']['affLoginPage']?>" />
		</td>
	</tr>       
        
</table>