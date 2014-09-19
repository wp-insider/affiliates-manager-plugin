<?php
/**
 * @author John Hargrove
 * 
 * Date: 12/2/10
 * Time: 12:30 AM
 */

class WPAM_Data_Models_MessageModel implements WPAM_Data_Models_IDataModel
{
	public
		$messageId,
		$name,
		$use,
		$type,
		$content;

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
