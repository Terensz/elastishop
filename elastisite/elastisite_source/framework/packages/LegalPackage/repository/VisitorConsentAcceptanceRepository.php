<?php
namespace framework\packages\LegalPackage\repository;

use App;
use framework\component\parent\DbRepository;
use framework\packages\LegalPackage\entity\VisitorConsent;

class VisitorConsentAcceptanceRepository extends DbRepository
{
    public function storeAcceptance($entity)
    {
        $stm = "UPDATE visitor_consent_acceptance SET acceptance = :acceptance WHERE id = :id ";
        $dbm = $this->getDbManager();
        $dbm->execute($stm, [
            'acceptance' => $entity->getAcceptance(),
            'id' => $entity->getId()
        ]);
    }
    
    // public function findByUser($userAccountId, $visitorCode, $requestCategory)
    // {
    //     if ($userAccountId && $userAccountId != 0) {
    //         $where = ' user_account_id = :user_account_id ';
    //         $params = array('user_account_id' => $userAccountId);
    //     }
    //     else {
    //         $where = ' visitor_code = :visitor_code ';
    //         $params = array('visitor_code' => $visitorCode);
    //     }
    //     $where = ' website = :website AND requested_for = :requested_for AND '.$where;
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


    //     if (!$visitorConsent) {
    //         $userAccount = null;
    //         $visitorConsent = new VisitorConsent();
    //         $visitorConsent->setVisitorCode(App::getContainer()->getSession()->get('visitorCode'));
    //         if ($userAccountId) {
    //             $userAccountRepository = new UserAccountRepository();
    //             $userAccount = $userAccountRepository->find($userAccountId);
    //         }
    //         $visitorConsent->setUserAccount($userAccount ? : null);
    //         $visitorConsent->setRequestCategory($requestCategory);
    //         $visitorConsent = $this->store($visitorConsent);
    //     }

    //     // dump($visitorConsent);exit;

    //     return $visitorConsent;
    // }
}
