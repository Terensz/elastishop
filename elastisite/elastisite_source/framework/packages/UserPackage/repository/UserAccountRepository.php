<?php
namespace framework\packages\UserPackage\repository;

use framework\component\parent\DbRepository;
use framework\packages\UserPackage\entity\FBSUser;
use framework\packages\UserPackage\entity\UserAccount;
use framework\packages\UserPackage\entity\Person;
use framework\packages\UserPackage\entity\User;
use framework\packages\UserPackage\repository\PersonRepository;
use framework\kernel\utility\BasicUtils;
use framework\packages\UserPackage\repository\UserAccountRegistrationTokenRepository as TokenRepo;
use framework\packages\UserPackage\service\Permission;

class UserAccountRepository extends DbRepository
{
    public function __construct()
    {
        $this->getContainer()->wireService('UserPackage/entity/UserAccount');
        $this->getContainer()->wireService('UserPackage/entity/Person');
        $this->getContainer()->wireService('UserPackage/entity/Address');
        $this->getContainer()->wireService('UserPackage/repository/PersonRepository');
    }

    public function authenticateByToken($controller, $authToken, $browserFingerprint, $skipBrowserFingerprintCheck = false)
    {
        $authTokenData = $authToken ? $controller->getAuthTokenData($authToken) : null;

        $id = null;
        if (($skipBrowserFingerprintCheck && $authTokenData) || ($browserFingerprint && $authTokenData && $authTokenData->browserFingerprint == $browserFingerprint)) {
            $id = $authTokenData->id;
        }

        return $id ? $this->findUser(['id' => $id]) : $this->createLoggedOutUser();
    }

    public function createLoggedOutUser()
    {
        $this->getContainer()->setService('UserPackage/repository/UserRepository');
        $userRepo = $this->getContainer()->getService('UserRepository');

        return $userRepo->createLoggedOutUser();
    }

    public function makeObject($rawData)
    {
        $userAccount = new UserAccount();
        $personRepo = new PersonRepository();
        
        $userAccount->setId(isset($rawData['id']) ? $rawData['id'] : null);
        $userAccount->setCode(isset($rawData['code']) ? $rawData['code'] : null);
        $userAccount->setStatus(isset($rawData['status']) ? $rawData['status'] : null);
        $person = $personRepo->findOneBy(['conditions' => [['key' => 'user_account_id', 'value' => $userAccount->getId()]]]);
        $person->setUserAccount($userAccount);
        $userAccount->setPerson($person);

        return $userAccount;
    }

    public function storeUserRegistration($userAccount)
    {
        $this->getContainer()->wireService('UserPackage/repository/UserAccountRegistrationTokenRepository');
        $dbm = $this->getDbManager();

        $userAccount->getNewsletterSubscription()->setUserAccount($userAccount);

        $stm0 = "DELETE FROM username_reservation WHERE visitor_code = :visitorCode";
        $params0 = ['visitorCode' => $this->getSession()->get('visitorCode')];
        $dbm->execute($stm0, $params0);

        $userAccount->setCode($this->createCode());
        $userAccount->setRegisteredAt($this->getCurrentTimestamp());
        $userAccount->setStatus(2);
        $userAccount->getPerson()->setUserAccount($userAccount);
        $userAccount->getPerson()->setPassword(md5($userAccount->getPerson()->getPassword()));
        // dump($userAccount);
        $userAccount = $this->store($userAccount);

        $tokenRepo = new TokenRepo();
        $token = $tokenRepo->createToken();
        // dump($userAccount);exit;
        $tokenRepo->insertToken($token, $userAccount->getId());
        // dump('storeUserRegistration!');
        // dump($userAccount);
        // dump($token);
        return $userAccount;
    }

