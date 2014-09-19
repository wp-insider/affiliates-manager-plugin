<?php
/**
 * @author John Hargrove
 * 
 * Date: Jun 6, 2010
 * Time: 7:22:15 PM
 */

require_once WPAM_BASE_DIRECTORY . "/source/Data/Models/CreativeModel.php";
class WPAM_Data_CreativeRepository extends WPAM_Data_GenericRepository
{
    public function insert(WPAM_Data_Models_CreativeModel $creative)
    {
		return parent::insert($creative);
    }

	/**
	 * @param  $id int
	 * @return WPAM_Data_Models_CreativeModel
	 */
	public function load($id)
	{
		return parent::load($id);
	}

    public function update(WPAM_Data_Models_CreativeModel $creative)
    {
		return parent::update($creative);
    }


	public function loadAllActiveNoDeletes()
	{
		$query = "
			SELECT *
			FROM `{$this->tableName}`
			WHERE
				status = 'active'
				and status != 'deleted'
		";
		return $this->getModelsFromQuery($query);
	}

	public function loadAllNoDeletes()
	{
		$query = "
			SELECT *
			FROM `{$this->tableName}`
			WHERE
				status != 'deleted'
		";
		return $this->getModelsFromQuery($query);
	}

	public function loadAllInactiveNoDeletes()
	{
		$query = "
			SELECT *
			FROM `{$this->tableName}`
			WHERE
				status = 'inactive'
				and status != 'deleted'
		";
		return $this->getModelsFromQuery($query);
	}
}


