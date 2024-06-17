<?php
namespace framework\packages\WebshopPackage\responseAssembler;

use App;
use framework\component\core\WidgetResponse;
use framework\component\parent\Service;
use framework\kernel\utility\BasicUtils;
use framework\kernel\view\ViewRenderer;
use framework\packages\WebshopPackage\service\WebshopCartService;
use framework\packages\WebshopPackage\service\WebshopRequestService;
use framework\packages\WebshopPackage\service\WebshopService;

class WebshopResponseAssembler extends Service
{
    private static $processedRequestData;

    // const REQUESTED_AND_PRIMARY_SECTIONS_ARE_THE_SAME = 'REQUESTED_AND_PRIMARY_SECTIONS_ARE_THE_SAME';

    const SECTION_TYPE_PRIMARY = 'Primary';
    const SECTION_TYPE_SECONDARY = 'Secondary';

    const LOCATION_SMALL_MODAL = 'smallModal';
    const LOCATION_EDITOR_MODAL = 'editorModal';
    const LOCATION_SIDEBAR = 'Sidebar';
    const LOCATION_MAIN_CONTENT = 'MainContent';

    // Primary
    const SECTION_SHIPMENTS_IN_PROGRESS = 'ShipmentsInProgress';
    const SECTION_PRODUCT_LIST = 'ProductList';
    const SECTION_HISTORY_PRODUCT_LIST = 'HistoryProductList';
    const SECTION_PRODUCT_DETAILS = 'ProductDetails';
    const SECTION_CHECKOUT = 'Checkout';
    const SECTION_SHIPMENT_HANDLING = 'ShipmentHandling';
    const SECTION_GWO_REPLY_BARION = 'GWOReplyBarion';
    // const SECTION_CLOSE_CART = 'CloseCart';
    // const SECTION_FINALIZE_ORDER = 'FinalizeOrder';

    // Standalone primary
    const SECTION_SET_CART_ITEM_QUANTITY_MODAL = 'SetCartItemQuantityModal';
    const SECTION_PRODUCT_DETAILS_MODAL = 'ProductDetailsModal';
    const SECTION_EDIT_ORGANIZATION_MODAL = 'EditOrganizationModal';
    const SECTION_EDIT_ADDRESS_MODAL = 'EditAddressModal';
    const SECTION_PAYMENT_MODAL = 'PaymentModal';

    // Secondary
    const SECTION_CATEGORIES = 'Categories';
    const SECTION_SIDE_CART = 'SideCart';
    const SECTION_SIDE_CART_SUMMARY = 'SideCartSummary';
    const SECTION_FILTER_BAR = 'FilterBar';
    const SECTION_SHIPMENT_HELPER = 'ShipmentHelper';

    const SECOND_PAGE_ROUTE_PART_TO_PRIMARY_SECTION = [
        'productList' => self::SECTION_PRODUCT_LIST 
    ];

