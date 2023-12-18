<?php
namespace framework\kernel\DbManager\manager;

use App;
use framework\kernel\component\Kernel;
use framework\component\exception\ElastiException;
use framework\component\helper\StringHelper;
use framework\kernel\base\Cache;
use framework\kernel\utility\BasicUtils;

class DbManager extends Kernel
{
	CONST COLLECT_QUERIES = true;

	protected $connection;
	
	protected $errorMessage;

	public $queries = [];

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
		return $this->run($statement, $params, false, false, true, $dump);
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

	private function run($statement, $params = array(), $fetchResult = false, $getOne = false, $getLastInsertId = false, $dump = false)
	{
		// if (!is_string($statement) && !is_array($params)) {
		// 	dump($statement);dump($params);exit;
		// }

		if (self::COLLECT_QUERIES) {
			$this->queries[] = [
				'statement' => $statement,
				'params' => $params
			];
		}
		
		try {
			//dump($this->errorMessage);
	        if (!$this->connection || $this->errorMessage) {
	            return;
	        }
			$this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);
			
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
			// dump($statement); dump($params);
			$sth->execute();
			if ($fetchResult) {
				// dump($sth);
				$return = $getOne ? $sth->fetch(\PDO::FETCH_ASSOC, 0) : $sth->fetchAll(\PDO::FETCH_ASSOC);
			} elseif ($getLastInsertId) {
				$return = $this->connection->lastInsertId();
			}


			return $return;

		} catch (\ErrorException $e){
			$missingTablePos = strpos($e->getMessage(), 'Base table or view not found');
			if ($missingTablePos !== false) {
				// dump($this->getConnection()->getAttribute(\PDO::ATTR_CONNECTION_STATUS));
				$dbName = $this->getConnection()->query('SELECT database()')->fetchColumn();
				$messageParts1 = explode("Table '".$dbName.'.', $e->getMessage());
				if (count($messageParts1) == 2) {
					$messageParts2 = explode("' doesn't exist", $messageParts1[1]);
					$missingTableName = $messageParts2[0];
					App::getContainer()->getSession()->set('maintenanceMode', true);
					throw new \Exception($e);

					// throw new ElastiException($this->wrapExceptionParams(array(
					// 	'error' => $e->getMessage(), 
					// 	'statement' => $statement,
					// 	'queryParams' => BasicUtils::arrayToString($params))
					// ), 1660);
				}
			}
			
			throw new ElastiException($this->wrapExceptionParams(array(
				'error' => $e->getMessage(), 
				'statement' => nl2br($statement),
				'queryParams' => BasicUtils::arrayToString($params))
			), 1661);
		} catch (\PDOException $e) {
			throw new ElastiException($this->wrapExceptionParams(array(
				'error' => $e->getMessage(), 
				'statement' => nl2br($statement),
				'queryParams' => BasicUtils::arrayToString($params))
			), 1661);
		}
	}

	public function getTableNames()
	{
		$tableNames = array();
        $cached = $this->getContainer()->getFromCache('database', 'DB_tableNames');
        if (!$cached) {
			if (!$this->connection || $this->errorMessage) {
				return;
			}

			// dump(Cache::cacheRefreshRequired());exit;
			
			if (!Cache::cacheRefreshRequired()) {
				$storedCache = App::$cache->read('DB_tableNames');
				if (!empty($storedCache)) {
					$this->getContainer()->addToCache('database', 'DB_tableNames', $storedCache);

					return $storedCache;
				}
			}

			$stm1 = "SHOW TABLES";
			$tablesRaw = $this->findAll($stm1);
			foreach ($tablesRaw as $tableRaw) {
				foreach ($tableRaw as $key => $tableName) {
					$tableNames[] = $tableName;
				}
			}

			App::$cache->write('DB_tableNames', $tableNames);
			$this->getContainer()->addToCache('database', 'DB_tableNames', $tableNames);

        } else {
            $tableNames = $cached;
        }

		return $tableNames;
	}

	public function tableExists($tableName)
	{
		$existences = array();
		$cached = $this->getContainer()->getFromCache('database', 'DB_existingTables');
        if (!$cached || !isset($cached[$tableName])) {
			if (!$this->connection || $this->errorMessage) {
				return;
			}

			if (!Cache::cacheRefreshRequired()) {
				$storedCache = App::$cache->read('DB_tableExistences');
				if (!empty($storedCache) && isset($storedCache[$tableName])) {
					$this->getContainer()->addToCache('database', 'DB_tableExistences', $storedCache);
					if (!isset($storedCache[$tableName])) {
						throw new \Exception('Missing cached existence for table: '.$tableName);
					}

					return $storedCache[$tableName];
				}

				if (is_array($storedCache)) {
					$cached = $storedCache;
				}
			}

			if (in_array($tableName, $this->getTableNames())) {
				$stm = "SELECT count(*) as cnt
				FROM information_schema.tables
				WHERE table_schema = '".$this->getDatabaseName()."' 
					AND table_name = '".$tableName."'
				LIMIT 1";
				// dump($stm);
				$res = $this->findOne($stm);
				$existence = $res['cnt'] == 0 ? false : true;

				if (is_array($cached) && !isset($cached[$tableName])) {
					$cached = array_merge($cached, [$tableName => $existence]);
				} else {
					$cached = [$tableName => $existence];
				}

				$this->getContainer()->addToCache('database', 'DB_tableExistences', $cached);
				App::$cache->write('DB_tableExistences', $cached);
				$existences = $cached;
			} else {
				return false;
				// throw new \Exception('Unknown table: '.$tableName);
			}
		} else {
            $existences = $cached;
		}

		if (!isset($existences[$tableName])) {
			return false;
			// throw new \Exception('Missing existence for table: '.$tableName);
		}

		return $existences[$tableName];
	}

	public function getFieldAttributes($tableName)
	{
		$fieldDescriptions = array();
        $cached = $this->getContainer()->getFromCache('database', 'DB_fieldDescriptions');
        if (!$cached || !isset($cached[$tableName])) {
			if (!$this->connection || $this->errorMessage) {
				return;
			}

			if (!Cache::cacheRefreshRequired()) {
				$storedCache = App::$cache->read('DB_fieldDescriptions');
				if (!empty($storedCache) && isset($storedCache[$tableName])) {
					$this->getContainer()->addToCache('database', 'DB_fieldDescriptions', $storedCache);
					if (!isset($storedCache[$tableName])) {
						throw new \Exception('Missing cached description for table: '.$tableName);
					}

					return $storedCache[$tableName];
				}

				if (is_array($storedCache)) {
					$cached = $storedCache;
				}
			}

			if (in_array($tableName, $this->getTableNames())) {
				$stm1 = "DESCRIBE ".$tableName;
				$fieldDescription = $this->findAll($stm1);

				if (is_array($cached) && !isset($cached[$tableName])) {
					$cached = array_merge($cached, [$tableName => $fieldDescription]);
				} else {
					$cached = [$tableName => $fieldDescription];
				}

				// dump('CACHEL!!!');
				// dump($cached);//exit;

				$this->getContainer()->addToCache('database', 'DB_fieldDescriptions', $cached);
				App::$cache->write('DB_fieldDescriptions', $cached);
				$fieldDescriptions = $cached;
			} else {
				return array();
				// throw new \Exception('Unknown table: '.$tableName);
			}
        } else {
            $fieldDescriptions = $cached;
		}

		if (!isset($fieldDescriptions[$tableName])) {
			throw new \Exception('Missing description for table: '.$tableName);
		}

		return $fieldDescriptions[$tableName];
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
