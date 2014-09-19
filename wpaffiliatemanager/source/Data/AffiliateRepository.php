<?php

class WPAM_Data_AffiliateRepository extends WPAM_Data_GenericRepository
{
    public function insert(WPAM_Data_Models_AffiliateModel $affiliate)
    {
		return parent::insert($affiliate);
    }

	public function load($id)
	{
		return parent::load($id);
	}

    public function update(WPAM_Data_Models_AffiliateModel $affiliate)
    {
		return parent::update($affiliate);
    }

	public function loadByUserId($userId)
	{
		$query = "
			select *
			from `{$this->tableName}`
			where
				userId=%d
		";
		
		return $this->getModelFromQuery($this->db->prepare($query, $userId));
	}

	public function loadByTrackingKey($trackingKey)
	{
		$query = "
			select `{$this->tableName}`.*
			from `" . $this->db->prefix . WPAM_Data_DataAccess::TABLE_TRACKING_TOKENS . "`
			inner join `{$this->tableName}` on (
				`".$this->db->prefix . WPAM_Data_DataAccess::TABLE_TRACKING_TOKENS ."`.sourceAffiliateId =
				`".$this->db->prefix . WPAM_Data_DataAccess::TABLE_AFFILIATES."`.affiliateId
			)
			where
				`" . $this->db->prefix . WPAM_Data_DataAccess::TABLE_TRACKING_TOKENS . "`.trackingKey = %s;
    	";

		$binConverter = new WPAM_Util_BinConverter();
		$query = $this->db->prepare($query, $binConverter->binToString($trackingKey));
		return $this->getModelFromQuery($query);		
	}

	public function loadByPurchaseLogId($purchaseLogId)
	{
		$query = "
			SELECT a.*
			FROM `".$this->db->prefix.WPAM_Data_DataAccess::TABLE_TRACKING_TOKENS_PURCHASE_LOGS . "` pl
			INNER JOIN `".$this->db->prefix.WPAM_Data_DataAccess::TABLE_TRACKING_TOKENS."` tt ON (tt.trackingTokenId = pl.trackingTokenId)
			INNER JOIN `{$this->tableName}` a ON (a.affiliateId = tt.sourceAffiliateId)
			WHERE
				pl.purchaseLogId = %d
		";
		$query = $this->db->prepare($query, $purchaseLogId);
		
		return $this->getModelFromQuery($query);
	}

	public function getAffiliateIdFromRefKey($refkey)
	{
		$binConverter = new WPAM_Util_BinConverter();
		$query = "
			select affiliateId
			from `{$this->tableName}`
			where
				uniqueRefKey=%s
		";

		$value = $binConverter->binToString($refkey);
		//this output will prevent wp_redirect from working
		//if ( WPAM_DEBUG ) 
		//	echo "\n\nQuery: {$query}\nValue: {$value}\n\n";

		$query = $this->db->prepare($query, $value);
		$affId = $this->db->get_var($query);
		return ( $affId != NULL ? (int)$affId : $affId );
	}

	public function isUserAffiliate($userId)
	{
		$query = "
			select COUNT(*)
			from `{$this->tableName}`
			where
				userId=%d
		";
		$query = $this->db->prepare($query, $userId);
		return $this->db->get_var($query) > 0;
	}

	public function loadAllByStatus($status)
	{
		$query = "
			select *
			from `{$this->tableName}`
			where
				status=%s";
		
		$query = $this->db->prepare($query, $status);

		return $this->getModelsFromQuery($query);
	}

	public function loadAffiliateSummary( array $whereArgs = array(), $minBalance = NULL, array $orderBy = array() ) {
		$query = "
			select
				`{$this->tableName}`.*,
				(
					select coalesce(sum(tr.amount),0)
					from `".$this->db->prefix.WPAM_Data_DataAccess::TABLE_TRANSACTIONS."` tr
					where
						tr.affiliateId = `{$this->tableName}`.affiliateId
						and tr.status != 'failed'
				) balance,
				(
					select coalesce(sum(IF(tr.type = 'credit', amount, 0)),0)
					from `".$this->db->prefix.WPAM_Data_DataAccess::TABLE_TRANSACTIONS."` tr
					where
						tr.affiliateId = `{$this->tableName}`.affiliateId
						and tr.status != 'failed'
				) earnings
			from `{$this->tableName}`
		" . $this->getWhereClause( $whereArgs );

		
		if ( $minBalance !== NULL ) {
			$query .= "
				having balance >= %s
			";
			$query = $this->db->prepare( $query, $minBalance );
		}
		
		$query .= $this->getOrderByClause( $orderBy, false );

		return $this->db->get_results( $query );
	}
}

?>