    const SECTIONS_CONFIG = [
        self::SECTION_SHIPMENTS_IN_PROGRESS => [
            'type' => self::SECTION_TYPE_PRIMARY,
            'location' => self::LOCATION_MAIN_CONTENT
        ],
        self::SECTION_PRODUCT_LIST => [
            'type' => self::SECTION_TYPE_PRIMARY,
            'location' => self::LOCATION_MAIN_CONTENT
        ],
        self::SECTION_HISTORY_PRODUCT_LIST => [
            'type' => self::SECTION_TYPE_PRIMARY,
            'location' => self::LOCATION_MAIN_CONTENT
        ],
        self::SECTION_PRODUCT_DETAILS => [
            'type' => self::SECTION_TYPE_PRIMARY,
            'location' => self::LOCATION_MAIN_CONTENT
        ],
        self::SECTION_CHECKOUT => [
            'type' => self::SECTION_TYPE_PRIMARY,
            'location' => self::LOCATION_MAIN_CONTENT
        ],
        self::SECTION_SHIPMENT_HANDLING => [
            'type' => self::SECTION_TYPE_PRIMARY,
            'location' => self::LOCATION_MAIN_CONTENT
        ],
        self::SECTION_GWO_REPLY_BARION => [
            'type' => self::SECTION_TYPE_PRIMARY,
            'location' => self::LOCATION_MAIN_CONTENT
        ],
        // self::SECTION_FINALIZE_ORDER => [
        //     'type' => self::SECTION_TYPE_PRIMARY,
        //     'location' => self::LOCATION_MAIN_CONTENT
        // ],
        self::SECTION_SET_CART_ITEM_QUANTITY_MODAL => [
            'type' => self::SECTION_TYPE_PRIMARY,
            'location' => self::LOCATION_SMALL_MODAL
        ],
        self::SECTION_PRODUCT_DETAILS_MODAL => [
            'type' => self::SECTION_TYPE_PRIMARY,
            'location' => self::LOCATION_EDITOR_MODAL
        ],
        self::SECTION_EDIT_ORGANIZATION_MODAL => [
            'type' => self::SECTION_TYPE_PRIMARY,
            'location' => self::LOCATION_EDITOR_MODAL
        ],
        self::SECTION_EDIT_ADDRESS_MODAL => [
            'type' => self::SECTION_TYPE_PRIMARY,
            'location' => self::LOCATION_EDITOR_MODAL
        ],
        self::SECTION_PAYMENT_MODAL => [
            'type' => self::SECTION_TYPE_PRIMARY,
            'location' => self::LOCATION_EDITOR_MODAL
        ],
        self::SECTION_CATEGORIES => [
            'type' => self::SECTION_TYPE_SECONDARY,
            'location' => self::LOCATION_SIDEBAR
        ],
        self::SECTION_SIDE_CART => [
            'type' => self::SECTION_TYPE_SECONDARY,
            'location' => self::LOCATION_SIDEBAR
        ],
        self::SECTION_SHIPMENT_HELPER => [
            'type' => self::SECTION_TYPE_SECONDARY,
            'location' => self::LOCATION_SIDEBAR
        ],
        self::SECTION_SIDE_CART_SUMMARY => [
            'type' => self::SECTION_TYPE_SECONDARY,
            'location' => self::LOCATION_SIDEBAR
        ],
        self::SECTION_FILTER_BAR => [
            'type' => self::SECTION_TYPE_SECONDARY,
            'location' => self::LOCATION_MAIN_CONTENT
        ]
    ];

    /**
     * @notice: Also put the primary section to the section list!
     * The 'location' will be determined by each section's config (e.g. self::SECTION_PRODUCT_LIST's value)
    */
    const SECTION_LAYOUTS = [
        self::SECTION_PRODUCT_LIST => [
            // self::SECTION_SHIPMENTS_IN_PROGRESS,
            self::SECTION_HISTORY_PRODUCT_LIST,
            self::SECTION_FILTER_BAR,
            self::SECTION_PRODUCT_LIST,
            self::SECTION_SIDE_CART,
            self::SECTION_CATEGORIES
        ],
        self::SECTION_PRODUCT_DETAILS => [
            self::SECTION_PRODUCT_DETAILS,
            self::SECTION_SIDE_CART,
            self::SECTION_CATEGORIES
        ],
        self::SECTION_CHECKOUT => [
            self::SECTION_SHIPMENTS_IN_PROGRESS,
            self::SECTION_CHECKOUT,
            self::SECTION_SIDE_CART,
            // self::SECTION_SIDE_CART_SUMMARY,
            self::SECTION_CATEGORIES,
        ],
        self::SECTION_SHIPMENT_HANDLING => [
            self::SECTION_SHIPMENT_HANDLING,
            self::SECTION_SHIPMENT_HELPER,
            // self::SECTION_SIDE_CART_SUMMARY,
            // self::SECTION_CATEGORIES,
        ],
        self::SECTION_GWO_REPLY_BARION => [
            self::SECTION_GWO_REPLY_BARION,
            self::SECTION_SIDE_CART,
            self::SECTION_CATEGORIES
        ],
        // self::SECTION_CLOSE_CART => [
        //     self::SECTION_CHECKOUT,
        //     self::SECTION_SIDE_CART,
        //     // self::SECTION_SIDE_CART_SUMMARY,
        //     self::SECTION_CATEGORIES,
        // ],
        // self::SECTION_FINALIZE_ORDER => [
        //     self::SECTION_FINALIZE_ORDER,
        //     self::SECTION_SIDE_CART_SUMMARY
        // ],
        self::SECTION_PRODUCT_DETAILS_MODAL => [],
        self::SECTION_EDIT_ORGANIZATION_MODAL => [],
        self::SECTION_EDIT_ADDRESS_MODAL => [],
        self::SECTION_PAYMENT_MODAL => []
    ];

