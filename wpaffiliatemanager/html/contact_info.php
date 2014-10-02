<?php

require_once WPAM_BASE_DIRECTORY . "/html/widget_form_errors_panel.php";

if( ! isset( $model ) && isset( $this->viewData['affiliate'] ) ){
	$model = $this->viewData['affiliate'];
}

?>
<div id="email_help" style="display: none;">
<p>
<?php
if( is_admin() ){
	_e( "Changing the affiliate's email address will not change their WordPress Affiliate Manager login, which may be based on the email address they used when they signed up.", 'wpam' );
}else{
	_e( 'Changing your email address will not change your WordPress Affiliate Manager login, which may be based on the email address you used when you signed up.', 'wpam' );
}
?>
</p>
</div>

	<form method="post" id="infoForm" class="pure-form">
		<table class="pure-table">
			<thead>
				<tr>
					<th colspan="2"><?php echo isset( $this->viewData['infoLabel'] ) ? $this->viewData['infoLabel'] : __( 'Contact Information', 'wpam' ); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php
				foreach ($this->viewData['affiliateFields'] as $field){
					$value = '';
					//prefer request data
					if ( isset( $this->viewData['request']['_' . $field->databaseField] ) ) {
						$value = $this->viewData['request']['_' . $field->databaseField];
					} elseif ( isset( $model ) ) {
						if( $field->type == 'base' )
							$value = $model->{$field->databaseField};
						else
							$value =  $model->userData[$field->databaseField];
					} 
				?>
				<tr>
					<td><label for="_<?php echo $field->databaseField; ?>">
						<?php echo $field->name; ?>
						</label>
						<?php
						echo $field->required ? '&nbsp;*': '';
						if( is_admin() )
							echo $field->type == 'custom' ? '&nbsp;+': '';
						if ( $field->fieldType == 'email' ){ ?>
							<img id="emailInfo" style="cursor: pointer;" src="<?php echo WPAM_URL . "/images/info_icon.png"?>" />
                                                <?php } ?>
					</td>
					<td>
						<?php switch ($field->fieldType) {
							case 'string':
							case 'email':
							case 'number':
							case 'zipCode':
								?>
							<input type="text" size="20" id="_<?php echo $field->databaseField; ?>" name="_<?php echo $field->databaseField; ?>" value="<?php echo $value; ?>" />
							<?php break;
							case 'phoneNumber':?>							
							<input type="text" size="20" maxlength="14" id="_<?php echo $field->databaseField; ?>" name="_<?php echo $field->databaseField; ?>" value="<?php echo $value; ?>" />
							<?php break;
							case 'stateCode':?>
							<select id="_<?php echo $field->databaseField; ?>" name="_<?php echo $field->databaseField; ?>">
							<?php wpam_html_state_code_options($value); ?>
							</select>
							<?php break;
							case 'countryCode':?>
							<select id="_<?php echo $field->databaseField; ?>" name="_<?php echo $field->databaseField; ?>">
							<?php wpam_html_country_code_options($value); ?>
							</select>
							<?php break; default: break;
						}?>
					</td>
				</tr>
				<?php if( !is_admin() && $field->fieldType == 'email' && isset( $this->viewData['newEmail'] ) ){ ?>
					<tr><td colspan="2"><?php printf( __('There is a pending change of your e-mail to <code>%1$s</code>. <a href="%2$s">Cancel</a>', 'wpam' ), $this->viewData['newEmail']['newemail'], esc_url( self_admin_url( 'profile.php?dismiss=' . $this->viewData['userId'] . '_new_email' ) ) ); ?></td></tr>
                                <?php } ?>
                                <?php } ?>
			</tbody>
			<?php if ( ! isset( $model ) || ( ! $model->isPending() && ! $model->isBlocked() && ! $model->isDeclined() ) ): ?>
			<thead>
				<tr>
					<th colspan="2"><?php _e( 'Payment Details', 'wpam' ) ?></th>
				</tr>
			</thead>
			<tbody>
		<?php if ( isset( $this->viewData['paymentMethods'] ) ): ?>
		<tr>
				<td><label for="ddPaymentMethod"><?php _e( 'Method', 'wpam' ) ?></label> *</td>
				<td><select id="ddPaymentMethod" name="ddPaymentMethod">
					<?php foreach ($this->viewData['paymentMethods'] as $key => $val) {
						$selected_html = $this->viewData['paymentMethod'] == $key ? ' selected="selected"' : '';			
						echo "<option value='{$key}'{$selected_html}>{$val}</option>";
					}?>
				</select></td>
		</tr>
		<tr id="rowPaypalEmail"<?php if( $this->viewData['paymentMethod'] != 'paypal' ) echo ' style="display: none;"'; ?>>
			<td><label for="txtPaypalEmail"><?php _e( 'PayPal E-Mail Address', 'wpam' ) ?></label> *</td>
			<td>
				<input id="txtPaypalEmail" type="text" name="txtPaypalEmail" size="30" value="<?php echo $this->viewData['paypalEmail']?>"/>
			</td>
		</tr>
		<?php endif; ?>
		<?php if ( is_admin() ): ?>					  
		<tr>
			<td>
				<label for="ddBountyType"><?php _e( 'Bounty Type *', 'wpam' ) ?></label>
			</td>
			<td>
				<select id="ddBountyType" name="ddBountyType">
		<?php

					$select = array( 'percent' => __( 'Percentage of Sales', 'wpam' ),
									 'fixed' => __( 'Fixed Amount per Sale', 'wpam' ) );

		$selected = isset( $this->viewData['bountyType'] ) ? $this->viewData['bountyType'] : NULL;
		foreach ( $select as $value => $name ) {
			$selected_html = $value == $selected ? ' selected="selected"' : '';
			echo "<option value='{$value}'{$selected_html}>{$name}</option>\n";
		}

		$currency = WPAM_MoneyHelper::getDollarSign();

		$label = isset( $this->viewData['bountyType'] ) && $this->viewData['bountyType'] == 'fixed' ?
			sprintf( __( 'Bounty Rate (%s per Sale) *', 'wpam' ), $currency) : __( 'Bounty Rate (% of Sale) *', 'wpam' );

		$bountyAmount = isset( $this->viewData['bountyAmount'] ) ? $this->viewData['bountyAmount'] : '';

		?>
				</select>
			</td>
		</tr>
		<tr>
			<td><label id='lblBountyAmount' for='txtBountyAmount'><?php echo $label; ?></label></td>
			<td><input type='text' id='txtBountyAmount' name='txtBountyAmount' size='5' value='<?php echo $bountyAmount; ?>'/></td>
		</tr>
		<?php endif; //is_admin ?>
		<?php endif; //not pending, blocked or declined ?>
			</tbody>
		</table>
		<p><?php _e( '* Required fields', 'wpam' ) ?></p>
		<?php if( is_admin() ){ ?>
			<p><?php _e( '+ Custom fields', 'wpam' ) ?></p>
                <?php } ?>
		<div class="wpam-save-profile">			
			<input type="hidden" name="action" value="saveInfo"/>
                        <input type="submit" id="saveInfoButton" class="pure-button pure-button-active" name="wpam-add-affiliate" value="<?php echo isset( $this->viewData['saveLabel'] ) ? $this->viewData['saveLabel'] : __( 'Save Changes', 'wpam' ); ?>" />
		</div>
	    </form>
