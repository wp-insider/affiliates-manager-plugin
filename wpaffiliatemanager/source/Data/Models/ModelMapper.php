<?php
/**
 * @author John Hargrove
 * 
 * Date: May 20, 2010
 * Time: 9:08:43 PM
 */

class WPAM_Data_Models_ModelMapper
{
    public function map($sourceObject, $destObject, $strict = TRUE)
	{
		foreach (((array)$sourceObject) as $key => $value)
		{
			if (defined('WPAM_PHP53') && WPAM_PHP53)
			{
				if (!$strict || property_exists($destObject, $key))
				{
					$destObject->{$key} = $value;
				}
				else
				{
					wp_die("Model mapping error. Could not map key {$key}");
				}
			}
			else
			{
				if (!$strict || array_key_exists($key, $destObject))
				{
					$destObject->{$key} = $value;
				}
				else
				{
					wp_die( sprintf( __('Model mapping error.  Could not map key %s.', 'wpam' ), $key) );
				}
			}
		}
    }
}
