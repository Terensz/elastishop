<?php
namespace framework\packages\StaffPackage\controller;

use App;
use framework\component\helper\DateUtils;
use framework\component\parent\WidgetController;
use framework\packages\StaffPackage\entity\StaffMember;
use framework\packages\StaffPackage\repository\StaffMemberRepository;
use framework\packages\StaffPackage\service\StaffMemberStatService;
use framework\packages\UserPackage\entity\User;

/*
Ezek a lépések vannak hátra:

1.: view/widget/ManageStaffMemberStatsWidget/error.php
    - Létre kell hozni
    - Jelenítse meg a hibaüzenetet

2.: public function manageStaffMemberStatsResponse(StaffMember $staffMember):
    - Vizsgálja meg, hogy be van-e jelentkezve az user
        - Nem: töltsön be egy login widgetet
            - Ugyanúgy működjön, mint az asc/loginOrRegister. 
                - Már regisztráltam és tudom a jelszavamat -> login modál
                    - Csak Staff membert jeletkeztethet be, se usert, se admint.
                - Nincs hozzáférésem, véletlenül kerültem ide
                    - rákattintva dobja el máshová
        - Igen:
*/

class StaffWidgetController extends WidgetController
{
    public $staffMember;

    public function __construct()
    {
        App::getContainer()->wireService('StaffPackage/service/StaffMemberStatService');
        App::getContainer()->wireService('StatisticsPackage/service/CustomWeekManager');
        // App::getContainer()->wireService('SiteBuilderPackage/service/ContentEditorDisplayTool');
    }

    public function createViews($notRequiredViews = [])
    {
        return [
            'views' => [
                'ChartView' => isset($notRequiredViews['ChartView']) ? null : $this->createChartView(),
                'StatListView' => isset($notRequiredViews['StatListView']) ? null : $this->createStatListView()
            ]
            // 'data' => [
            //     'closeModal' => false
            // ]
        ];
    }

    public function createChartView()
    {
        $staffMember = $this->getStaffMember();
        $staffMemberService = new StaffMemberStatService($staffMember);
        $staffMemberStats = $staffMemberService->getStaffMemberStats([], true);
        $viewPath = 'framework/packages/StaffPackage/view/StaffMemberStats/ChartView.php';
        $renderedWidget = $this->renderWidget('', $viewPath, [
            'staffMemberStats' => $staffMemberStats
        ]);

        return $renderedWidget;
    }

    public function createStatListView()
    {
        $staffMember = $this->getStaffMember();
        $staffMemberService = new StaffMemberStatService($staffMember);
        $staffMemberStats = $staffMemberService->getStaffMemberStats([], true);
        // $currentYear = DateUtils::getCurrentYear();
        // dump($staffMemberStats);exit;

        $viewPath = 'framework/packages/StaffPackage/view/StaffMemberStats/StatListView.php';
        $renderedWidget = $this->renderWidget('', $viewPath, [
            'staffMemberStats' => $staffMemberStats
        ]);

        return $renderedWidget;
    }

    public function getStaffMember()
    {
        if ($this->staffMember) {
            return $this->staffMember;
        }

        $user = App::getContainer()->getUser();
        // dump($user);exit;
        $urlParamChain = App::getContainer()->getUrl()->getParamChain();
        // dump($urlParamChain);exit;

        if ($user->getId() > 0 && $user->getType() == User::TYPE_USER) {
            App::getContainer()->getSession()->logout();
            header('Location: /'.$urlParamChain);
            die;
        }

        App::getContainer()->wireService('StaffPackage/entity/StaffMember');

        $urlDetails = App::getContainer()->getUrl()->getDetails();
        $staffMember = null;
        if (isset($urlDetails[2])) {
            App::getContainer()->wireService('StaffPackage/repository/StaffMemberRepository');
            $repo = new StaffMemberRepository();
            $code = $urlDetails[2];
            if ($urlDetails[1] == 'staffMember') {
                $staffMember = $repo->findOneBy(['conditions' => [
					['key' => 'code', 'value' => $code]
				]]);
                if ($staffMember) {
                    $this->staffMember = $staffMember;
                }
            } else {
                /**
                 * @todo 
                */
            }
        }

        return $staffMember;
    }

