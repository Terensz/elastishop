<?php
namespace framework\packages\UserPackage\repository;

use framework\component\parent\DbRepository;
use framework\kernel\utility\BasicUtils;

class UserLoginTokenRepository extends DbRepository
{
    public function findByUserId($userId)
    {
        $stm = "SELECT token, created_at
                FROM user_login_token
                WHERE user_id = :userId and redeemed_at is null ";
        $params = array(':userId' => $userId);
        $dbm = $this->getDbManager();
        $ret = $dbm->findOne($stm, $params);

        if (!$ret) {
            return array(
                'result' => false,
                'message' => 'invalid.login.token'
            );
        }

        $loginTokenValidityInterval = $this->getProjectData('loginTokenValidityInterval');
        $expirationTime = new \DateTime($ret['created_at']);
        if ($loginTokenValidityInterval) {
            $expirationTime = $expirationTime->add(new \DateInterval('PT'.$loginTokenValidityInterval.'S'));
        }
        if ($this->getCurrentTimestamp() > $expirationTime) {
            return array(
                'result' => false,
                'message' => 'expired.login.token'
            );
        }

        return array(
            'result' => $ret['token'],
            'message' => null
        );
        // return $ret;
    }

    public function redeem($userId)
    {
        $stm = "UPDATE user_login_token
                SET redeemed_at = now()
                WHERE user_id = :userId ";
        $params = array(':userId' => $userId);
        $dbm = $this->getDbManager();
        $dbm->execute($stm, $params);
    }

    public function removeAllTokens($userId)
    {
        $stm = "DELETE FROM user_login_token WHERE user_id = :userId ";
        $params = array(':userId' => $userId);
        $dbm = $this->getDbManager();
        $dbm->execute($stm, $params);
    }

    public function createToken()
    {
        $token = BasicUtils::generateRandomString(8, 'alphanum_mixed');
        return $this->isToken($token) ? $this->createToken() : $token;
    }

    public function insertToken($token, $userId)
    {
        $stm = "INSERT INTO user_login_token (user_id, token) VALUES (:userId, :token)";
        $params = array(':userId' => $userId, ':token' => $token);
        $dbm = $this->getDbManager();
        return $dbm->execute($stm, $params);
    }

    public function isToken($token)
    {
        $stm = "SELECT id FROM user_login_token WHERE token = :token ";
        $params = array(':token' => $token);
        $dbm = $this->getDbManager();
        $ret = $dbm->findOne($stm, $params);
        return $ret;
    }
}
