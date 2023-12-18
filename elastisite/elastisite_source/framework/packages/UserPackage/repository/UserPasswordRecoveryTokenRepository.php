<?php
namespace framework\packages\UserPackage\repository;

use framework\component\parent\DbRepository;
use framework\packages\UserPackage\entity\FBSUser;
use framework\packages\UserPackage\entity\UserAccount;
use framework\packages\UserPackage\entity\Person;
use framework\kernel\utility\BasicUtils;

class UserPasswordRecoveryTokenRepository extends DbRepository
{
    public function findByUserAccountId($userAccountId)
    {
        $stm = "SELECT token
                FROM user_password_recovery_token
                WHERE user_account_id = :userAccountId and redeemed_at is null ";
        $params = array(':userAccountId' => $userAccountId);
        $dbm = $this->getDbManager();
        $ret = $dbm->findOne($stm, $params);
        $ret = $ret ? $ret['token'] : null;
        return $ret;
    }

    public function redeem($userAccountId)
    {
        $stm = "UPDATE user_password_recovery_token
                SET redeemed_at = now()
                WHERE user_account_id = :userAccountId ";
        $params = array(':userAccountId' => $userAccountId);
        $dbm = $this->getDbManager();
        $dbm->execute($stm, $params);
    }

    public function createToken()
    {
        $token = BasicUtils::generateRandomString(32, 'alphanum_mixed');
        return $this->isToken($token) ? $this->createToken() : $token;
    }

    public function insertToken($token, $userAccountId)
    {
        $stm = "INSERT INTO user_password_recovery_token (user_account_id, token) VALUES (:userAccountId, :token)";
        $params = array(':userAccountId' => $userAccountId, ':token' => $token);
        $dbm = $this->getDbManager();
        $dbm->execute($stm, $params);
    }

    public function isToken($token)
    {
        $stm = "SELECT id FROM user_password_recovery_token WHERE token = :token ";
        $params = array(':token' => $token);
        $dbm = $this->getDbManager();
        $ret = $dbm->findOne($stm, $params);
        return $ret;
    }
}
