<?php
/**
 * @author Element Green <element@elementsofsound.org>
 * 
 * Date: Sep 2, 2014
 * Time: 11:40:35 AM
 */

class WPAM_Data_Models_ImpressionModel implements WPAM_Data_Models_IDataModel
{
	public
		$impressionId,
		$sourceAffiliateId,
		$sourceCreativeId,
		$dateCreated,
		$referer,
		$affiliateSubCode;

	function fromRow($rowData) {
		$modelMapper = new WPAM_Data_Models_ModelMapper();
		$modelMapper->map($rowData, $this);

		$this->dateCreated = strtotime($this->dateCreated);
	}

	function toRow() {
		$row = new stdClass();

		$modelMapper = new WPAM_Data_Models_ModelMapper();
		$modelMapper->map($this, $row, false);

		$row->dateCreated = date('Y-m-d H:i:s', $row->dateCreated);

		return $row;

	}
}