    /**
    * Route: [name: widget_ManageStaffMemberStatsWidget, paramChain: /widget/ManageStaffMemberStatsWidget]
    */
    public function manageStaffMemberStatsWidgetAction()
    {
        $staffMember = $this->getStaffMember();

        if ($staffMember) {
            return $this->manageStaffMemberStatsResponse($staffMember);
        }
        
        if (!$staffMember) {
            return $this->manageStaffMemberStatsErrorResponse(trans('staff.member.not.found'));
        }
        
        // dump($urlDetails);exit;
        // dump('ManageStaffMemberStatsWidget');exit;
        // dump($response);exit;
    }

    public function manageStaffMemberStatsErrorResponse($errorMessage)
    {
        $viewPath = 'framework/packages/StaffPackage/view/widget/ManageStaffMemberStatsWidget/error.php';
        $response = [
            'view' => $this->renderWidget('', $viewPath, [
                'errorMessage' => $errorMessage
            ]),
            'data' => [
                'errorMessage' => $errorMessage
            ]
        ];

        return $this->widgetResponse($response);
    }

    public function manageStaffMemberStatsResponse(StaffMember $staffMember)
    {
        // $staffMemberService = new StaffMemberStatService($staffMember);
        // $staffMemberStats = $staffMemberService->getStaffMemberStats();
        // $currentYear = DateUtils::getCurrentYear();
        
        $views = $this->createViews();
        $viewPath = 'framework/packages/StaffPackage/view/widget/ManageStaffMemberStatsWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('ManageStaffMemberStatsWidget', $viewPath, [
                // 'container' => $this->getContainer(),
                'views' => $views['views']
            ])
        ];
        // $response = array_merge($response, $views);
        // dump($response);exit;

        return $this->widgetResponse($response);

        // $viewPath = 'framework/packages/StaffPackage/view/widget/ManageStaffMemberStatsWidget/widget.php';
        // $response = [
        //     'view' => $this->renderWidget('', $viewPath, [
        //         // 'staffMemberWeeks' => $staffMemberWeeks,
        //         'currentYear' => $currentYear
        //     ]),
        //     'data' => []
        // ];

        // return $this->widgetResponse($response);
    }

    /**
    * Route: [name: staffMemberStats_saveStat, paramChain: /staffMemberStats/saveStat]
    */
    public function staffMemberStatsSaveStatAction()
    {
        $staffMember = $this->getStaffMember();
        $staffMemberService = new StaffMemberStatService($staffMember);
        $year = App::getContainer()->getRequest()->get('year');
        $weekNumber = App::getContainer()->getRequest()->get('weekNumber');
        $points = App::getContainer()->getRequest()->get('points');
        $result = $staffMemberService->saveStatForWeek($year, $weekNumber, $points);

        $views = $this->createViews();
        // $viewPath = 'framework/packages/StaffPackage/view/widget/ManageStaffMemberStatsWidget/widget.php';
        $response = [
            'view' => '',
            'views' => $views['views'],
            'data' => [
                'result' => $result['errorMessage'] ? 'error' : 'success',
                'message' => $result['errorMessage'] ? : trans('stat.points.successfully.saved')
            ]
        ];
        // $response = array_merge($response, $views);
        // dump($response);exit;

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: widget_ManageStaffMemberStatsWidget, paramChain: /widget/ManageStaffMemberStatsWidget]
    */
    // public function manageStaffMemberStatsWidgetAction_2()
    // {
    //     $views = $this->createViews();
    //     $viewPath = 'framework/packages/StaffPackage/view/widget/ManageStaffMemberStatsWidget/widget.php';
    //     $response = [
    //         'view' => $this->renderWidget('ManageStaffMemberStatsWidget', $viewPath, [
    //             // 'container' => $this->getContainer(),
    //             'views' => $views['views']
    //         ])
    //     ];
    //     // $response = array_merge($response, $views);
    //     // dump($response);exit;

    //     return $this->widgetResponse($response);
    // }
}