<div class="wrap">

	 <h2><?php _e( 'Affiliate Confirmation', 'affiliates-manager' ) ?></h2>

	 <h3><?php _e( 'Contract Terms', 'affiliates-manager' ) ?></h3>

	<div>
			<br/>
			<table class="pure-table pure-table-bordered">
				<tr>
					<td style="font-weight: bold;">
						<?php _e( 'Commission Rate', 'affiliates-manager' ) ?>
					</td>
					<td>
						<?php echo esc_html(wpam_format_bounty($this->viewData['affiliate']->bountyType, $this->viewData['affiliate']->bountyAmount))?>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<br/>
							<strong><?php _e( 'Terms & Conditions', 'affiliates-manager' ) ?></strong><br/>
						<div id="termsBox" style="padding: 20px; max-height: 300px; overflow: scroll; background-color: white; color: black; border: 1px solid black; white-space: pre-wrap;"><?php echo $this->viewData['tnc']?></div>
						<br />
					</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align: center"><a class="button-primary" href="<?php echo esc_url($this->viewData['nextStepUrl'])?>"><?php _e( 'Agree to Terms', 'affiliates-manager' ) ?></a></td>
				</tr>
			</table>
	</div>

</div>