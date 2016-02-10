<table class="form-table">
	<tr>
		<th width="200">
			<label for="affLoginPage">
				<?php _e('Login Page', 'affiliates-manager');?>
			</label>                       
		</th>
		<td>
			<input type="text" size="60" name="affLoginPage" id="affLoginPage" value="<?php echo $this->viewData['request']['affLoginPage']?>" />
                        <p class="description"><?php _e('This is the URL of Affiliate Login page', 'affiliates-manager');?></p>
		</td>
	</tr>       
        
</table>