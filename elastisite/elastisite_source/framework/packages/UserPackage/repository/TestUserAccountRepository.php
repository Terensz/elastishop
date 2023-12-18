<?php
namespace framework\packages\UserPackage\repository;

use framework\component\parent\DbRepository;
use framework\packages\UserPackage\entity\FBSUser;
use framework\packages\UserPackage\entity\UserAccount;
use framework\packages\UserPackage\entity\Person;
use framework\packages\UserPackage\repository\PersonRepository;
use framework\kernel\utility\BasicUtils;
use framework\packages\UserPackage\repository\UserAccountRegistrationTokenRepository as TokenRepo;

class TestUserAccountRepository extends DbRepository
{
    public function makeObject($rawData)
    {
        $this->getContainer()->wireService('UserPackage/entity/UserAccount');
        $this->getContainer()->wireService('UserPackage/repository/PersonRepository');

        $userAccount = new UserAccount();
        $personRepo = new PersonRepository();
        
        $userAccount->setId(isset($rawData['id']) ? $rawData['id'] : null);
        $userAccount->setCode(isset($rawData['code']) ? $rawData['code'] : null);
        $userAccount->setStatus(isset($rawData['status']) ? $rawData['status'] : null);
        $person = $personRepo->findOneBy(['user_account_id' => $userAccount->getId()]);
        $person->setUserAccount($userAccount);
        $userAccount->setPerson($person);
        return $userAccount;
    }

    public function storeUserRegistration($userAccount)
    {
        $this->getContainer()->wireService('UserPackage/repository/UserAccountRegistrationTokenRepository');
        $dbm = $this->getDbManager();

        $stm0 = "DELETE FROM username_reservation WHERE visitor_code = :visitorCode";
        $params0 = ['visitorCode' => $this->getSession()->get('visitorCode')];
        $dbm->execute($stm0, $params0);

        $userAccount->setCode($this->createCode());
        $userAccount = $this->insert($userAccount);

        $tokenRepo = new TokenRepo();
        $token = $tokenRepo->createToken();
        $tokenRepo->insertToken($token, $userAccount->getId());
        // dump('storeUserRegistration!');
        // dump($userAccount);
        // dump($token);
        return $userAccount;
    }

    public function createCode()
    {
        $code = BasicUtils::generateRandomString(8, 'alphanum_small');
        $isCode = $this->isCode($code);
        return $isCode ? $this->createCode() : $code;
    }

    public function isCode($code)
    {
        $stm = "SELECT id FROM user_account WHERE code = :code ";
        $params = array(':code' => $code);
        $dbm = $this->getDbManager();
        $ret = $dbm->findOne($stm, $params);
        return $ret;
    }

    // public function getCode($userAccountId)
    // {
    //     $stm = "SELECT code FROM user_account WHERE id = :id ";
    //     $params = [':id' => $userAccountId];
    //     $dbm = $this->getDbManager();
    //     $ret = $dbm->findOne($stm, $params);
    //     return $ret;
    // }

    public function getQueryBase($queryType)
    {
        $select = $queryType == 'result' ? "SELECT a.id as \"id\", a.code as \"code\"
                , a.status as \"status\", a.registered_at as \"registered_at\"
                , p.id as \"person_id\", p.full_name as \"name\", p.username as \"username\"
                , p.password as \"password\", p.email as \"email\", p.mobile as \"mobile\""
                : "SELECT count(*) as row_count ";

        return $select."
                FROM user_account a
                LEFT JOIN person p on p.user_account_id = a.id";
    }

    public function findByUsername($username)
    {
        $stm = $this->getQueryBase('result')."
                WHERE p.username = :username
        ";
        $params = array(':username' => $this->encrypt($username));
        $dbm = $this->getDbManager();
        $rawUserAccount = $dbm->findOne($stm, $params);
        return $this->makeObject($rawUserAccount);
    }

    public function findByCode($code)
    {
        // $stm = "SELECT id FROM user_account
        //         WHERE code = :code
        // ";
        $stm = $this->getQueryBase('result')."
                WHERE a.code = :code
        ";
        $params = array(':code' => $code);
        $dbm = $this->getDbManager();
        $rawUserAccount = $dbm->findOne($stm, $params);
        return $this->makeObject($rawUserAccount);
    }