    public static function renderSections(array $requestedSections, $data = []) 
    {
        // if ($data == []) {
        //     $renderedSections = [];
        // } else {
        //     $renderedSections = self::assembleSectionsResponseData(null, $requestedSections, $data);
        //     // dump($renderedSections);exit;
        // }

        $renderedSections = self::assembleSectionsResponseData(null, $requestedSections, $data);

        // dump($renderedSections);exit;

        /*
        // The response looks someting like this:
        [renderedSections] => [
            [primarySection] => null,
            [sectionsResponse] => [
                [SetCartItemQuantityModal] => [
                    [view] => '.....',
                    [data] => [
                        [offerId] => 29000
                    ]
                ]
            ]
        ]
        */
        $response = [
            'renderedSections' => $renderedSections,
        ];

        return WidgetResponse::create($response);
    }

    public static function assembleSectionsResponseData(string $primarySection = null, array $requestedSections = [], $data = [])
    {
        App::getContainer()->wireService('WebshopPackage/service/WebshopCartService');
        // App::getContainer()->wireService('WebshopPackage/service/WebshopRequestService');
        $processedRequestData = self::getCachedProcessedRequestData();

        // Primary
        // $primarySectionResponseAssemblerClass = 'WebshopResponseAssembler_' . $primarySection;
        // dump('WebshopPackage/responseAssembler/'.$primarySectionResponseAssemblerClass);

        // App::getContainer()->wireService('WebshopPackage/responseAssembler/'.$primarySectionResponseAssemblerClass);
        // $primarySectionResponse = forward_static_call(['framework\packages\WebshopPackage\responseAssembler\\'.$primarySectionResponseAssemblerClass, 'assembleResponse'], $processedRequestData);

        // Secondaries
        $sectionsResponse = [];
        // $sectionsResponses = [];
        // $processedRequestData;
        $layout = $primarySection ? self::SECTION_LAYOUTS[$primarySection] : $requestedSections;


        // dump($layout);exit;
        foreach ($layout as $sectionName) {
            $sectionResponseAssemblerClass = 'WebshopResponseAssembler_' . $sectionName;
            App::getContainer()->wireService('WebshopPackage/responseAssembler/'.$sectionResponseAssemblerClass);
            $sectionConfig = self::SECTIONS_CONFIG[$sectionName];
            // $assembledResponse = forward_static_call(['framework\packages\WebshopPackage\responseAssembler\\'.$sectionResponseAssemblerClass, 'assembleResponse'], [
            //     'alma' => 'körtekörte'
            // ]);
            $assembledResponse = forward_static_call(['framework\packages\WebshopPackage\responseAssembler\\'.$sectionResponseAssemblerClass, 'assembleResponse'], $processedRequestData, $data);
// dump($sectionConfig['location']);
                // dump($assembledResponse);exit;
            
            /**
             * If $requestedSections is a not empty array, than an ajax request was called for only some parts. E.g.: addToCart renders only the cart.
            */
            if (!empty($requestedSections)) {
                $sectionsResponse[$sectionName] = $assembledResponse;
            /**
             * The other if-branch is the fully rendered parts, suited for the Main.php.
            */
            } else {
                $sectionsResponse[$sectionConfig['location']][] = [
                    'sectionName' => $sectionName,
                    'assembledResponse' => $assembledResponse
                ];
            }
        }



// exit;



        // dump($sections);exit;


        // dump($primarySectionResponse); 
        /**
         * Do not remove them to primarySectionView and secondarySectionsViews. This will handle API calls later, instead of direct rendering.
        */
        return [
            'primarySection' => $primarySection,
            // 'primarySectionResponse' => $primarySectionResponse,
            'sectionsResponse' => $sectionsResponse
        ];
    }

    public static function getCachedProcessedRequestData()
    {
        if (self::$processedRequestData) {
            return self::$processedRequestData;
        }

        App::getContainer()->wireService('WebshopPackage/service/WebshopRequestService');
        $processedRequestData = WebshopRequestService::getProcessedRequestData();
        self::$processedRequestData = $processedRequestData;

        return self::$processedRequestData;
    }

    public static function guessPrimarySection()
    {
        $processedRequestData = self::getCachedProcessedRequestData();
        $pageRoute = App::getContainer()->getRouting()->getPageRoute()->getName();
        // $actualRoute = App::getContainer()->getRouting()->getActualRoute();
        $pageRouteParts = explode('_', $pageRoute);
        if (isset(self::SECOND_PAGE_ROUTE_PART_TO_PRIMARY_SECTION[$pageRouteParts[1]])) {
            return self::SECOND_PAGE_ROUTE_PART_TO_PRIMARY_SECTION[$pageRouteParts[1]];
        }

        throw new \Exception('No primary section found for this route: '.$pageRoute->getName());
        // dump($pageRouteParts);
        // dump($processedRequestData);exit;
    }

