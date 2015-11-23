<?php
/**
 * @author John Hargrove
 * 
 * Date: May 20, 2010
 * Time: 10:41:07 PM
 */

class WPAM_Data_GenericRepository
{
    protected $db;
    protected $tableName;
	protected $modelType;
	protected $primaryKey;
	protected $pkeyIsAuto;
	protected $indexMultBy;

    public function __construct(wpdb $db, $tableName, $modelType, $primaryKey, $pkeyIsAuto = true, $indexMultiBy = null)
    {
        $this->db = $db;
        $this->tableName = $tableName;
		$this->modelType = $modelType;
		$this->primaryKey = $primaryKey;
		$this->pkeyIsAuto = $pkeyIsAuto;
		$this->indexMultiBy = $indexMultiBy;
    }

	public function update($model)
	{
		if ( get_class( $model ) != $this->modelType )
			throw new InvalidArgumentException( sprintf( __("'model' must be of type '%s'", 'affiliates-manager' ), $this->modelType ) );

		/// WPDB has a built-in function for doing updates, but it doesn't appear to handle NULL columns very well.
		///

		$row = (array)$model->toRow();
		$args = array();
		$bits = array();

		$query = "
			UPDATE `{$this->tableName}`
			SET";


		foreach ($row as $field => $value)
		{
			if ($value !== NULL)
			{
				$args[] = $value;
				$bits[] = "
				`$field` = %s";
			}
			else
			{
				$bits[] = "
				`$field` = NULL";
			}
		}

		$query .= implode(",", $bits);

		$query .= "
			WHERE
				`{$this->tableName}`.`{$this->primaryKey}` = %d
			";

		$args[] = $row[$this->primaryKey];

		$query = $this->db->prepare($query, $args);
		$this->db->query($query);
	}

	public function insert($model)
	{
		if (get_class($model) != $this->modelType)
			throw new InvalidArgumentException( sprintf( __("'model' must be of type '%s'", 'affiliates-manager' ), $this->modelType ) );

		$modelData = (array)$model->toRow();

		if ($this->pkeyIsAuto)
			unset($modelData[$this->primaryKey]);

		foreach ($modelData as $key => $val)
		{
			if ($val === NULL)
				unset($modelData[$key]);
		}

		$this->db->insert($this->tableName, $modelData);
		
		return $this->db->insert_id;
	}

	public function load($id)
	{
		return $this->loadBy(array(
			$this->primaryKey => $id
		));
	}

	public function exists($id)
	{
		$query = "
			select count(*)
			from `{$this->tableName}`
			where
				`{$this->primaryKey}`=%d
		";
		$query = $this->db->prepare($query, $id);
		return $this->db->get_var($query) > 0;
	}

	public function existsBy(array $where = array())
	{
		$query = "
			SELECT count(*)
			FROM `{$this->tableName}`
			" . $this->getWhereClause($where);
		
		return $this->db->get_var($query) > 0;
	}

	public function loadBy(array $where = array())
	{
		$query = "
			SELECT *
			FROM `{$this->tableName}`
			" . $this->getWhereClause($where) . "
			LIMIT 1
		";

		return $this->getModelFromQuery($query);
	}

	public function loadMultipleBy(array $where = array(), array $orderBy = array())
	{
		return $this->loadMultipleByLimit($where, $orderBy);
	}

	public function loadMultipleByLimit(array $where = array(), array $orderBy = array(), $limit = NULL)
	{
		$query = "
			SELECT *
			FROM `{$this->tableName}`"
		. $this->getWhereClause($where)
		. $this->getOrderByClause($orderBy);

		if ($limit !== NULL) $query .= " LIMIT $limit";

		return $this->getModelsFromQuery($query);
	}

	public function count(array $where = array())
	{
		$query = "
			SELECT COUNT(*)
			FROM `{$this->tableName}`"
		. $this->getWhereClause($where);

		return $this->db->get_var($query);
	}

	public function delete(array $where)
	{
		$query = "
			DELETE FROM `{$this->tableName}`

		".$this->getWhereClause($where);
		$this->db->query($query);

		return $this->db->rows_affected;
	}

	public function loadAll()
	{
		$query = "
			SELECT *
			FROM `{$this->tableName}`";

		return $this->getModelsFromQuery($query);
	}

	/**
	 * @param $includeTable
	 * @TODO does not work on a per-field basis
	 */
	protected function getOrderByClause( array $orderBy, $includeTable = true ) {
		$bits = array();

		$tableName = $includeTable ? "`{$this->tableName}`." : '';
		foreach ( $orderBy as $field => $value ) {
			if (strtolower($value) == 'desc')
				$bits[] = "{$tableName}`$field` DESC";
			else if (strtolower($value) == 'asc')
				$bits[] = "{$tableName}`$field` ASC";
		}

		if (count($bits) > 0)
			return " ORDER BY " . implode(", ", $bits);

		return "";
	}

	protected function getWhereClause(array $whereArgs)
	{
		$conditions = array();
		$values = array();
		foreach ($whereArgs as $field => $value)
		{
			//#45 a hack to allow multiple constraints on one field
			$field = str_replace('~', '', $field);
			
			$condition = "`{$this->tableName}`.`$field`";

			if ($value === NULL)
			{
				$condition .= " IS NULL";
			}
			else if (is_array($value))
			{
				list($operator, $v) = $value;

				$condition .= " $operator ";
				if ($operator == 'IN' OR $operator == 'NOT IN')
				{
					if (is_array($v))
					{
						$condition .= " ('".implode("','", $v)."')";
					}
				}
				else
				{
					if (is_string($v))
						$condition .= '%s';
					else
						$condition .= '%d';

					$values[] = $v;
				}
			}
			else
			{
				$condition .= " = ";
				if (is_string($value))
					$condition .= '%s';
				else
					$condition .= '%d';

				$values[] = $value;
			}

			$conditions[] = $condition;
		}
		if (count($conditions) > 0)
			return $this->db->prepare(" WHERE " . implode(" AND ", $conditions), $values);
		return "";
	}

	protected function getModelsFromQuery($query)
	{
		$rows = $this->db->get_results($query);
		$models = array();

		foreach ($rows as $row)
		{
			if($this->indexMultiBy)
				$models[$row[$this->indexMultiBy]] = $this->getModelFromRow($row);
			else
				$models[] = $this->getModelFromRow($row);
		}

		return $models;
	}

	protected function getModelFromQuery($query)
	{
		$row = $this->db->get_row($query);
		return $this->getModelFromRow($row);
	}

	protected function getModelFromRow($row)
	{
		if ($row != null)
		{
			$className = $this->modelType;

			$model = new $className();
			$model->fromRow($row);

			return $model;
		}
		return null;
	}
}
