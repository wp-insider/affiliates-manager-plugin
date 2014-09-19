<?php
/**
 * @author John Hargrove
 * 
 * Date: Jun 9, 2010
 * Time: 11:54:22 PM
 */

class WPAM_Data_Models_TrackingTokenModel implements WPAM_Data_Models_IDataModel
{
	public
		$trackingTokenId,
		$sourceAffiliateId,
		$sourceCreativeId,
		$dateCreated,
		$trackingKey,
		$referer,
		$affiliateSubCode;

	function fromRow($rowData) {
		$modelMapper = new WPAM_Data_Models_ModelMapper();
		$modelMapper->map($rowData, $this);
		$binConverter = new WPAM_Util_BinConverter();
		
		$this->dateCreated = strtotime($this->dateCreated);
		$this->trackingKey = $binConverter->stringToBin($this->trackingKey);
	}

	function toRow() {
		$row = new stdClass();

		$binConverter = new WPAM_Util_BinConverter();
		$modelMapper = new WPAM_Data_Models_ModelMapper();
		$modelMapper->map($this, $row, false);

		$row->dateCreated = date('Y-m-d H:i:s', $row->dateCreated);
		$row->trackingKey = $binConverter->binToString($row->trackingKey);

		return $row;

	}
}
