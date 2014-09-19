<?php
/**
 * @author John Hargrove
 * 
 * Date: Jun 30, 2010
 * Time: 11:06:05 PM
 */

class WPAM_Data_TransactionRepository extends WPAM_Data_GenericRepository
{
	/**
	 * returns object(
	 * 	'balance' => total balance,
	 * 	'credits' => earned credits
	 * 	'debits' => paid out
	 * )
	 * @param  $affiliateId
	 * @return object
	 */
	public function getAccountSummary($affiliateId, $dateStart = NULL, $dateEnd = NULL)
	{
		$query = "
			select
				COALESCE(SUM(IF(type = 'credit', amount, 0)),0) credits,
				COALESCE(SUM(IF(type = 'payout', amount, 0)),0) debits,
				COALESCE(SUM(IF(type = 'adjustment', amount, 0)),0) adjustments,
				COALESCE(SUM(IF(status <> 'failed', amount,0)),0) standing
			FROM
				`{$this->tableName}`
			WHERE
				affiliateId = %d
		";

		if ($dateStart !== NULL)
			$query .= "
				AND dateCreated >= '".date("Y-m-d H:i:s", $dateStart)."'
			";
		if ($dateEnd !== NULL)
			$query .= "
				AND dateCreated < '".date("Y-m-d H:i:s", $dateEnd)."'
			";
		$query = $this->db->prepare($query, $affiliateId);
		return $this->db->get_row($query);
	}

	public function getBalance( $affiliateId, $dateStart = NULL ) {
		//start witih zero if no date specified
		if ( $dateStart === NULL )
			return 0;
		
		$query = "
			select
				COALESCE(SUM(IF(status <> 'failed', amount,0)),0) balance
			FROM
				`{$this->tableName}`
			WHERE
				affiliateId = %d
			AND dateCreated <= '" . date( 'Y-m-d H:i:s', strtotime( $dateStart ) ) . "'";
		
		$query = $this->db->prepare($query, $affiliateId);
		return $this->db->get_var($query);		
	}
}
