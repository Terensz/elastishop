<?php
namespace framework\kernel\DbManager\connection;

use framework\kernel\component\Kernel;

class PDOConnect extends Kernel
{
	private $errorMessage;

	public function getErrorMessage()
	{
		return $this->errorMessage;
	}

	public function connect($connectionId)
	{
		$dbConnection = $this->getContainer()->getDbConnection($connectionId);
		$dbConnectionStr = $dbConnection->getDriver().':host='.$dbConnection->getHost().';dbname='.$dbConnection->getName().';charset=UTF8';
		try {
			$pdoConnection = new \PDO($dbConnectionStr, $dbConnection->getUsername(), $dbConnection->getPassword());
		} catch (\PDOException $e) {
			$this->errorMessage = $e->getMessage();
			$pdoConnection = null;
		}

		return $pdoConnection;
	}
}
