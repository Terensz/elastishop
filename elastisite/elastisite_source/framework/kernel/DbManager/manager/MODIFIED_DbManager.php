<?php
namespace framework\kernel\DbManager\manager;

use framework\kernel\component\Kernel;
use framework\component\exception\ElastiException;
use framework\component\helper\StringHelper;
use framework\kernel\DbManager\StatementAnalyzer;
use framework\kernel\utility\BasicUtils;

class DbManager extends Kernel
{
	protected $connection;
	protected $errorMessage;

	public function connect($connectionId = 'default')
	{
		$pdo = $this->getContainer()->getKernelObject('PDOConnect');
		
		$this->connection = $pdo->connect($connectionId);
		$this->errorMessage = $pdo->getErrorMessage();
		// dump($this->errorMessage);exit;

	}

	public function getDatabaseName()
	{
		return isset($this->connection) && $this->connection instanceof \PDO ? $this->connection->query('select database()')->fetchColumn() : null;
	}

	public function tableExists($tableName)
	{
		// dump($this->connection->query('select database()')->fetchColumn());
		$stm = "SELECT count(*) as cnt
		FROM information_schema.tables
		WHERE table_schema = '".$this->getDatabaseName()."' 
			AND table_name = '".$tableName."'
		LIMIT 1";
		// dump($stm);
		$res = $this->findOne($stm);
		return $res['cnt'] == 0 ? false : true;
	}

	public function getErrorMessage()
	{
		return $this->errorMessage;
	}

	public function getConnection()
	{
		return $this->connection;
	}

	public function execute($statement, $params = array(), $dump = false)
	{
		return $this->run($statement, $params, false, false, true, $dump, true);
	}

	public function findAll($statement, $params = array(), $dump = false)
	{
		return $this->run($statement, $params, true, false, false, $dump);
	}

	public function findOne($statement, $params = array())
	{
		return $this->run($statement, $params, true, true, false);
	}

	public function getColumnParams($statement, $rows = null)
	{
		// dump($statement);
		$sth = $this->connection->prepare($statement);
		$sth->execute();
		$columnParams = [];

		for ($i = 0; $i < $sth->columnCount(); $i++) {
			$columnMeta = $sth->getColumnMeta($i);
			$columnType = $this->transformColumnType($columnMeta['native_type']);
			$columnParams[] = [
				'name' => $columnMeta['name'],
				'type' => $columnType,
				'length' => $this->transformColumnLength($columnType, $columnMeta['len'])
			];
		}
		return $columnParams;
	}

	public function transformColumnLength($columnType, $length)
	{
		if ($columnType == 'string') {
			return $length / 3;
		}
		if ($columnType == 'date') {
			return 20;
		}
		return $length;
	}

	public function transformColumnType($resultType)
	{
		$converterArray = [
			'TINY' => 'numeric',
			'DOUBLE' => 'numeric',
			'LONG' => 'numeric',
			'SHORT' => 'numeric',
			'FLOAT' => 'numeric',
			'INT24' => 'numeric',
			'DATETIME' => 'date',
			'NEWDATE' => 'date',
			'LONGLONG' => 'string',
			'STRING' => 'string',
			'VAR_STRING' => 'string'
		];
		return isset($converterArray[$resultType]) ? $converterArray[$resultType] : 'string';
	}

