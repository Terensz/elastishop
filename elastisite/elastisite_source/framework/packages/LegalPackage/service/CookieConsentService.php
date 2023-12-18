<?php
namespace framework\packages\LegalPackage\controller;

use App;
use framework\component\parent\Service;
use framework\packages\LegalPackage\entity\VisitorConsentAcceptance;
use framework\packages\LegalPackage\repository\VisitorConsentRepository;

class CookieConsentService extends Service
{
    const PAGES_NOT_NEQUIRING_COOKIE_INFO = [
        'setup',
        'documents_cookie-info',
        'documents_privacy-statement',
        'documents_about-removing-personal-data',
        'errorpage_403',
        'errorpage_404',
        'admin_login'
    ];
    
    public static function findThirdPartyCookiesAcceptances($refusedOnly = false, $subscriber = null)
    {
        App::getContainer()->wireService('LegalPackage/entity/VisitorConsentAcceptance');
        App::getContainer()->wireService('LegalPackage/repository/VisitorConsentRepository');
        $repo = new VisitorConsentRepository();

        $acceptances = $repo->findAcceptances(
            App::getContainer()->getUser()->getId(),
            App::getContainer()->getSession()->get('visitorCode'),
            VisitorConsentAcceptance::REQUESTED_FOR_ACCEPTING_3RD_PARTY_COOKIES,
            $subscriber
        );

        if ($refusedOnly) {
            $result = [];
            foreach ($acceptances as $acceptance) {
                if ($acceptance->getAcceptance() == VisitorConsentAcceptance::ACCEPTANCE_REFUSED) {
                    $result[] = $acceptance;
                }
            }
            $acceptances = $result;
        }

        if ($subscriber) {
            if (isset($acceptances[0])) {
                return $acceptances[0];
            }
        } else {
            return $acceptances;
        }
    }
}
