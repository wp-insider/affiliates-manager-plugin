<?php
/**
 * @author John Hargrove
 * 
 * Date: 10/29/10
 * Time: 1:11 AM
 */

class WPAM_Data_Models_AffiliateFieldModel implements WPAM_Data_Models_IDataModel
{
	public $affiliateFieldId;
	public $type;
	public $name;
	public $order;
	public $length;
	public $required;
	public $fieldType;
	public $databaseField;
	public $enabled;
	
	function fromRow($rowData)
	{
		$modelMapper = new WPAM_Data_Models_ModelMapper();
		$modelMapper->map($rowData, $this);
	}

	function toRow()
	{
		$row = new stdClass();

		$modelMapper = new WPAM_Data_Models_ModelMapper();
		$modelMapper->map($this, $row, false);

		return $row;
	}
}
