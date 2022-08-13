<?php
$home_url = home_url('/');
?>
<table class="form-table">
	<tr>
		<th width="200">
			<label for="affLandingPage">
				<?php _e('Default Landing Page', 'affiliates-manager');?>
			</label>
		</th>
		<td>
			<input type="text" size="60" name="affLandingPage" id="affLandingPage" value="<?php echo esc_url($this->viewData['request']['affLandingPage'])?>" />
                        <p class="description"><?php echo sprintf( __( 'Your default landing page URL is <code>%s</code>. If you want to change to a different URL you can specify it here.', 'affiliates-manager' ), $home_url );?></p>
		</td>
	</tr>       
        <tr>
		<th width="200">
			<label for="disableOwnReferrals">
				<?php _e('Disable Own Referrals', 'affiliates-manager');?>
			</label>
		</th>
		<td>
			<input type="checkbox" id="disableOwnReferrals" name="disableOwnReferrals" <?php
			if ($this->viewData['request']['disableOwnReferrals'])
				echo 'checked="checked"';
			?>/><p class="description"><?php _e('If checked, your affiliates will not be able to earn a commission on their own purchases.', 'affiliates-manager');?></p>
		</td>
	</tr>
        <tr>
		<th width="200">
			<label for="autoDeleteWPUserAccount">
				<?php _e('Automatically Delete WordPress Account', 'affiliates-manager');?>
			</label>
		</th>
		<td>
			<input type="checkbox" id="autoDeleteWPUserAccount" name="autoDeleteWPUserAccount" <?php
			if ($this->viewData['request']['autoDeleteWPUserAccount'])
				echo 'checked="checked"';
			?>/><p class="description"><?php _e('If checked, when an affiliate account is deleted the WordPress user account for it will be automatically deleted.', 'affiliates-manager');?></p>
		</td>
	</tr>
        <tr>
		<th width="200">
			<label for="enableRegFormAnchor">
				<?php _e('Enable Registration Form Anchor', 'affiliates-manager');?>
			</label>
		</th>
		<td>
			<input type="checkbox" id="enableRegFormAnchor" name="enableRegFormAnchor" <?php
			if ($this->viewData['request']['enableRegFormAnchor'])
				echo 'checked="checked"';
			?>/><p class="description"><?php _e('If checked, the affiliate will be taken to the registration form anchor within the page after the form is submitted.', 'affiliates-manager');?></p>
		</td>
	</tr>
        <tr>
		<th width="200">
			<label for="disableFrontEndAffRegistration">
				<?php _e('Disable Front-end Affiliate Registration', 'affiliates-manager');?>
			</label>
		</th>
		<td>
			<input type="checkbox" id="disableFrontEndAffRegistration" name="disableFrontEndAffRegistration" <?php
			if ($this->viewData['request']['disableFrontEndAffRegistration'])
				echo 'checked="checked"';
			?>/><p class="description"><?php _e('If checked, visitors will not be able to sign up on the affiliate registration page. This allows you to selectively create affiliate accounts from the admin dashboard.', 'affiliates-manager');?></p>
		</td>
	</tr>
        <tr>
		<th width="200">
			<label for="showRegTncChk">
				<?php _e('Show Terms and Conditions', 'affiliates-manager');?>
			</label>
		</th>
		<td>
			<input type="checkbox" id="showRegTncChk" name="showRegTncChk" <?php
			if ($this->viewData['request']['showRegTncChk'])
				echo 'checked="checked"';
			?>/><p class="description"><?php _e('If checked, a terms and conditions checkbox will be shown on the affiliate registration page.', 'affiliates-manager');?></p>
		</td>
	</tr>
        <tr>
		<th width="200">
			<label for="useIPReferralTrack">
				<?php _e('Use IP Address for Referral Tracking', 'affiliates-manager');?>
			</label>
		</th>
		<td>
			<input type="checkbox" id="useIPReferralTrack" name="useIPReferralTrack" <?php
			if ($this->viewData['request']['useIPReferralTrack'])
				echo 'checked="checked"';
			?>/><p class="description"><?php _e('If checked, a fallback method will be used to track referrals with IP addresses. This can be useful if the default cookie-based tracking fails.', 'affiliates-manager');?></p>
		</td>
	</tr>
</table>