    public static function render($primarySection = null)
    {
        // $primarySection = self::guessPrimarySection();
        if (!$primarySection) {
            $primarySection = self::guessPrimarySection();
        }
        App::getContainer()->wireService('WebshopPackage/service/WebshopService');
        App::getContainer()->wireService('WebshopPackage/service/WebshopRequestService');
        // dump('Render1');//exit;
        $sectionsResponseData = self::assembleSectionsResponseData($primarySection);

        // $processedRequestData = self::getCachedProcessedRequestData();
        $locale = App::getContainer()->getSession()->getLocale();

        /**
         * Putting together the search links.
         * One for search all, one for search in actual category.
        */
        $searchLinkBase = '/'.WebshopRequestService::getSlugTransRef(WebshopService::TAG_WEBSHOP, $locale);
        $searchSlug = WebshopRequestService::getSlugTransRef(WebshopService::TAG_SEARCH, $locale);
        $searchLinkBaseAll = $searchLinkBase.'/'.$searchSlug.'/';

        $listAllLink = WebshopRequestService::getListAllLink();
        $viewPath = 'framework/packages/WebshopPackage/view/Sections/Main/Main.php';
        $mainView = ViewRenderer::renderWidget('Webshop_Main', $viewPath, [
            'listAllLink' => WebshopRequestService::getListAllLink(),
            'listAllCategorySlug' => BasicUtils::explodeAndGetElement($listAllLink, '/', 'last'),
            'searchLinkData' => [
                'searchLinkBase' => $searchLinkBase,
                'searchLinkBaseAll' => $searchLinkBaseAll,
                // 'searchLinkBaseCategory' => $searchLinkBaseCategory
            ],
            'grantedViewProjectAdminContent' => App::getContainer()->isGranted('viewProjectAdminContent'),
            'advanceForm' => false,
            'validateForm' => false,
            'cart' => null,
            // 'primarySection' => $sectionsResponse['primarySection'],
            // 'sections' => $sectionsResponse['sections'],
        ]);


        // dump('Render2');//exit;

        // dump($sectionsResponse['sections']);exit;
        $locations = [];
        $data = [];
        foreach ($sectionsResponseData['sectionsResponse'] as $location => $sections) {
            if (!in_array($location, $locations)) {
                $locations[] = $location;
            }
            /**
             * Notice, that we are putting the location tag (e.g. '[mainContent]') to the end of the content of that current location,
             * so the next content will always follow up the previous ones.
            */
            // dump($sections);
            foreach ($sections as $section) {
                $view = '<div id="viewSection-'.$section['sectionName'].'">'.$section['assembledResponse']['view'].'</div>';
                $mainView = str_replace('['.$location.']', $view . '['.$location.']', $mainView);
                if (!empty($section['assembledResponse']['data'])) {
                    $data = array_merge($data, $section['assembledResponse']['data']);
                }
            }
        }
        for ($i = 0; $i < count($locations); $i++) {
            // dump('['.$locations[$i].']');
            // $pozi = strpos('['.$locations[$i].']', $mainView);
            // dump($pozi);
            $mainView = str_replace('['.$locations[$i].']', '', $mainView);
        }

        // dump('Render2');exit;
        // dump($mainView);
        // str_replace('[Sidebar]', '', $mainView);
        // str_replace('[MainContent]', '', $mainView); exit;

        $response = [
            'view' => $mainView,
            'data' => $data
        ];

        // dump($response);exit;
        // dump('Render3');exit;

        return WidgetResponse::create($response);
    }

    public static function returnAlternativeView($widgetName, $viewPath, $additionalViewParams = [])
    {
        $viewParams = [
            'webshopBaseLink' => '/'.WebshopRequestService::getSlugTransRef(WebshopService::TAG_WEBSHOP, App::getContainer()->getSession()->getLocale())
        ];

        if (!empty($additionalViewParams)) {
            $viewParams = array_merge($viewParams, $additionalViewParams);
        }
        $view = ViewRenderer::renderWidget($widgetName, $viewPath, $viewParams);

        return [
            'view' => $view,
            'data' => [
            ]
        ];
    }
}