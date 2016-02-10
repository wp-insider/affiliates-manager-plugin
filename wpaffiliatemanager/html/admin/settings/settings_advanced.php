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
			<input type="text" size="60" name="affLandingPage" id="affLandingPage" value="<?php echo $this->viewData['request']['affLandingPage']?>" />
                        <p class="description"><?php echo sprintf( __( 'Your default landing page URL is <code>%s</code>. If you want to change to a different URL you can specify it here.', 'affiliates-manager' ), $home_url );?></p>
		</td>
	</tr>       
        
</table>