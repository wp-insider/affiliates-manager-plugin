<?php
/**
 * @author John Hargrove
 * 
 * Date: May 26, 2010
 * Time: 11:36:35 PM
 */

class WPAM_OutputCleaner
{
	public function cleanRequest($request)
	{
		$requestClean = array();
		foreach ($request as $ck => $cv)
		{
			if (is_array($request[$ck]))
			{
				$requestClean[$ck] = $this->cleanRequest($cv);
			}
			else
			{
				$requestClean[$ck] = stripslashes($cv);
			}
		}
		return $requestClean;
	}
}
