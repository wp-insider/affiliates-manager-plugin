<?php

// This widget will also highlight the labels below, assuming they're properly
// associated with the input being validated
// -jgh
require_once WPAM_BASE_DIRECTORY . "/html/widget_form_errors_panel.php";

$request = @$this->viewData['request'];

?>

<form action="<?php echo $this->viewData['postBackUrl']?>" method="post" id="mainForm">

	<div style="text-align: left;">
		<?php _e( '* Required fields', 'wpam' ) ?><br /><br />
		<table class="wpam_affiliate_application">
			<?php foreach ($this->viewData['affiliateFields'] as $field) { ?>
				<tr>
					<th><label for="_<?php echo $field->databaseField?>"><?php echo $field->name?><?php echo $field->required ? '&nbsp;*': '' ?></label></th>
					<td>
						<?php switch ($field->fieldType) {
							case 'string':
							case 'email':
							case 'number':
							case 'zipCode':
								?>
							<input type="text" size="20" id="_<?php echo $field->databaseField?>" name="_<?php echo $field->databaseField?>" value="<?php echo $request['_'.$field->databaseField]?>" />
							<?php break;
							case 'phoneNumber':?>
							<input type="text" size="20" id="_<?php echo $field->databaseField?>" name="_<?php echo $field->databaseField?>" value="<?php echo $request['_'.$field->databaseField]?>" />
							<?php break;
							case 'ssn':?>
							<input type="password" size="3" maxlength="3" id="_<?php echo $field->databaseField?>[0]" name="_<?php echo $field->databaseField?>[0]" value="<?php echo $request['_'.$field->databaseField][0]?>" /> -
							<input type="password" size="2" maxlength="2" id="_<?php echo $field->databaseField?>[1]" name="_<?php echo $field->databaseField?>[1]" value="<?php echo $request['_'.$field->databaseField][1]?>" /> -
							<input type="password" size="4" maxlength="4" id="_<?php echo $field->databaseField?>[2]" name="_<?php echo $field->databaseField?>[2]" value="<?php echo $request['_'.$field->databaseField][2]?>" />
							<?php break;
							case 'stateCode':?>
							<select id="_<?php echo $field->databaseField?>" name="_<?php echo $field->databaseField?>">
								<?php wpam_html_state_code_options($request['_'.$field->databaseField]) ?>
							</select>
							<?php break;
							case 'countryCode':?>
							<select id="_<?php echo $field->databaseField?>" name="_<?php echo $field->databaseField?>">
								<?php wpam_html_country_code_options($request['_'.$field->databaseField]) ?>
							</select>
							<?php break; default: break;
						}?>
					</td>
				</tr>
			<?php } //end foreach ?>

			<tr>
				<td></td>
				<td>
					<input type="checkbox" id="chkAgreeTerms" name="chkAgreeTerms" <?php echo (isset($request['chkAgreeTerms']) ? 'checked="checked"':'')?> />&nbsp;<label for="chkAgreeTerms" id="agreeTermsLabel">I have read and agree to the <a href="#" id="tncLink">Terms and Conditions</a></label>
					<span id="termsAgreeWarning" style="color: red; display: none"><br><?php _e( 'You must agree to the terms.', 'wpam' ) ?></span>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center"><input type="submit" name="submit" value="<?php _e( 'Submit Application', 'wpam' ) ?>" /></td>
			</tr>

		</table>
	</div>


</form>

<div id="tncDialog" style="display: none">
	<div id="termsBox" style="padding: 20px; width: auto; height: 380px; overflow: scroll; background-color: white; color: black; border: 1px solid black; white-space: pre-wrap;"><?php echo $this->viewData['tnc']?></div>
</div>
