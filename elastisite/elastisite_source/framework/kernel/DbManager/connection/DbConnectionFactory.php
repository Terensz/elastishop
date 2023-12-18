<?php
namespace framework\kernel\DbManager\connection;

use framework\kernel\component\Kernel;
use framework\kernel\DbManager\entity\DbConnection;

class DbConnectionFactory extends Kernel
{
	public function __construct()
	{
		$dbConnectionData = $this->getDbConnectionData();
		// dump($dbConnectionData);
		foreach ($dbConnectionData as $db) {
			$dbConnection = new DbConnection();
			foreach ($db as $property => $value) {
				$setter = 'set'.ucfirst($property);
				$dbConnection->$setter($value);
			}

			if (!$dbConnection->getId()) {
				$dbConnection->setId(!$this->getContainer()->getDbConnection('default') ? 'default' : $dbConnection->getName());
			}
			$this->getContainer()->addDbConnection($dbConnection);
		}

		$dbm = $this->getDbManager();
		$dbm->connect();
	}

	public function getDbConnectionData()
	{
		$data = array();
		foreach ($this->getContainer()->getKernelObject('Config')->getAllGlobals() as $key => $value) {
			$keyParts = explode('.', $key);
			if (count($keyParts) > 1 && $keyParts[0] == 'database') {
				if (count($keyParts) == 1) {
					$keyParts[2] = $keyParts[1];
					$keyParts[1] = 'default';
				}
				$data[$keyParts[1]][$keyParts[2]] = $value;
			}
		}
		return $data;
	}
}
