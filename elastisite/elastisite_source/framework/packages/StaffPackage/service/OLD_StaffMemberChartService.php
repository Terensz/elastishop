<?php
namespace framework\packages\StaffPackage\service;

use App;
use framework\component\helper\DateUtils;
use framework\component\parent\Service;
use framework\packages\StaffPackage\entity\StaffMember;
use framework\packages\StaffPackage\entity\StaffMemberStat;
use framework\packages\StaffPackage\repository\StaffMemberRepository;
use framework\packages\StaffPackage\repository\StaffMemberStatRepository;
use framework\packages\StatisticsPackage\service\CustomWeekManager;

/**
 * Egyelore azert nem ez lesz az irany, mert Joe szerint nem szabad 1 grafikonra tenni a dolgozok linechart-jait.
*/
class OLD_StaffMemberChartService extends Service
{
    public static $staffMemberRepository;
    public static $staffMemberStatRepository;

    public function __construct()
    {
    }

    public static function getStaffMemberRepository()
    {
        if (!self::$staffMemberRepository) {
            App::getContainer()->wireService('StaffPackage/repository/StaffMemberRepository');
            self::$staffMemberRepository = new StaffMemberRepository();
        }

        return self::$staffMemberRepository;
    }

    public static function getStaffMemberStatRepository()
    {
        if (!self::$staffMemberStatRepository) {
            App::getContainer()->wireService('StaffPackage/repository/StaffMemberStatRepository');
            self::$staffMemberStatRepository = new StaffMemberStatRepository();
        }

        return self::$staffMemberStatRepository;
    }

    public static function assembleChartData($staffMemberId)
    {
        App::getContainer()->wireService('StaffPackage/repository/StaffMemberRepository');
        App::getContainer()->wireService('StaffPackage/service/StaffMemberStatService');
        $repo = new StaffMemberRepository();
        $staffMembersRaw = $repo->findAll();

        $staffMembers = [];
        foreach ($staffMembersRaw as $staffMemberRaw) {
            $staffMembers[$staffMemberRaw->getId()] = $staffMemberRaw;
        }

        $staffMembersStats = [];
        foreach ($staffMembers as $staffMember) {
            $staffMemberStatService = new StaffMemberStatService($staffMember);
            $staffMemberStats = $staffMemberStatService->getStaffMemberStats([], true);
            $staffMembersStats[$staffMember->getId()] = $staffMemberStats;
        }
    }

    public static function getStaffMembers(StaffMemberRepository $staffMemberRepository, int $staffMemberId = null)
    {
        App::getContainer()->wireService('StaffPackage/repository/StaffMemberRepository');
        $repo = new StaffMemberRepository();

        if (!$staffMemberId) {
            $staffMembersRaw = $repo->findAll();
        } else {
            $staffMember = $repo->find($staffMemberId);
        }
    }
}
