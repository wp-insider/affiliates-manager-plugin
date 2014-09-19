<div class="aff-wrap">
<?php
include WPAM_BASE_DIRECTORY . "/html/affiliate_cp_nav.php";
?>
<div class="wrap">


	<table class="widefat">
		<thead>
		<tr>
			<th colspan="2"><?php _e( 'Account Summary', 'wpam' ) ?></th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td width="150"><?php _e( 'Balance', 'wpam' ) ?></td>
			<td style="font-size: 1.5em; padding: 10px"><?php echo wpam_format_money($this->viewData['accountStanding'])?></td>
		</tr>
		<tr>
			<td><?php _e( 'Commission Rate', 'wpam' ) ?></td>
			<td><?php echo $this->viewData['commissionRateString']?></td>
		</tr>
		</tbody>
	</table><br /><br/>

	<table>
		<tr>
			<td>
				<table class="widefat" style="width: 300px;">
					<thead>
					<tr>
						<th colspan="2"><?php _e( 'Today', 'wpam' ) ?></th>
					</tr>
					</thead>
					<tbody>
					<tr>
						<td>
							<div class="summaryPanel">
								<?php if (get_option (WPAM_PluginConfig::$AffEnableImpressions)) { ?>
								<div class="summaryPanelLine">
									<div style="width: 80px;" class="summaryPanelLineValue"><?php echo $this->viewData['todayImpressions']?></div>
									<div class="summaryPanelLineLabel"><?php _e( 'Impressions', 'wpam' ) ?></div>
								</div>
								<?php } ?>
								<div class="summaryPanelLine">
									<div style="width: 80px;" class="summaryPanelLineValue"><?php echo $this->viewData['todayVisitors']?></div>
									<div class="summaryPanelLineLabel"><?php _e( 'Visitors', 'wpam' ) ?></div>
								</div>
								<div class="summaryPanelLine">
									<div style="width: 80px;" class="summaryPanelLineValue"><?php echo $this->viewData['todayClosedTransactions']?></div>
									<div class="summaryPanelLineLabel"><?php _e( 'Closed Transactions', 'wpam' ) ?></div>
								</div>
								<div class="summaryPanelLine">
									<div style="width: 80px;" class="summaryPanelLineValue"><?php echo $this->viewData['todayRevenue']?></div>
									<div class="summaryPanelLineLabel"><?php _e( 'Revenue', 'wpam' ) ?></div>
								</div>
							</div>
						</td>
					</tr>
					</tbody>
				</table>
			</td>
			<td>
				<table class="widefat" style="width: 300px">
					<thead>
					<tr>
						<th colspan="2"><?php _e( 'This Month', 'wpam' ) ?></th>
					</tr>
					</thead>
					<tbody>
					<tr>
						<td>
							<div class="summaryPanel">
								<?php if (get_option (WPAM_PluginConfig::$AffEnableImpressions)) { ?>
								<div class="summaryPanelLine">
									<div style="width: 80px;" class="summaryPanelLineValue"><?php echo $this->viewData['monthImpressions']?></div>
									<div class="summaryPanelLineLabel"><?php _e( 'Impressions', 'wpam' ) ?></div>
								</div>
								<?php } ?>
								<div class="summaryPanelLine">
									<div style="width: 80px;" class="summaryPanelLineValue"><?php echo $this->viewData['monthVisitors']?></div>
									<div class="summaryPanelLineLabel"><?php _e( 'Visitors', 'wpam' ) ?></div>
								</div>
								<div class="summaryPanelLine">
									<div style="width: 80px;" class="summaryPanelLineValue"><?php echo $this->viewData['monthClosedTransactions']?></div>
									<div class="summaryPanelLineLabel"><?php _e( 'Closed Transactions', 'wpam' ) ?></div>
								</div>
								<div class="summaryPanelLine">
									<div style="width: 80px;" class="summaryPanelLineValue"><?php echo ($this->viewData['monthRevenue'])?></div>
									<div class="summaryPanelLineLabel"><?php _e( 'Revenue', 'wpam' ) ?></div>
								</div>
							</div>
						</td>
					</tr>
					</tbody>
				</table>

			</td>
		</tr>
	</table>


</div>
</div>