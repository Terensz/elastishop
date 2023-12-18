<?php
namespace projects\ASC\service;

use App;
use framework\component\parent\Service;
use projects\ASC\entity\AscScale;
use projects\ASC\repository\AscScaleRepository;

// use projects\ASC\entity\AscTranslationGroup;
// use projects\ASC\repository\AscTranslationRepository;

class ScaleListService extends Service
{
    const LIST_TYPE_OWN_LIST = 'Own';
    const LIST_TYPE_OWN_INACTIVE_LIST = 'OwnInactive';
    const LIST_TYPE_TEAM_LIST = 'Team';
    const LIST_TYPE_OTHERS_LIST = 'Others';

    public static function collectAscScales($listType) : array
    {
        App::getContainer()->wireService('projects/ASC/entity/AscScale');
        App::getContainer()->wireService('projects/ASC/repository/AscScaleRepository');
        
        $repo = new AscScaleRepository();
        $list = [];

        if ($listType == self::LIST_TYPE_OWN_LIST) {
            if (!App::getContainer()->getUser()->getUserAccount()) {
                return [];
            }

            // $list = $repo->findBy(['conditions' => [
            //     ['key' => 'user_account_id', 'value' => App::getContainer()->getUser()->getUserAccount()->getId()],
            //     ['key' => 'status', 'operator' => '<>', 'value' => AscScale::STATUS_INACTIVE],
            // ]]);
            $list = $repo->findBy(['conditions' => [
                ['key' => 'user_account_id', 'value' => App::getContainer()->getUser()->getUserAccount()->getId()],
                ['key' => 'status', 'value' => AscScale::STATUS_UNDER_CONSTRUCTION],
            ]]
            );
        }

        if ($listType == self::LIST_TYPE_OWN_INACTIVE_LIST) {
            if (!App::getContainer()->getUser()->getUserAccount()) {
                return [];
            }

            $list = $repo->findBy(['conditions' => [
                ['key' => 'user_account_id', 'value' => App::getContainer()->getUser()->getUserAccount()->getId()],
                ['key' => 'status', 'value' => AscScale::STATUS_INACTIVE],
            ]]
            );

            // dump($list);exit;
        }

        if ($listType == self::LIST_TYPE_TEAM_LIST) {
            if (!App::getContainer()->getUser()->getUserAccount()) {
                return [];
            }

            // TEMP 
            // App::getContainer()->wireService('projects/ASC/service/AscPermissionService');
            // $alma = AscPermissionService::getCurrentScaleAccessibility();
            // dump($alma);exit;



            App::getContainer()->wireService('projects/ASC/service/ProjectUserService');
            $projectUser = ProjectUserService::getProjectUser();
            $list = $repo->getTeamScales($projectUser->getId());
        }


        /**
         * Azok az admin skalak, amiket masok csinaltak, de van jogosultsagunk megtekinteni.
         * @todo ki kell dolgozni ezt
        */
        if ($listType == self::LIST_TYPE_OTHERS_LIST) {
            // $list = $repo->findBy(['conditions' => [
            //     ['key' => 'user_account_id', 'operator' => '<>', 'value' => App::getContainer()->getUser()->getUserAccount()->getId()],
            //     ['key' => 'is_sample', 'operator' => '=', 'value' => AscScale::IS_SAMPLE_TRUE]
            // ]]);
            $list = [];

            // $list = $repo->findBy(['conditions' => [
            //     ['key' => 'created_by', 'value' => App::getContainer()->getUser()->getUserAccount()->getId()]]]
            // ); 
            // dump($list);exit;
        }

        return $list;
    }
}