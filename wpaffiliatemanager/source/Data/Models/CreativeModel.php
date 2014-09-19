<?php
/**
 * @author John Hargrove
 * 
 * Date: Jun 7, 2010
 * Time: 10:45:40 PM
 */

class WPAM_Data_Models_CreativeModel implements WPAM_Data_Models_IDataModel
{
	public $creativeId;
	public $dateCreated;
	public $status;

	public $name;
	public $type;
	public $altText;
	public $imagePostId;
	public $linkText;
	public $slug;
	
    public function fromRow($rowData)
    {
		$modelMapper = new WPAM_Data_Models_ModelMapper();
		$modelMapper->map($rowData, $this);

		$this->dateCreated = strtotime($this->dateCreated);
	}

    public function toRow()
    {
        $row = new stdClass();

		$modelMapper = new WPAM_Data_Models_ModelMapper();
		$modelMapper->map($this, $row, false);

		$row->dateCreated = date('Y-m-d H:i:s', $row->dateCreated);

		return $row;
    }

	public function activate()
	{
		$this->status = 'active';
	}
	public function deactivate()
	{
		$this->status = 'inactive';
	}
	public function isActive()
	{
		return $this->status === 'active';
	}
	public function isInactive()
	{
		return $this->status === 'inactive';
	}
}
