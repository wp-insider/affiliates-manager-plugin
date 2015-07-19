<?php
/**
 * @author John Hargrove
 * 
 * Date: Jun 28, 2010
 * Time: 9:42:04 PM
 */

require_once WPAM_BASE_DIRECTORY . "/source/Data/Models/EventModel.php";
require_once WPAM_BASE_DIRECTORY . "/source/Data/Models/ActionModel.php";

class WPAM_Data_EventRepository extends WPAM_Data_GenericRepository
{
	public function insert($event)
	{
		parent::insert($event);
	}
	public function update($event)
	{
		parent::update($event);
	}

	public function quickInsert($dateCreated, $trackingKey, $actionKey)
	{
		$query = "
			INSERT INTO `{$this->tableName}`
			SET
				dateCreated = %s,
				trackingTokenId = (
					select trackingTokenId
					from " . $this->db->prefix . WPAM_Data_DataAccess::TABLE_TRACKING_TOKENS . "
					where `trackingKey` = %s
				),
				actionId = (
					select actionId
					from " . $this->db->prefix . WPAM_Data_DataAccess::TABLE_ACTIONS . "
					where `name` = %s
				)";
		$binConverter = new WPAM_Util_BinConverter();
		$query = $this->db->prepare($query, date("Y-m-d H:i:s", $dateCreated), $binConverter->binToString($trackingKey), $actionKey);
		
		$this->db->query($query);
	}

	public function getSummaryForRange($dateStart = NULL, $dateEnd = NULL, $affiliateId = NULL)
	{
		$query = "
			select
				COALESCE(SUM(IF(a.name = 'visit', 1, 0)), 0) visits,
				COALESCE(SUM(IF(a.name = 'purchase', 1, 0)), 0) purchases
			from `{$this->tableName}` ev
			inner join `".$this->db->prefix . WPAM_Data_DataAccess::TABLE_TRACKING_TOKENS."` tt using (`trackingTokenId`)
			inner join `".$this->db->prefix . WPAM_Data_DataAccess::TABLE_ACTIONS."` a using (`actionId`)
		";

		$where = array();

		if ($dateStart !== NULL && $dateEnd !== NULL)
			$where[] = "ev.`dateCreated` >= '".date("Y-m-d", $dateStart)."'
				AND ev.`dateCreated` < '".date("Y-m-d", $dateEnd) . "'";

		if ($affiliateId !== NULL)
			$where[] = "tt.sourceAffiliateId = " . ((int)$affiliateId);

		if (count($where) > 0)
			$query .= " WHERE " . implode(" AND ", $where);

		return $this->db->get_row($query);
	}

	public function getSummary($affiliateId = NULL)
	{
		return $this->getSummaryForRange( NULL, NULL, $affiliateId );
	}
}
