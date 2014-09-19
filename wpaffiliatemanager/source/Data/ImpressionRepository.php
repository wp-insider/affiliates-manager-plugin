<?php
/**
 * @author Element Green
 * 
 * Date: Sep 2, 2014
 * Time: 11:07:12 PM
 */

require_once WPAM_BASE_DIRECTORY . "/source/Data/Models/ImpressionModel.php";
class WPAM_Data_ImpressionRepository extends WPAM_Data_GenericRepository
{
	public function getImpressionsForRange($dateStart = NULL, $dateEnd = NULL, $affiliateId = NULL)
	{
		$where = array();

		if ($dateStart !== NULL && $dateEnd !== NULL)
		{
			$where["dateCreated"] = array (">=", date("Y-m-d", $dateStart));
			$where["~dateCreated"] = array ("<", date("Y-m-d", $dateEnd));
		}

		if ($affiliateId !== NULL)
			$where["sourceAffiliateId"] = $affiliateId;

		return $this->count($where);
	}

	public function getImpressionsSummary($affiliateId = NULL)
	{
		return $this->getImpressionsForRange( NULL, NULL, $affiliateId );
	}
}

