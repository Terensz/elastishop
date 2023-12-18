<?php
namespace framework\packages\StaffPackage\repository;

use App;
use framework\component\parent\DbRepository;
use framework\kernel\utility\BasicUtils;
use framework\packages\UserPackage\repository\UserRepository;
use framework\packages\UserPackage\service\Permission;

class StaffMemberRepository extends DbRepository
{
    const TABLE_STAFF_MEMBER = 'staff_member';
    const TABLE_STAFF_MEMBER_STAT_PAGE = 'staff_member_stat_page';

    public function isDeletable($id)
    {
        return false;
    }

    public function getGridDataFilteredQuery($filter)
    {
        $whereClause = $this->createWhereClauseFromFilter($filter ? $filter['conditions'] : null);
        // dump($whereClause);exit;
        return array(
            'statement' => "SELECT * FROM (
                                SELECT 
                                    maintable.id as id,
                                    maintable.status as status,
                                    maintable.organization as organization, 
                                    maintable.division as division,
                                    p.full_name as full_name,
                                    p.username as username,
                                    p.email as email
                                FROM staff_member maintable
                                LEFT JOIN person p ON p.id = maintable.person_id 
                                WHERE maintable.website = '".App::getWebsite()."'
                            ) table0
                            ".$whereClause['whereStr']." ",
            'params' => $whereClause['params']
        );
    }

    public function getGridDataFilteredQuery2($filter)
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

    public static function createCode($tableName)
    {
        $dbm = App::getContainer()->getDbManager();
        if (!$dbm->tableExists($tableName)) {
            return null;
        }
        $code = BasicUtils::generateRandomString(36, BasicUtils::RANDOM_STRING_TYPE_ALPHANUM_MIXED);
        $isCode = self::isCode($code, $tableName);

        return $isCode ? self::createCode($tableName) : $code;
    }

    public static function isCode($code, $tableName)
    {
        $stm = "SELECT id FROM ".$tableName." WHERE code = :code ";
        $params = array(':code' => $code);
        $dbm = App::getContainer()->getDbManager();
        $ret = $dbm->findOne($stm, $params);

        return $ret;
    }

    public static function findAndAuthStaffMember($staffMemberCode, $loginParams)
    {
        // dump($params);exit;
        if (!isset($loginParams['username']) || !isset($loginParams['password'])) {
            return null;
        }
        $stm = "SELECT id FROM staff_member WHERE code = :code ";
        $params = array('code' => $staffMemberCode);
        $dbm = App::getContainer()->getDbManager();
        $ret = $dbm->findOne($stm, $params); 
        if (!$ret || !isset($ret['id'])) {
            return null;
        }
        $repo = new self();
        $staffMember = $repo->find($ret['id']);
        if ($staffMember) {
            App::getContainer()->wireService('UserPackage/repository/UserRepository');
            $userRepo = new UserRepository();
            $user = $userRepo->createLoggedOutUser();
            $user = StaffMemberRepository::fillUserFromStaffMember($staffMember,$user);

            if ($staffMember->getPerson()->getUsername() == $loginParams['username'] && $staffMember->getPerson()->getPassword() == $loginParams['password']) {
                App::getContainer()->getSession()->set('userId', $staffMember->getId());
                App::getContainer()->getSession()->set('userStorageType', 'Db/staffMember');
                App::getContainer()->getSession()->set('user', serialize($user));
                App::getContainer()->addSystemMessage('login.success', 'success', 'login');
                return $user;
            }
        }

        // dump($staffMember);exit;

        return null;
    }

    public static function fillUserFromStaffMember($staffMember, $user)
    {
        $user->setId($staffMember->getId());
        $user->setPermissionGroups(Permission::BASIC_STAFF_MEMBER_PERMISSION_GROUPS);
        $user->setName($staffMember->getPerson()->getFullName());
        $user->setUsername($staffMember->getPerson()->getUsername());
        $user->setEmail($staffMember->getPerson()->getEmail());
        $user->setMobile($staffMember->getPerson()->getMobile());
        $user->setType(Permission::STAFF_MEMBER_PERMISSION_GROUP);

        return $user;
        // dump($user);exit;
    }
}
