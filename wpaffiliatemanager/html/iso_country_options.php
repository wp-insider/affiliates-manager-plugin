	<option value="--"></option>

	<?php
	foreach (WPAM_Validation_CountryCodes::$countryCodes as $code => $name)
	{
		echo '<option value="'.$code.'"';
		if ($this->viewData['request']['country'] == $code)
			echo ' selected="selected"';
		echo '>'.$name.'</option>';
	}
	?>
