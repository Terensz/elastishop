<?php
namespace framework\kernel\DbManager\manager;

use framework\kernel\component\Kernel;
use framework\kernel\DbManager\entity\DbQuery;
use framework\component\exception\ElastiException;

class StatementBuilder extends Kernel
{
	private $statements = array();

	public function getStatements()
	{
		return $this->statements;
	}

	public function assemble(DbQuery $dbQuery)
	{
		$subQueries = $dbQuery->getSubQueries();
		foreach ($subQueries as $subQuery) {
			$this->processQuery($subQuery);
		}
		$this->processQuery($dbQuery);
	}

	public function processQuery(DbQuery $dbQuery)
	{
		if ($dbQuery->getSelect()) {
			$statement = $this->assembleSelect($dbQuery);
		}
		elseif ($dbQuery->getInsert()) {
			$statement = $this->assembleInsert($dbQuery);
		}
		elseif ($dbQuery->getUpdate()) {
			$statement = $this->assembleUpdate($dbQuery);
		}
		elseif ($dbQuery->getDelete()) {
			$statement = $this->assembleDelete($dbQuery);
		}
		else {
			throw new ElastiException('Missing query purpose', ElastiException::ERROR_TYPE_SECRET_PROG);
		}
		$this->statements[] = $statement;
	}

	public function getFieldsPropertyList(DbQuery $dbQuery, $operation)
	{
		$fieldsPropertyList = '';
		$fields = $dbQuery->getFields();
		$fieldsAdded = 0;
		foreach ($fields as $field) {
			if ($field['value']) {
				$fieldsPropertyList .= ($fieldsAdded == 0 ? '' : ',').' '.(($operation == 'select') ? $field['tableAlias'].'.' : '').$field['name']. ' ';
				$fieldsAdded++;
			}
		}
		return $fieldsPropertyList;
	}

	public function getUpdateValueList(DbQuery $dbQuery)
	{
		return '';
	}

	public function getInsertValueList(DbQuery $dbQuery)
	{
		return '';
	}

	// public function getUpdateParameters(DbQuery $dbQuery)
	// {
	// 	$setStr = '';
	// 	$params = array();
	// 	$fields = $dbQuery->getFields();
	// 	for ($i = 0; $i < count($fields); $i++) {
	// 		$setStr .= ($i == 0 ? '' : ' AND ').' '.
	// 			$field['tableAlias'].'.'.$field['name']. ' = '.
	// 			':'.$field['tableAlias'].'_'.$field['name'];
	// 		$params[':'.$field['tableAlias'].'_'.$field['name']] = $field['value'];
	// 	}
	// 	return array(
	// 		'setStr' => $setStr,
	// 		'params' => $params
	// 	);
	// }

	public function getTableList(DbQuery $dbQuery)
	{
		$tableList = '';
		$tables = $dbQuery->getTables();
		for ($i = 0; $i < count($tables); $i++) {
			if ($i == 0) {
				$tableList .= ' '.$tables[$i]['name'].' '.$tables[$i]['alias'];
			}
			else {
				$relationStr = '';
				foreach ($dbQuery->getRelations() as $relation) {
					if ($relation['sibling'] == $tables['alias']) {
						$relationStr = ' '.
							$relation['sibling'].'.'.$dbQuery->getTableName($relation['parent']).'_id = '.
							$relation['parent'].'.id';
					}
				}
				$tableList .= ' JOIN '.$tables[$i]['name'].'.'.$tables[$i]['alias'].' ON '.$relationStr;
			}
		}
		return $tableList;
	}

	public function getConditionList(DbQuery $dbQuery)
	{
		$conditionList = '';
		return $conditionList;
	}

	public function assembleSelect(DbQuery $dbQuery)
	{
		return array(
			'rawStatement' => "SELECT ".$this->getFieldsPropertyList($dbQuery, 'select')."
							FROM ".$this->getTableList($dbQuery)."
							".$this->getConditionList($dbQuery)."
							",
			'params' => array()
		);
	}

	public function assembleInsert(DbQuery $dbQuery)
	{
		return array(
			'rawStatement' => "INSERT INTO
							(".$this->getFieldsPropertyList($dbQuery, 'insert').")
							VALUES
							(".$this->getInsertValueList($dbQuery).")
							",
			'params' => array()
		);
	}

	public function assembleUpdate(DbQuery $dbQuery)
	{
		return array(
			'rawStatement' => "UPDATE
							",
			'params' => array()
		);
	}

	public function assembleDelete(DbQuery $dbQuery)
	{
		return array(
			'rawStatement' => "DELETE FROM
							",
			'params' => array()
		);
	}
}
