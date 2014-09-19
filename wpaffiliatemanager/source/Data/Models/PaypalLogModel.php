<?php
/**
 * @author John Hargrove
 * 
 * Date: 1/3/11
 * Time: 10:41 PM
 */

class WPAM_Data_Models_PaypalLogModel implements WPAM_Data_Models_IDataModel
{
	public
		$paypalLogId,
		$responseTimestamp,
		$dateOccurred,
		$correlationId,
		$ack,
		$version,
		$build,
		$errors,
		$rawResponse,
		$status,
		$amount,
		$fee,
		$totalAmount;

	function fromRow($rowData)
	{
		$modelMapper = new WPAM_Data_Models_ModelMapper();
		$modelMapper->map($rowData, $this);

		$this->dateOccurred = strtotime($this->dateOccurred);
		$this->responseTimestamp = strtotime($this->responseTimestamp);
		$this->errors = unserialize($this->errors);
	}

	function toRow()
	{
		$row = new stdClass();

		$modelMapper = new WPAM_Data_Models_ModelMapper();
		$modelMapper->map($this, $row, false);

		$row->dateOccurred = date('Y-m-d H:i:s', $row->dateOccurred);
		$row->responseTimestamp = date('Y-m-d H:i:s', $row->responseTimestamp);
		$row->errors = serialize($row->errors);

		return $row;
	}
}