    public function createCode()
    {
        $code = BasicUtils::generateRandomString(12, 'alphanum_small');
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
                INNER JOIN person p on p.user_account_id = a.id ";
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

    // public function findByCode($code)
    // {
    //     // $stm = "SELECT id FROM user_account
    //     //         WHERE code = :code
    //     // ";
    //     $stm = $this->getQueryBase('result')."
    //             WHERE a.code = :code
    //     ";
    //     $params = array(':code' => $code);
    //     $dbm = $this->getDbManager();
    //     $rawUserAccount = $dbm->findOne($stm, $params);
    //     return $this->makeObject($rawUserAccount);
    // }

    public function getFilteredResult($filter = null, $options = null)
    {
        //dump($filter);exit;
        $queryType = isset($options['queryType']) ? $options['queryType'] : 'result';
        $page = isset($filter['currentPage']) ? $filter['currentPage'] : 1;
        $limit = isset($filter['maxResults']) ? $filter['maxResults'] : 5;
        $pageFirstIndex = (($page - 1) * $limit);
        // $filter = array(
        //     'searchAccountId'   => $searchAccountId,
        //     'searchName'        => $searchName,
        //     'searchEmail'       => $searchEmail,
        //     'searchUsername'    => $searchUsername
        // );
        // dump($filter);
        $where = array();
        $params = array();
        if ($this->filterHasCondition($filter, 'code') && $this->getFilterConditionValue($filter, 'code') != '') {
            $where[] = "a.code like :code ";
            $params[':code'] = '%'.$this->getFilterConditionValue($filter, 'code').'%';
        }
        if ($this->filterHasCondition($filter, 'full_name') && $this->getFilterConditionValue($filter, 'full_name') != '') {
            $where[] = "p.full_name = :full_name ";
            $params[':full_name'] = $this->encrypt($this->getFilterConditionValue($filter, 'full_name'));
        }
        if ($this->filterHasCondition($filter, 'email') && $this->getFilterConditionValue($filter, 'email') != '') {
            $where[] = "p.email = :email ";
            $params[':email'] = $this->encrypt($this->getFilterConditionValue($filter, 'email'));
        }
        if ($this->filterHasCondition($filter, 'username') && $this->getFilterConditionValue($filter, 'username') != '') {
            $where[] = "p.username = :username ";
            $params[':username'] = $this->encrypt($this->getFilterConditionValue($filter, 'username'));
        }
        if ($this->filterHasCondition($filter, 'status') && $this->getFilterConditionValue($filter, 'status') != '') {
            $where[] = "a.status = :status ";
            $params[':status'] = $this->getFilterConditionValue($filter, 'status');
        }
        $where = $where == array() ? '' : ' where '.implode(' and ',$where);
        $stm = $this->getQueryBase($queryType)."
                ".$where."
                ".($queryType == 'result' ? "LIMIT {$pageFirstIndex}, {$limit}" : "")."
        ";

        $dbm = $this->getDbManager();
        // dump($params);
        $userAccountsRaw = $dbm->findAll($stm, $params);
        // if ($queryType == 'result') {
        //     dump($this->assembleUserAccounts($userAccountsRaw));exit;
        // }

        $result = $queryType == 'result'
            ? $this->assembleUserAccounts($userAccountsRaw)
            : $userAccountsRaw[0]['row_count'];
        // dump($stm);//exit;
        return $result;
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

    public function getPermissions()
    {
        $permissionGroups = Permission::BASIC_USER_PERMISSION_GROUPS;
        return Permission::getPermissions($permissionGroups);
    }

    public function findUser($params)
    {
        if (isset($params['id']) && empty($params['id'])) {
            return null;
        }

        $user = $this->findDatabaseUser($params);

        if ($user && $user instanceof User) {
            return $user;
        }

        $this->addSystemMessage('login.error', 'error', 'login');

        return null;
    }

    public function findDatabaseUser($params)
    {
        $this->wireService('UserPackage/repository/UserRepository');
        $userRepo = new UserRepository();

        if (isset($params['id']) && !isset($params['username']) && !isset($params['password'])) {
            $stm1 = "SELECT p.*
                    FROM person p
                    INNER JOIN user_account ua ON ua.id = p.user_account_id
                    WHERE ua.id = :id and ua.status = 1 ";
        }
        // elseif (isset($params['findByOnlyUsername']) && $params['findByOnlyUsername'] === true) {
        //     $params['username'] = $this->encrypt($params['username']);
        //     $stm1 = "SELECT p.*
        //             FROM person p
        //             INNER JOIN user_account ua ON ua.id = p.user_account_id 
        //             WHERE p.username = :username ua.status = 1 ";
        // }
        else {
            $params['username'] = $this->encrypt($params['username']);
            $params['password'] = $this->encrypt($params['password']);
            $stm1 = "SELECT p.*
                    FROM person p
                    INNER JOIN user_account ua ON ua.id = p.user_account_id 
                    WHERE p.username = :username and p.password = :password and ua.status = 1 ";
        }
        $personData = $this->getDbManager()->findOne($stm1, $params);

        if ($personData) {
            $stm2 = "SELECT * FROM user_account WHERE id = :user_account_id AND status = 1 ";
            $userAccountData = $this->getDbManager()->findOne($stm2, ['user_account_id' => $personData['user_account_id']]);

            if (!$userAccountData) {
                return null;
            }

            $user = $this->makeUserFromDatabaseUser($personData, $userAccountData);
            $this->getSession()->set('userId', $user->getId());
            $this->getSession()->set('userStorageType', 'Db');
            if ($this->getRequest()->get('LoginWidget_username') && $this->getRequest()->get('LoginWidget_username') != '') {
                $this->addSystemMessage('login.success', 'success', 'login');
            }

            if ($user->getStatus() != 1) {
                $this->addSystemMessage('user.inactive', 'error', 'login');
                return $userRepo->createLoggedOutUser();
            }
            else {
                foreach (Permission::BASIC_USER_PERMISSION_GROUPS as $basicUserPermissionGroup) {
                    $user->addPermissionGroup($basicUserPermissionGroup);
                }
                if ((int)$userAccountData['is_tester'] === 1) {
                    $user->addPermissionGroup(Permission::TESTER_PERMISSION_GROUP);
                    $user->addPermissionGroup(Permission::WEBSHOP_TESTER_PERMISSION_GROUP);
                }
            }

            return $user;
        }
        else {
            return null;
        }
    }

    public function makeUserFromDatabaseUser($personData, $userAccountData)
    {
        $userAccount = new UserAccount();
        // dump($personData);
        // dump($userAccountData);exit;
        if (!$userAccountData) {

        }
        foreach ($userAccountData as $key => $value) {
            $setter = 'set'.ucfirst(BasicUtils::snakeToCamelCase($key));
            $userAccount->$setter($value);
        }

        $userAccount = $userAccount->getRepository()->find($userAccount->getId());
        // dump($userAccount);exit;

        $user = new User();
        $user->setId($userAccount->getId());
        $user->setUserAccount($userAccount);
        $user->setName($this->decrypt($personData['full_name']));
        $user->setUsername($this->decrypt($personData['username']));
        $user->setPassword($this->decrypt($personData['password']));
        $user->setEmail($this->decrypt($personData['email']));
        $user->setMobile($this->decrypt($personData['mobile']));
        $user->setType(User::TYPE_USER);
        $user->setStatus($userAccount->getStatus());
        
        return $user;
    }

    public function getGridDataFilteredQuery($filter)
    {
        $whereClause = $this->createWhereClauseFromFilter($filter && isset($filter['conditions']) ? $filter['conditions'] : null);
        return array(
            'statement' => "SELECT * FROM (SELECT maintable.id, p.full_name, p.username, p.email, p.mobile, maintable.is_tester, maintable.status
                            FROM user_account maintable
                            INNER JOIN person p ON p.user_account_id = maintable.id) table0
                            ".$whereClause['whereStr']." ",
            'params' => $whereClause['params']
        );
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
