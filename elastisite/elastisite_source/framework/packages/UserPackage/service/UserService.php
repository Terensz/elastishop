<?php
namespace framework\packages\UserPackage\service;

use framework\component\parent\Service;
use framework\packages\UserPackage\entity\User;
use framework\component\exception\ElastiException;
use framework\packages\UserPackage\repository\UserLoginTokenRepository as TokenRepo;
use framework\packages\ToolPackage\service\TextProcessor;
use framework\packages\ToolPackage\service\Mailer;

class UserService extends Service
{
    // public function removeTemporaryAccount()
    // {
    //     $this->setService('UserPackage/repository/TemporaryAccountRepository');
    //     $tempAccRepo = $this->getService('TemporaryAccountRepository');
    //     $this->setService('UserPackage/repository/TemporaryPersonRepository');
    //     $tempPersonRepo = $this->getService('TemporaryPersonRepository');
    //     $this->setService('UserPackage/repository/AddressRepository');
    //     $addressRepo = $this->getService('AddressRepository');

    //     $tempAccs = $tempAccRepo->findBy(['conditions' => [['key' => 'visitor_code', 'value' => $this->getSession()->get('visitorCode')]]]);

    //     foreach ($tempAccs as $tempAcc) {
    //         $tempPerson = $tempAcc->getTemporaryPerson();
    //         $addressRepo->remove($tempPerson->getAddress()->getId());
    //         $tempPersonRepo->remove($tempPerson);
    //         $tempAccRepo->remove($tempAcc->getId());
    //     }
    // }

    public function sendLoginTokenByEmail($user)
    {
        $this->wireService('UserPackage/repository/UserLoginTokenRepository');
        $this->wireService('ToolPackage/service/Mailer');
        $this->wireService('ToolPackage/service/TextProcessor');

        $tokenRepo = new TokenRepo();
        $tokenRepo->removeAllTokens($user->getId());
        $tokenCode = $tokenRepo->createToken();
        $tokenRepo->insertToken($tokenCode, $user->getId());
        $token = $tokenRepo->findOneBy(['conditions' => [['key' => 'user_id', 'value' => $user->getId()]]]);

        $mailer = new Mailer();
        $mailer->setSubject(trans('login.token').' - '.$user->getName());
        $mailer->textAssembler->setPackage('UserPackage');
        $mailer->textAssembler->setReferenceKey('userLoginToken');
        $mailer->textAssembler->setPlaceholdersAndValues([
            'name' => $user->getName(),
            'domain' => '<a href="'.$this->getUrl()->getHttpDomain().'">'.$this->getUrl()->getHttpDomain().'</a>',
            'token' => $token->getToken()
        ]);
        $mailer->textAssembler->create();
        $mailer->setBody($mailer->textAssembler->getView());
        $mailer->addRecipient($user->getEmail(), $user->getName());
        return $mailer->send();
    }

    public function checkLoginToken($user, $loginTokenReqest)
    {
        $this->wireService('UserPackage/repository/UserLoginTokenRepository');

        $tokenRepo = new TokenRepo();
        return $tokenRepo->findByUserId($user->getId());
    }
}