    public function getFilteredResult($filter = null, $options = null)
    {
        $queryType = isset($options['queryType']) ? $options['queryType'] : 'result';
        $page = isset($options['page']) ? $options['page'] : 1;
        $limit = isset($options['limit']) ? $options['limit'] : 5;
        $pageFirstIndex = (($page - 1) * $limit);
        // $filter = array(
        //     'searchAccountId'   => $searchAccountId,
        //     'searchName'        => $searchName,
        //     'searchEmail'       => $searchEmail,
        //     'searchUsername'    => $searchUsername
        // );
        $where = array();
        if (isset($filter['searchAccountCode']) && $filter['searchAccountCode'] != '') {
            $where[] = "a.code like '%".$filter['searchAccountCode']."%'";
        }
        if (isset($filter['searchName']) && $filter['searchName'] != '') {
            $where[] = "p.full_name = '".$this->encrypt($filter['searchName'])."'";
        }
        if (isset($filter['searchEmail']) && $filter['searchEmail'] != '') {
            $where[] = "p.email = '".$this->encrypt($filter['searchEmail'])."'";
        }
        if (isset($filter['searchUsername']) && $filter['searchUsername'] != '') {
            $where[] = "p.username = '".$this->encrypt($filter['searchUsername'])."'";
        }
        if (isset($filter['searchStatus']) && $filter['searchStatus'] != 'all') {
            $where[] = "a.status = '".$filter['searchStatus']."'";
        }
        $where = $where == array() ? '' : ' where '.implode(' and ',$where);
        $stm = $this->getQueryBase($queryType)."
                ".$where."
                ".($queryType == 'result' ? "LIMIT {$pageFirstIndex}, {$limit}" : "")."
        ";

        $dbm = $this->getDbManager();
        $userAccountsRaw = $dbm->findAll($stm);

        // if ($queryType == 'result') {
        //     dump($this->assembleUserAccounts($userAccountsRaw));exit;
        // }

        return $queryType == 'result'
            ? $this->assembleUserAccounts($userAccountsRaw)
            : $userAccountsRaw[0]['row_count'];
    }

    public function getTotalCount($filter)
    {
        return $this->getFilteredResult($filter, ['queryType' => 'count']);
        // $stm = "SELECT count(*) as total_count FROM user_account";
        // $dbm = $this->getDbManager();
        // return (int)$dbm->findOne($stm)['total_count'];
    }

    public function assembleUserAccounts($userAccountsRaw)
    {
        $userAccounts = array();
        // $updatedUserAccountsRaw = array();
        for ($i = 0; $i < count($userAccountsRaw); $i++) {
            if (!$userAccountsRaw[$i]['id']) {
                // array_splice($i);
                continue;
            }

            $userAccounts[] = array(
                'id' => $userAccountsRaw[$i]['id'],
                'registeredAt' => $userAccountsRaw[$i]['registered_at'],
                'status' => $userAccountsRaw[$i]['status'],
                'personId' => $userAccountsRaw[$i]['person_id'],
                'name' => $this->decrypt($userAccountsRaw[$i]['name']),
                'username' => $this->decrypt($userAccountsRaw[$i]['username']),
                'password' => $this->decrypt($userAccountsRaw[$i]['password']),
                'email' => $this->decrypt($userAccountsRaw[$i]['email']),
                'mobile' => $this->decrypt($userAccountsRaw[$i]['mobile'])
            );
        }

        return $userAccounts;
    }

    public function removeUserRegistration($id)
    {

    }

    // public function store($userAccount)
    // {
    //     return $userAccount->getId() ? $this->update($userAccount) : $this->insert($userAccount);
    // }

    // public function update($userAccount)
    // {
    //     $dbm = $this->getDbManager();
    //     $this->getContainer()->wireService('UserPackage/repository/PersonRepository');
    //     $personRepo = new PersonRepository();
    //     $person = $personRepo->findOneBy(['user_account_id' => $userAccount->getId()]);
    //     $personRepo->store($person);
    //     $userAccount->getPerson()->setId($personId ? $personId['person_id'] : null);
    //     $userAccount->getPerson()->setPassword(md5($userAccount->getPerson()->getPassword()));

    //     // $storedUserAccount = $this->makeObject($this->find($userAccount->getId()));

    //     // dump($storedUserAccount);
    //     // dump($userAccount);exit;

    //     $stm1 = "UPDATE user_account set status = :status where id = :id";
    //     $params1 = ['id' => $userAccount->getId(), 'status' => $userAccount->getStatus()];
    //     $id1 = $dbm->execute($stm1, $params1);

    //     return $userAccount;
    // }

    // public function insertUserAccount($userAccount)
    // {
    //     $dbm = $this->getDbManager();

    //     $stm1 = "INSERT INTO user_account (code, status) VALUES(:code , :status)";
    //     $params1 = ['code' => $userAccount->getCode(), 'status' => false];
    //     $id = $dbm->execute($stm1, $params1);
    //     $userAccount->setId($id);
    //     $userAccount->getPerson()->setId($personId);
    //     return $userAccount;
    // }
}