	private function run($statement, $params = array(), $fetchResult = false, $getOne = false, $getLastInsertId = false, $dump = false, $execute = false)
	{
		// if (!is_string($statement) && !is_array($params)) {
		// 	dump($statement);dump($params);exit;
		// }
		
		try {
			//dump($this->errorMessage);
	        if (!$this->connection || $this->errorMessage) {
	            return;
	        }
			$this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);
			$fieldAttributeCollection = null;
			$fieldsWidthParams = null;

			if ($execute) {
				// $updatePos = 
				// if ()
				$statementAnalyzer = new StatementAnalyzer($statement);
				/**
				 * No inner statements
				*/
				if (empty($statementAnalyzer->getInnerSelectStatements())) {
					$parts = explode(' ', trim($statementAnalyzer->getAnalyzerStatement()));
					if ($parts[0] == 'update') {
						// dump($statementAnalyzer);//exit;
						$tableName = $parts[1];
						$fieldAttributeCollection = $this->getFieldAttributes($tableName);
						$fieldsWidthParams = StatementAnalyzer::pairFieldWidthParam($statementAnalyzer->getAnalyzerStatement());
						// dump($fieldsWidthParams);
						// dump($fieldAttributeCollection);exit;
					}
					// dump($statementAnalyzer->getAnalyzerStatement());
					// dump($parts);
					// dump($parts[0]);
					// dump($statementAnalyzer);exit;
				}
			}
			
			$sth = $this->connection->prepare($statement);
			foreach ($params as $key => $value) {
				if (is_array($key) || is_array($value)) {
					dump('Binding error:');
					dump('Key:');
					dump($key);
					dump('Value:');
					dump($value);
					exit;
				}


				// $fieldAttributeCollection
				// dump($fieldAttributeCollection);
				if ($fieldsWidthParams && is_array($fieldAttributeCollection)) {
					// $cleanKey = ltrim($key, ':');
					$lowercaseKey = strtolower($key);
					// if ($lowercaseKey == 'width') {
					// 	dump($lowercaseKey.' - '.(isset($fieldsWidthParams[$lowercaseKey]) ? $fieldsWidthParams[$lowercaseKey] : 'null'));
					// 	dump($fieldsWidthParams);
					// }
					if (isset($fieldsWidthParams[$lowercaseKey])) {
						$fieldName = $fieldsWidthParams[$lowercaseKey];
						foreach ($fieldAttributeCollection as $fieldAttributes) {
							if ($fieldAttributes['Field'] == $fieldName) {
								if ($fieldAttributes['Type'] == 'int') {
									if ($value != null && !is_numeric($value) && $fieldAttributes['Default'] == null) {
										$value = null;
									}
								}
							}
						}

						// if ($fieldName == 'width') {
						// 	dump($statement);
						// 	dump($key);
						// 	dump($value);
						// }
					}
				}
				
				$key = ':'.trim($key, ':');
				// $sth->bindValue($key, $value);
				if (StringHelper::isDecimal($value)) {
					$sth->bindValue($key, $value, \PDO::PARAM_STR);
				}
				elseif (is_numeric($value)) {
					$sth->bindValue($key, $value, \PDO::PARAM_INT);
				}
				elseif ($value instanceof \DateTime) {
					$sth->bindValue($key, $value->format('Y-m-d H:i:s'), \PDO::PARAM_STR);
				}
				elseif ($value === true || $value === false) {
					$sth->bindValue($key, (int)$value, \PDO::PARAM_INT);
				}
				else {
					// if (!is_string($value) && $value !== null) {
					// 	dump($value);exit;
					// }
					$sth->bindValue($key, $value, \PDO::PARAM_STR);
				}
			}

			$updatePos = strpos($statement, 'UPDATE');
			$catPos = strpos($statement, 'content_editor_unit');
			if ($catPos !== false && $updatePos !== false) {
				dump($fieldsWidthParams);
				dump($fieldAttributeCollection);
				dump($statementAnalyzer);
				dump($params);
			}

			// dump($statement); dump($params);
			$sth->execute();
			if ($fetchResult) {
				// dump($sth);
				return $getOne ? $sth->fetch(\PDO::FETCH_ASSOC, 0) : $sth->fetchAll(\PDO::FETCH_ASSOC);
			}
			if ($getLastInsertId) {
				return $this->connection->lastInsertId();
			}
		} catch (\ErrorException $e){
			// dump($e);exit;
			throw new ElastiException($this->wrapExceptionParams(array(
				'error' => $e->getMessage(), 
				'statement' => $statement,
				'queryParams' => BasicUtils::arrayToString($params))
			), 1660);
		} catch (\PDOException $e) {
			// dump($e);exit;
			throw new ElastiException($this->wrapExceptionParams(array(
				'error' => $e->getMessage(), 
				'statement' => $statement,
				'queryParams' => BasicUtils::arrayToString($params))
			), 1661);
		}
	}

	public function getTableNames()
	{
		if (!$this->connection || $this->errorMessage) {
			return;
		}
		$tableNames = array();
        $cached = $this->getContainer()->getFromCache('database', 'tableNames');
        if (!$cached) {
			$stm1 = "SHOW TABLES";
			$tablesRaw = $this->findAll($stm1);
			foreach ($tablesRaw as $tableRaw) {
				foreach ($tableRaw as $key => $tableName) {
					$tableNames[] = $tableName;
				}
			}
            $this->getContainer()->addToCache('database', 'tableNames', $tableNames);
        } else {
            $tableNames = $cached;
        }
		return $tableNames;
	}

	public function getFieldAttributes($tableName)
	{
		if (!$this->connection || $this->errorMessage) {
			return;
		}
		$fieldParams = array();
        $cached = $this->getContainer()->getFromCache('fieldParams', $tableName);
        if (!$cached) {
			if (in_array($tableName, $this->getTableNames())) {
				$stm1 = "DESCRIBE ".$tableName;
				$fieldParams = $this->findAll($stm1);
			}
            $this->getContainer()->addToCache('fieldParams', $tableName, $fieldParams);
        } else {
            $fieldParams = $cached;
		}
		return $fieldParams;
	}

	public function getFieldNames($tableName)
	{
		if (!$this->connection || $this->errorMessage) {
			return;
		}
		$fieldNames = array();
		foreach ($this->getFieldAttributes($tableName) as $fieldParamsRow) {
			$fieldNames[] = $fieldParamsRow['Field'];
		}
		return $fieldNames;
	}
}
