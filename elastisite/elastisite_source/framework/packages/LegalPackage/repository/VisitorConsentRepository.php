<?php
namespace framework\packages\LegalPackage\repository;

use App;
use framework\component\parent\DbRepository;
use framework\packages\LegalPackage\entity\VisitorConsent;
use framework\packages\LegalPackage\entity\VisitorConsentAcceptance;
use framework\packages\UserPackage\repository\UserAccountRepository;

class VisitorConsentRepository extends DbRepository
{
    // public function findByUser_OLD($userAccountId, $visitorCode) : VisitorConsent
    // {
    //     if ($userAccountId && $userAccountId != 0) {
    //         $where = ' user_account_id = :user_account_id ';
    //         $params = array('user_account_id' => $userAccountId);
    //     }
    //     else {
    //         $where = ' visitor_code = :visitor_code ';
    //         $params = array('visitor_code' => $visitorCode);
    //     }
    //     $where = ' website = :website AND '.$where;
    //     $params = array_merge([
    //         'website' => App::getWebsite(),
    //         // 'requested_for' => $requestCategory
    //     ], $params);
    //     $stm = "SELECT id FROM visitor_consent WHERE ".$where;
    //     $dbm = $this->getDbManager();

    //     $idQuery = $dbm->findOne($stm, $params);
    //     $visitorConsent = null;
    //     if ($idQuery) {
    //         $visitorConsent = $this->find($idQuery['id']);
    //     }

    //     dump($visitorConsent);exit;

    //     if (!$visitorConsent) {
    //         $userAccount = null;
    //         $visitorConsent = new VisitorConsent();
    //         $visitorConsent->setVisitorCode(App::getContainer()->getSession()->get('visitorCode'));
    //         if ($userAccountId) {
    //             $userAccountRepository = new UserAccountRepository();
    //             $userAccount = $userAccountRepository->find($userAccountId);
    //         }
    //         $visitorConsent->setUserAccount($userAccount ? : null);
    //         $visitorConsent = $this->store($visitorConsent);
    //     }

    //     return $visitorConsent;
    // }

    public function findByUserOrVisitorCode($userAccountId, $visitorCode) : ? VisitorConsent
    {
        $visitorConsent = $this->findByUser($userAccountId);
        if ($visitorConsent) {
            return $visitorConsent;
        }

        App::getContainer()->wireService('UserPackage/repository/UserAccountRepository');

        $visitorConsent = $this->findByVisitor($visitorCode);
        if ($visitorConsent) {
            if ($userAccountId && !$visitorConsent->getUserAccount()) {
                $userAccountRepository = new UserAccountRepository();
                $userAccount = $userAccountRepository->find($userAccountId);
                if ($userAccount) {
                    $visitorConsent->setUserAccount($userAccount);
                    $visitorConsent = $this->store($visitorConsent);
                }
            }

            return $visitorConsent ? : null;
        }

        if (!$visitorConsent) {
            $userAccount = null;
            $visitorConsent = new VisitorConsent();
            $visitorConsent->setVisitorCode(App::getContainer()->getSession()->get('visitorCode'));
            if ($userAccountId) {
                $userAccountRepository = new UserAccountRepository();
                $userAccount = $userAccountRepository->find($userAccountId);
            }
            $visitorConsent->setUserAccount($userAccount ? : null);
            $visitorConsent = $this->store($visitorConsent);
        }

        return $visitorConsent ? : null;
    }

    public function findByUser(int $userAccountId = null) : ? VisitorConsent
    {
        if (!$userAccountId) {
            return null;
        }
        $visitorConsent = $this->findOneBy(['conditions' => [
            ['key' => 'website', 'value' => App::getWebsite()],
            ['key' => 'user_account_id', 'value' => $userAccountId]
        ]]);

        return $visitorConsent;
    }

    public function findByVisitor(string $visitorCode = null) : ? VisitorConsent
    {
        if (!$visitorCode) {
            return null;
        }
        $visitorConsent = $this->findOneBy(['conditions' => [
            ['key' => 'website', 'value' => App::getWebsite()],
            ['key' => 'visitor_code', 'value' => $visitorCode]
        ]]);

        return $visitorConsent;
    }

    public function findAcceptances($userAccountId, $visitorCode, $requestCategory, $subscriber = null)
    {
        $visitorConsent = $this->findByUserOrVisitorCode($userAccountId, $visitorCode);
        $foundAcceptances = [];
        if (!$visitorConsent) {
            return false;
        }
        // dump($visitorConsent);
        // dump($visitorConsent->getVisitorConsentAcceptance());
        foreach ($visitorConsent->getVisitorConsentAcceptance() as $visitorConsentAcceptance) {
            if ($visitorConsentAcceptance->getRequestCategory() == $requestCategory) {
                if (!$subscriber || ($subscriber && ($subscriber == $visitorConsentAcceptance->getRequestSubscriber()))) {
                    $foundAcceptances[] = $visitorConsentAcceptance;
                }
            }
        }

        return $foundAcceptances;
    }

    // public function getAcceptances($userAccountId, $visitorCode, $requestCategory)
    // {
    //     $visitorConsent = $this->findByUserOrVisitorCode($userAccountId, $visitorCode);
    //     foreach ($visitorConsent->getVisitorConsentAcceptance() as $visitorConsentAcceptance) {
    //         if ($visitorConsentAcceptance->getRequestCategory() == $requestCategory) {
    //             return $visitorConsentAcceptance->getAcceptance();
    //         }
    //     }

    //     return [];
    // }

    public function removeAcceptance($userAccountId, $visitorCode, $requestCategory, $subscriber)
    {
        App::getContainer()->wireService('LegalPackage/entity/VisitorConsentAcceptance');
        App::getContainer()->wireService('LegalPackage/repository/VisitorConsentAcceptanceRepository');
        $acceptanceRepo = new VisitorConsentAcceptanceRepository();

        $foundAcceptances = $this->findAcceptances($userAccountId, $visitorCode, $requestCategory, $subscriber);
        // dump($foundAcceptances);
        if ($foundAcceptances) {
            foreach ($foundAcceptances as $foundAcceptance) {
                if ($foundAcceptance->getRequestSubscriber() == $subscriber) {
                    $acceptanceRepo->remove($foundAcceptance->getId());
                    return true;
                }
            }
        }

        return false;
    }

    public function storeAcceptance($userAccountId, $visitorCode, $requestCategory, $subscriber, $acceptance)
    {
        App::getContainer()->wireService('LegalPackage/entity/VisitorConsentAcceptance');
        App::getContainer()->wireService('LegalPackage/repository/VisitorConsentAcceptanceRepository');
        $acceptanceRepo = new VisitorConsentAcceptanceRepository();

        $foundAcceptances = $this->findAcceptances($userAccountId, $visitorCode, $requestCategory, $subscriber);
        if ($foundAcceptances) {
            foreach ($foundAcceptances as $foundAcceptance) {
                $acceptanceRepo->remove($foundAcceptance->getId());
            }
        }

        $visitorConsent = $this->findByUserOrVisitorCode($userAccountId, $visitorCode);
        $visitorConsentAcceptance = new VisitorConsentAcceptance();
        $visitorConsentAcceptance->setVisitorConsent($visitorConsent);
        $visitorConsentAcceptance->setRequestCategory($requestCategory);
        $visitorConsentAcceptance->setRequestSubscriber($subscriber);
        $visitorConsentAcceptance->setAcceptance($acceptance);

        // dump($acceptance);exit;

        $visitorConsentAcceptance = $acceptanceRepo->store($visitorConsentAcceptance);

        return $acceptance;
    }
}
