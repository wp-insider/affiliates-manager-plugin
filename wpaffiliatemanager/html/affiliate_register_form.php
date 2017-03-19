<?php

// This widget will also highlight the labels below, assuming they're properly
// associated with the input being validated
// -jgh
require_once WPAM_BASE_DIRECTORY . "/html/widget_form_errors_panel.php";

$request = @$this->viewData['request'];

?>

<form action="<?php echo $this->viewData['postBackUrl']?>" method="post" id="mainForm" class="pure-form">


		<?php _e( '* Required fields', 'affiliates-manager' ) ?><br /><br />
		<table class="pure-table">
			<?php foreach ($this->viewData['affiliateFields'] as $field) { ?>
				<tr>
					<th><label for="_<?php echo $field->databaseField?>"><?php _e( $field->name, 'affiliates-manager' ) ?><?php echo $field->required ? '&nbsp;*': '' ?></label></th>
					<td>
						<?php switch ($field->fieldType) {
                                                        case 'email':
                                                            $email = $request['_'.$field->databaseField];
                                                            if(is_user_logged_in()){
                                                                $current_user = wp_get_current_user();
                                                                $email = $current_user->user_email;
                                                                ?>
                                                                <input type="text" size="20" id="_<?php echo $field->databaseField?>" name="_<?php echo $field->databaseField?>" value="<?php echo $email?>" readonly />
                                                                <p class="wpam_registration_input_help_text"><?php _e('This is the email address associated with your currently logged in WordPress user account.', 'affiliates-manager')?></p>
                                                                <p class="wpam_registration_input_help_text"><?php _e('If you want to use a different email address, log out of your WordPress account then try a new registration.', 'affiliates-manager')?></p>
                                                                <?php
                                                            }
                                                            else{
                                                            ?>
                                                            <input type="text" size="20" id="_<?php echo $field->databaseField?>" name="_<?php echo $field->databaseField?>" value="<?php echo $email?>" />
                                                            <?php
                                                            }
                                                             break;
							case 'string':
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
				<td colspan="2">
					<input type="checkbox" id="chkAgreeTerms" name="chkAgreeTerms" <?php echo (isset($request['chkAgreeTerms']) ? 'checked="checked"':'')?> />&nbsp;<label for="chkAgreeTerms" id="agreeTermsLabel"><?php _e('I have read and agree to the', 'affiliates-manager' ) ?> <a target="_blank" href="<?php echo get_option( WPAM_PluginConfig::$AffTncPageURL );?>"><?php _e('Terms and Conditions', 'affiliates-manager' ) ?></a></label>
					<span id="termsAgreeWarning" style="color: red; display: none"><br><?php _e( 'You must agree to the terms.', 'affiliates-manager' ) ?></span>
				</td>
			</tr>
		</table>
                <?php 
                $output = apply_filters( 'wpam_before_registration_submit_button', '');
                if(!empty($output)){
                    echo $output;
                }
                ?>
                <div class="wpam-registration-form">
                    <input type="submit" name="submit" value="<?php _e( 'Submit Application', 'affiliates-manager' ) ?>" class="pure-button pure-button-active" />
                </div>
</form>

<div id="tncDialog" style="display: none">
	<div id="termsBox" style="padding: 20px; width: auto; height: 380px; overflow: scroll; background-color: white; color: black; border: 1px solid black; white-space: pre-wrap;"><?php echo $this->viewData['tnc']?></div>
</div>
