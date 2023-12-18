<?php
namespace framework\packages\UserPackage\repository;

use framework\component\parent\DbRepository;
use framework\kernel\utility\BasicUtils;
use framework\packages\UserPackage\entity\TestPerson;

class TestPersonRepository extends DbRepository
{
    public function collectRecordData($filter, $limit = null, $queryType = 'result')
    {
        $this->getContainer()->wireService('UserPackage/repository/TestUserAccountRepository');
        $dbm = $this->getDbManager();
        $params = array();
        foreach ($filter as $filterKey => $filterValue) {
            $params[$filterKey] = $filterValue;
        }
        if (!$this->checkFilter($filter)) {
            return array();
        }
        $stm = "SELECT id, full_name, username, password, email, mobile
        FROM test_person
        ".($params ? "WHERE ".BasicUtils::concatArray($params) : "");
        $personRecordData = $dbm->findAll($stm, $params);
        return $personRecordData;
    }

    public function makeEntityFromRecordData($recordData, $entity = null)
    {
        $this->getContainer()->wireService('UserPackage/entity/TestPerson');
        $person = new TestPerson();
        if (!$recordData) {
            return $person;
        }
        $person->setId(isset($recordData['id']) ? $recordData['id'] : null);
        // $person->setUserAccountId(isset($rawData['user_account_id']) ? $rawData['user_account_id'] : null);
        $person->setFullName(isset($recordData['full_name']) ? $this->decrypt($recordData['full_name']) : null);
        $person->setUsername(isset($recordData['username']) ? $this->decrypt($recordData['username']) : null);
        $person->setPassword(isset($recordData['password']) ? $this->decrypt($recordData['password']) : null);
        $person->setEmail(isset($recordData['email']) ? $this->decrypt($recordData['email']) : null);
        $person->setMobile(isset($recordData['mobile']) ? $this->decrypt($recordData['mobile']) : null);
        return $person;
    }

    public function store($person)
    {
        if (!is_object($person)) {
            return false;
        }
        return $person->getId() ? $this->update($person) : $this->insert($person);
    }

    public function checkUsernameAvailability($username)
    {
        $counter = 0;
        $this->setService('UserPackage/repository/FBSUserRepository');
        $FBSrepo = $this->getService('FBSUserRepository');
        $FBSresult = $FBSrepo->findBy(['username' => $username]);
        if (count($FBSresult) > 0) {
            return false;
        }

        if (!$this->checkDbUsernameAvailability($username)) {
            return false;
        }

        return true;
    }

    public function checkDbUsernameAvailability($username)
    {
        $dbm = $this->getDbManager();

        $stm1 = "SELECT count(*) as username_count
                FROM person p
                WHERE p.username = :encoded_username";
        $params1 = ['encoded_username' => $this->encrypt($username)];
        $usernameCount1 = $dbm->findOne($stm1, $params1)['username_count'];

        $stm2 = "SELECT count(*) as username_count
                FROM username_reservation r
                WHERE r.username = :encoded_username AND r.visitor_code != :visitor_code";
        $params2 = ['encoded_username' => $this->encrypt($username), 'visitor_code' => $this->getSession()->get('visitorCode')];
        $usernameCount2 = $dbm->findOne($stm2, $params2)['username_count'];

        return ($usernameCount1 + $usernameCount2) > 0 ? false : true;
    }

    public function reserveUsername($username)
    {
        $dbm = $this->getDbManager();

        $stm1 = "DELETE FROM username_reservation WHERE visitor_code = :visitor_code";
        $params1 = ['visitor_code' => $this->getSession()->get('visitorCode')];
        $dbm->execute($stm1, $params1);

        $stm2 = "INSERT INTO username_reservation (visitor_code, username) VALUES (:visitor_code, :encoded_username)";
        $params2 = ['visitor_code' => $this->getSession()->get('visitorCode'), 'encoded_username' => $this->encrypt($username)];
        $dbm->execute($stm2, $params2);
    }
}
