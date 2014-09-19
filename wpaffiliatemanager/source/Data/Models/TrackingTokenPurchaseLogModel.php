<?php
/**
 * @author John Hargrove
 * 
 * Date: Jul 9, 2010
 * Time: 1:06:34 AM
 */

class WPAM_Data_Models_TrackingTokenPurchaseLogModel implements WPAM_Data_Models_IDataModel
{
	public $trackingTokenPurchaseLogId;
	public $trackingTokenId;
	public $purchaseLogId;

	function fromRow($rowData) {
		$modelMapper = new WPAM_Data_Models_ModelMapper();
		$modelMapper->map($rowData, $this);
	}

	function toRow() {
		$row = new stdClass();

		$modelMapper = new WPAM_Data_Models_ModelMapper();
		$modelMapper->map($this, $row, false);

		return $row;
	}
}
