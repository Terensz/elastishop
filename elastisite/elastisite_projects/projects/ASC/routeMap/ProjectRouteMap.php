<?php
namespace projects\ASC\routeMap;

class ProjectRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'admin_login',
                'paramChains' => array(
                    'login/asc/TMzZ9VSyD6ciP4h33flx2A' => 'default'
                ),
                'controller' => 'framework/packages/UserPackage/controller/UserController',
                'action' => 'adminLoginAction',
                'permission' => 'viewGuestContent',
                'inMenu' => 'main',
                'title' => 'login',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'widgetChanges' => array(
                    'mainContent' => 'FrameworkPackage/view/widget/AdminLoginMainContentWidget',
                    'left1' => 'UserPackage/view/widget/Login2Widget',
                    // // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
                ),
                'pageSwitchBehavior' => array(
                    'UserRegistrationWidget' => 'restore'
                )
            ),
            array(
                'name' => 'homepage',
                'paramChains' => array(
                    '' => 'default',
                    'homepage' => 'en',
                    'fooldal' => 'hu'
                ),
                'controller' => 'projects/ASC/controller/BasicController',
                'action' => 'standardAction',
                'permission' => 'viewGuestContent',
                'inMenu' => 'main',
                'title' => 'homepage.title',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                // 'structure' => 'projects/Meheszellato/view/structure/onePanel.php',
                'widgetChanges' => array(
                    // 'left1' => 'SchedulePackage/view/widget/CalendarWidget',
                    // 'mainContent' => 'projects/Meheszellato/view/widget/HomepageWidget',
                    // 'mainContent' => 'SiteBuilderPackage/view/widget/SplashWidget'
                    'mainContent' => 'projects/ASC/view/widget/ProjectSplashWidget'
                    // // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
                    // 'left1' => 'projects/Meheszellato/view/widget/HomepageSideWidget'
                )
            ),
            array(
                'name' => 'widget_ProjectSplashWidget',
                'paramChains' => array(
                    'widget/ProjectSplashWidget' => 'default'
                ),
                'controller' => 'projects/ASC/controller/ProjectWidgetController',
                'action' => 'projectSplashWidgetAction',
                'permission' => 'viewGuestContent'
            ),
            // array(
            //     'name' => 'uj',
            //     'paramChains' => array(
            //         'new' => 'en',
            //         'uj' => 'hu'
            //     ),
            //     'controller' => 'projects/Meheszellato/controller/BasicController',
            //     'action' => 'standardAction',
            //     'permission' => 'viewGuestContent',
            //     'inMenu' => 'main',
            //     'title' => 'homepage.title',
            //     'structure' => 'FrameworkPackage/view/structure/BasicStructure',
            //     'widgetChanges' => array(
            //         'mainContent' => 'SiteBuilderPackage/view/widget/CreateNewSiteWidget'
            //         // // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
            //         // 'left1' => 'projects/Meheszellato/view/widget/HomepageSideWidget'
            //     )
            // ),
            // array(
            //     'name' => 'HomepageWidget',
            //     'paramChains' => array(
            //         'HomepageWidget' => 'default'
            //     ),
            //     'controller' => 'projects/Meheszellato/controller/ProjectWidgetController',
            //     'action' => 'homepageWidgetAction',
            //     'permission' => 'viewGuestContent'
            // ),
            // array(
            //     'name' => 'HomepageSideWidget',
            //     'paramChains' => array(
            //         'HomepageSideWidget' => 'default'
            //     ),
            //     'controller' => 'projects/Meheszellato/controller/ProjectWidgetController',
            //     'action' => 'homepageSideWidgetAction',
            //     'permission' => 'viewGuestContent'
            // ),
            // array(
            //     'name' => 'elastisite_ESTitleWidget',
            //     'paramChains' => array(
            //         'elastisite/ESTitleWidget' => 'default'
            //     ),
            //     'controller' => 'projects/Meheszellato/controller/ProjectWidgetController',
            //     'action' => 'eSTitleWidgetAction',
            //     'permission' => 'viewGuestContent'
            // ),
            // array(
            //     'name' => 'elastisite_ESContentWidget',
            //     'paramChains' => array(
            //         'elastisite/ESContentWidget' => 'default'
            //     ),
            //     'controller' => 'projects/Meheszellato/controller/ProjectWidgetController',
            //     'action' => 'eSContentWidgetAction',
            //     'permission' => 'viewGuestContent'
            // ),
            array(
                'name' => 'widget_SideSubmenuWidget',
                'paramChains' => array(
                    'widget/SideSubmenuWidget' => 'default'
                ),
                'controller' => 'projects/Meheszellato/controller/ProjectWidgetController',
                'action' => 'sideSubmenuWidgetAction',
                'permission' => 'viewGuestContent'
            ),
            // array(
            //     'name' => 'news',
            //     'paramChains' => array(
            //         'hirek' => 'hu',
            //         'news' => 'en'
            //     ),
            //     'controller' => 'projects/Meheszellato/controller/BasicController',
            //     'action' => 'standardAction',
            //     'permission' => 'viewGuestContent',
            //     'inMenu' => 'main',
            //     'title' => 'news',
            //     'structure' => 'FrameworkPackage/view/structure/BasicStructure',
            //     'widgetChanges' => array(
            //         // 'left1' => 'SchedulePackage/view/widget/CalendarWidget',
            //         // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
            //     )
            // ),
            // array(
            //     'name' => 'about_me',
            //     'paramChains' => array(
            //         'story-of-a-vandering-beekeeper' => 'en',
            //         'egy-vandormehesz-tortenete' => 'hu'
            //     ),
            //     'controller' => 'projects/Meheszellato/controller/BasicController',
            //     'action' => 'standardAction',
            //     'permission' => 'viewGuestContent',
            //     'inMenu' => 'main',
            //     'title' => 'a.story.of.a.wandering.beekeeper',
            //     'structure' => 'FrameworkPackage/view/structure/BasicStructure',
            //     'widgetChanges' => array(
            //         // 'mainContent' => 'SiteBuilderPackage/view/widget/SplashWidget'
            //         'mainContent' => 'projects/Meheszellato/view/widget/BeekeeperStoryWidget'
            //     )
            // ),
            // array(
            //     'name' => 'widget_BeekeeperStoryWidget',
            //     'paramChains' => array(
            //         'widget/BeekeeperStoryWidget' => 'default'
            //     ),
            //     'controller' => 'projects/Meheszellato/controller/ProjectWidgetController',
            //     'action' => 'beekeeperStoryWidgetAction',
            //     'permission' => 'viewGuestContent'
            // ),
            // array(
            //     'name' => 'contactWidget',
            //     'paramChains' => array(
            //         'ContactWidget' => 'default'
            //     ),
            //     'controller' => 'projects/Meheszellato/controller/ProjectWidgetController',
            //     'action' => 'contactWidgetAction',
            //     'permission' => 'viewGuestContent'
            // ),

            array(
                'name' => 'contact',
                'paramChains' => array(
                    'contact' => 'en',
                    'kapcsolat' => 'hu'
                ),
                'controller' => 'projects/Meheszellato/controller/BasicController',
                'action' => 'standardAction',
                'permission' => 'viewGuestContent',
                'inMenu' => 'main',
                'title' => 'contact',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                'widgetChanges' => array(
                    'mainContent' => 'projects/Meheszellato/view/widget/ContactWidget',
                    // 'mainContent2' => 'SiteBuilderPackage/view/widget/WrappedAdvancedArticleWidget', // AAW
                    // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
                )
            ),
            array(
                'name' => 'widget_ContactWidget',
                'paramChains' => array(
                    'widget/ContactWidget' => 'default'
                ),
                'controller' => 'projects/Meheszellato/controller/ProjectWidgetController',
                'action' => 'contactWidgetAction',
                'permission' => 'viewGuestContent'
            ),
            // array(
            //     'name' => 'widget_NewsSubscriptionForm',
            //     'paramChains' => array(
            //         'widget/NewsSubscriptionForm' => 'default'
            //     ),
            //     'controller' => 'projects/Meheszellato/controller/ProjectWidgetController',
            //     'action' => 'newsSubscriptionFormAction',
            //     'permission' => 'viewGuestContent'
            // ),
            // array(
            //     'name' => 'surveyOfUserSatisfactionForm',
            //     'paramChains' => array(
            //         'surveyOfUserSatisfactionForm' => 'default'
            //     ),
            //     'controller' => 'projects/Meheszellato/controller/ProjectWidgetController',
            //     'action' => 'surveyOfUserSatisfactionFormAction',
            //     'permission' => 'viewGuestContent'
            // ),
            array(
                'name' => 'ajax_sendUsMessageForm',
                'paramChains' => array(
                    'ajax/sendUsMessageForm' => 'default'
                ),
                'controller' => 'projects/Meheszellato/controller/ProjectWidgetController',
                'action' => 'sendUsMessageFormAction',
                'permission' => 'viewGuestContent'
            ),
            // array(
            //     'name' => 'elastisite_ESIndexWidget',
            //     'paramChains' => array(
            //         'elastisite/ESIndexWidget' => 'default'
            //     ),
            //     'controller' => 'projects/Meheszellato/controller/ProjectWidgetController',
            //     'action' => 'elastiSiteIndexWidgetAction',
            //     'permission' => 'viewGuestContent'
            // ),
            // array(
            //     'name' => 'what-is-elastisite',
            //     'paramChains' => array(
            //         'what-is-elastisite' => 'en',
            //         'mi-az-az-elastisite' => 'hu'
            //     ),
            //     'controller' => 'projects/Meheszellato/controller/BasicController',
            //     'action' => 'standardAction',
            //     'permission' => 'viewGuestContent',
            //     'inMenu' => 'main',
            //     'title' => 'what.is.elastisite',
            //     'structure' => 'FrameworkPackage/view/structure/BasicStructure',
            //     'widgetChanges' => array(
            //         'mainContent' => 'projects/Meheszellato/view/widget/ESContentWidget',
            //         'left1' => 'projects/Meheszellato/view/widget/SideSubmenuWidget',
            //         // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
            //     )
            // ),
            // array(
            //     'name' => 'products',
            //     'paramChains' => array(
            //         'products' => 'en',
            //         'termekek' => 'hu'
            //     ),
            //     'controller' => 'projects/Meheszellato/controller/BasicController',
            //     'action' => 'standardAction',
            //     'permission' => 'viewGuestContent',
            //     'inMenu' => 'main',
            //     'title' => 'products.and.services',
            //     'structure' => 'FrameworkPackage/view/structure/BasicStructure',
            //     'widgetChanges' => array(
            //         'mainContent' => 'projects/Meheszellato/view/widget/ESContentWidget',
            //         'left1' => 'projects/Meheszellato/view/widget/SideSubmenuWidget',
            //         // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
            //     )
            // ),
            array(
                'name' => 'service-providers',
                'paramChains' => array(
                    'service-providers' => 'en',
                    'szolgaltatok' => 'hu'
                ),
                'controller' => 'projects/Meheszellato/controller/BasicController',
                'action' => 'standardAction',
                'permission' => 'viewGuestContent',
                'inMenu' => 'main',
                'title' => 'service.providers',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                'widgetChanges' => array(
                    'mainContent' => 'projects/Meheszellato/view/widget/ESContentWidget',
                    'left1' => 'projects/Meheszellato/view/widget/SideSubmenuWidget',
                    // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
                )
            ),
            array(
                'name' => 'order-conditions',
                'paramChains' => array(
                    'order-conditions' => 'en',
                    'megrendeles-feltetelei' => 'hu'
                ),
                'controller' => 'projects/Meheszellato/controller/BasicController',
                'action' => 'standardAction',
                'permission' => 'viewGuestContent',
                'inMenu' => 'main',
                'title' => 'order.conditions',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                'widgetChanges' => array(
                    'mainContent' => 'projects/Meheszellato/view/widget/ESContentWidget',
                    'left1' => 'projects/Meheszellato/view/widget/SideSubmenuWidget',
                    // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
                )
            ),
            // array(
            //     'name' => 'order',
            //     'paramChains' => array(
            //         'order' => 'en',
            //         'megrendeles' => 'hu'
            //     ),
            //     'controller' => 'projects/Meheszellato/controller/BasicController',
            //     'action' => 'standardAction',
            //     'permission' => 'viewGuestContent',
            //     'inMenu' => 'main',
            //     'title' => 'order',
            //     'structure' => 'FrameworkPackage/view/structure/BasicStructure',
            //     'widgetChanges' => array(
            //         'mainContent' => 'projects/Meheszellato/view/widget/ESContentWidget',
            //         'left1' => 'projects/Meheszellato/view/widget/SideSubmenuWidget',
            //         // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
            //     )
            // ),
            // array(
            //     'name' => 'customers-area',
            //     'paramChains' => array(
            //         'customers-area' => 'en',
            //         'ugyfeleknek' => 'hu'
            //     ),
            //     'controller' => 'projects/Meheszellato/controller/BasicController',
            //     'action' => 'standardAction',
            //     'permission' => 'viewGuestContent',
            //     'inMenu' => 'main',
            //     'title' => 'customers.area',
            //     'structure' => 'FrameworkPackage/view/structure/BasicStructure',
            //     'widgetChanges' => array(
            //         'mainContent' => 'projects/Meheszellato/view/widget/ESContentWidget',
            //         'left1' => 'projects/Meheszellato/view/widget/SideSubmenuWidget',
            //         // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
            //     )
            // ),
            // array(
            //     'name' => 'support',
            //     'paramChains' => array(
            //         'support' => 'en',
            //         'tamogatas' => 'hu'
            //     ),
            //     'controller' => 'projects/Meheszellato/controller/BasicController',
            //     'action' => 'standardAction',
            //     'permission' => 'viewGuestContent',
            //     'inMenu' => 'main',
            //     'title' => 'support',
            //     'structure' => 'FrameworkPackage/view/structure/BasicStructure',
            //     'widgetChanges' => array(
            //         'mainContent' => 'projects/Meheszellato/view/widget/ESContentWidget',
            //         'left1' => 'projects/Meheszellato/view/widget/SideSubmenuWidget',
            //         // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
            //     )
            // ),
            // array(
            //     'name' => 'error-reporting',
            //     'paramChains' => array(
            //         'error-reporting' => 'en',
            //         'hibabejelentes' => 'hu'
            //     ),
            //     'controller' => 'projects/Meheszellato/controller/BasicController',
            //     'action' => 'standardAction',
            //     'permission' => 'viewGuestContent',
            //     'inMenu' => 'main',
            //     'title' => 'error.reporting',
            //     'structure' => 'FrameworkPackage/view/structure/BasicStructure',
            //     'widgetChanges' => array(
            //         'mainContent' => 'projects/Meheszellato/view/widget/ESContentWidget',
            //         'left1' => 'projects/Meheszellato/view/widget/SideSubmenuWidget',
            //         // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
            //     )
            // ),
            // array(
            //     'name' => 'documents',
            //     'paramChains' => array(
            //         'documents' => 'en',
            //         'dokumentumok' => 'hu'
            //     ),
            //     'controller' => 'projects/Meheszellato/controller/BasicController',
            //     'action' => 'standardAction',
            //     'permission' => 'viewGuestContent',
            //     'inMenu' => 'main',
            //     'title' => 'documents',
            //     'structure' => 'FrameworkPackage/view/structure/BasicStructure',
            //     'widgetChanges' => array(
            //         'mainContent' => 'projects/Meheszellato/view/widget/ESContentWidget',
            //         'left1' => 'projects/Meheszellato/view/widget/SideSubmenuWidget',
            //         // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
            //     )
            // ),
            array(
                'name' => 'documents_general-terms-and-conditions',
                'paramChains' => array(
                    'documents/general-terms-and-conditions' => 'en',
                    'dokumentumok/altalanos-szerzodesi-feltetelek' => 'hu'
                ),
                'controller' => 'projects/Meheszellato/controller/BasicController',
                'action' => 'standardAction',
                'permission' => 'viewGuestContent',
                'inMenu' => 'main',
                'title' => 'general.terms.and.conditions',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                'widgetChanges' => array(
                    'mainContent' => 'projects/Meheszellato/view/widget/ESContentWidget',
                    'left1' => 'projects/Meheszellato/view/widget/SideSubmenuWidget',
                    // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
                )
            ),
            array(
                'name' => 'documents_contract-ElastiSite-basic-software',
                'paramChains' => array(
                    'documents/contract-ElastiSite-basic-software' => 'en',
                    'dokumentumok/szerzodes-ElastiSite-alapszoftver' => 'hu'
                ),
                'controller' => 'projects/Meheszellato/controller/BasicController',
                'action' => 'standardAction',
                'permission' => 'viewGuestContent',
                'inMenu' => 'main',
                'title' => 'contract.elastisite.basic.software',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                'widgetChanges' => array(
                    'mainContent' => 'projects/Meheszellato/view/widget/ESContentWidget',
                    'left1' => 'projects/Meheszellato/view/widget/SideSubmenuWidget',
                    // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
                )
            ),
            array(
                'name' => 'documents_contract-ElastiSite-webshop-package',
                'paramChains' => array(
                    'documents/contract-ElastiSite-webshop-package' => 'en',
                    'dokumentumok/szerzodes-ElastiSite-webaruhaz-csomag' => 'hu'
                ),
                'controller' => 'projects/Meheszellato/controller/BasicController',
                'action' => 'standardAction',
                'permission' => 'viewGuestContent',
                'inMenu' => 'main',
                'title' => 'contract.elastisite.webshop.package',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                'widgetChanges' => array(
                    'mainContent' => 'projects/Meheszellato/view/widget/ESContentWidget',
                    'left1' => 'projects/Meheszellato/view/widget/SideSubmenuWidget',
                    // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
                )
            ),
            // array(
            //     'name' => 'documents_contract-ElastiSite-support',
            //     'paramChains' => array(
            //         'documents/contract-ElastiSite-support' => 'en',
            //         'dokumentumok/szerzodes-ElastiSite-support' => 'hu'
            //     ),
            //     'controller' => 'projects/Meheszellato/controller/BasicController',
            //     'action' => 'standardAction',
            //     'permission' => 'viewGuestContent',
            //     'inMenu' => 'main',
            //     'title' => 'contract.elastisite.support',
            //     'structure' => 'FrameworkPackage/view/structure/BasicStructure',
            //     'widgetChanges' => array(
            //         'mainContent' => 'projects/Meheszellato/view/widget/ESContentWidget',
            //         'left1' => 'projects/Meheszellato/view/widget/SideSubmenuWidget',
            //         // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
            //     )
            // ),
            // array(
            //     'name' => 'documents_contract-ElastiSite-custom-development',
            //     'paramChains' => array(
            //         'documents/contract-ElastiSite-custom-development' => 'en',
            //         'dokumentumok/szerzodes-ElastiSite-egyedi-fejlesztes' => 'hu'
            //     ),
            //     'controller' => 'projects/Meheszellato/controller/BasicController',
            //     'action' => 'standardAction',
            //     'permission' => 'viewGuestContent',
            //     'inMenu' => 'main',
            //     'title' => 'contract.elastisite.custom.development',
            //     'structure' => 'FrameworkPackage/view/structure/BasicStructure',
            //     'widgetChanges' => array(
            //         'mainContent' => 'projects/Meheszellato/view/widget/ESContentWidget',
            //         'left1' => 'projects/Meheszellato/view/widget/SideSubmenuWidget',
            //         // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
            //     )
            // ),
            // array(
            //     'name' => 'elastisite_bannerWidget',
            //     'paramChains' => array(
            //         'elastisite/bannerWidget' => 'default'
            //     ),
            //     'controller' => 'framework/packages/FrameworkPackage/controller/PublicAccessoriesWidgetController',
            //     'action' => 'elastiSiteBannerWidgetAction',
            //     'permission' => 'viewGuestContent'
            // ),

            // array(
            //     'name' => 'elastisite_index',
            //     'paramChains' => array(
            //         'elastisite' => 'default'
            //     ),
            //     'controller' => 'projects/Meheszellato/controller/ElastiSiteController',
            //     'action' => 'indexAction',
            //     'permission' => 'viewGuestContent',
            //     'title' => 'admin.handle.backgrounds',
            //     'structure' => 'ElastiSitePackage/view/structure/ESBasic',
            //     'widgetChanges' => array(
            //         'mainContent' => 'DevPackage/view/widget/ElastiSiteIndexWidget',
            //         'leftTopContent' => 'SchedulePackage/view/widget/CalendarWidget'
            //     )
            // ),
            // array(
            //     'name' => 'elastisite_indexWidget',
            //     'paramChains' => array(
            //         'elastisite/indexWidget' => 'default'
            //     ),
            //     'controller' => 'projects/Meheszellato/controller/ProjectWidgetController',
            //     'action' => 'elastiSiteIndexWidgetAction',
            //     'permission' => 'viewGuestContent'
            // ),

            // array(
            //     'name' => 'elastisite_information_index',
            //     'paramChains' => array(
            //         'elastisite/information' => 'default'
            //     ),
            //     'controller' => 'projects/Meheszellato/controller/ElastiSiteInformationController',
            //     'action' => 'informationIndexAction',
            //     'permission' => 'viewGuestContent',
            //     'title' => 'product.information',
            //     'structure' => 'ElastiSitePackage/view/structure/ESBasic',
            //     'backgroundColor' => '6f7179',
            //     'widgetChanges' => array(
            //         'mainContent' => 'ElastiSitePackage/view/widget/ESInformationIndexWidget',
            //         'leftTopContent' => 'ElastiSitePackage/view/widget/ESCustomersDocumentsWidget'
            //     )
            // ),
            // array(
            //     'name' => 'elastisite_information_indexWidget',
            //     'paramChains' => array(
            //         'elastisite/ESInformationIndexWidget' => 'default'
            //     ),
            //     'controller' => 'projects/Meheszellato/controller/ElastiSiteInformationWidgetController',
            //     'action' => 'eSInformationIndexWidgetAction',
            //     'permission' => 'viewGuestContent'
            // ),

            // array(
            //     'name' => 'elastisite_support_index',
            //     'paramChains' => array(
            //         'elastisite/support' => 'default'
            //     ),
            //     'controller' => 'projects/Meheszellato/controller/ElastiSiteSupportController',
            //     'action' => 'supportIndexAction',
            //     'permission' => 'viewGuestContent',
            //     'title' => 'elastisite.support',
            //     'structure' => 'ElastiSitePackage/view/structure/ESBasic',
            //     'backgroundColor' => 'd12bb4',
            //     'widgetChanges' => array(
            //         'mainContent' => 'DevPackage/view/widget/ESSupportIndexWidget',
            //         'leftTopContent' => 'ElastiSitePackage/view/widget/ESSupportDocumentsWidget'
            //     )
            // ),
            // array(
            //     'name' => 'elastisite_support_indexWidget',
            //     'paramChains' => array(
            //         'elastisite/support/indexWidget' => 'default'
            //     ),
            //     'controller' => 'projects/Meheszellato/controller/ElastiSiteSupportWidgetController',
            //     'action' => 'eSSupportIndexWidgetAction',
            //     'permission' => 'viewGuestContent'
            // ),
            // array(
            //     'name' => 'elastisite_support_documents',
            //     'paramChains' => array(
            //         'elastisite/support/documents/{slug}' => 'default'
            //     ),
            //     'controller' => 'projects/Meheszellato/controller/ElastiSiteSupportController',
            //     'action' => 'eSSupportDocumentReaderAction',
            //     'permission' => 'viewGuestContent',
            //     'title' => 'document.reader',
            //     'structure' => 'FrameworkPackage/view/structure/BasicStructure',
            //     'widgetChanges' => array(
            //         'mainContent' => 'ElastiSitePackage/view/widget/ESSupportDocumentReaderWidget',
            //         'left1' => 'ElastiSitePackage/view/widget/ESSupportDocumentsWidget',
            //         // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
            //     )
            // ),

            // array(
            //     'name' => 'elastisite_support_documents_widget',
            //     'paramChains' => array(
            //         'elastisite/support/documentsWidget' => 'default'
            //     ),
            //     'controller' => 'projects/Meheszellato/controller/ElastiSiteSupportWidgetController',
            //     'action' => 'eSSupportDocumentsWidgetAction',
            //     'permission' => 'viewGuestContent'
            // ),
            // array(
            //     'name' => 'elastisite_support_documents_readerWidget',
            //     'paramChains' => array(
            //         'elastisite/support/documentReaderWidget' => 'default'
            //     ),
            //     'controller' => 'projects/Meheszellato/controller/ElastiSiteSupportWidgetController',
            //     'action' => 'eSSupportDocumentReaderWidgetAction',
            //     'permission' => 'viewGuestContent'
            // ),

            // array(
            //     'name' => 'elastisite_conditions_index',
            //     'paramChains' => array(
            //         'elastisite/conditions' => 'default'
            //     ),
            //     'controller' => 'projects/Meheszellato/controller/ElastiSiteConditionsController',
            //     'action' => 'conditionsIndexAction',
            //     'permission' => 'viewGuestContent',
            //     'title' => 'elastisite.terms.and.conditions',
            //     'structure' => 'ElastiSitePackage/view/structure/ESBasic',
            //     'backgroundColor' => '96070b',
            //     'widgetChanges' => array(
            //         'mainContent' => 'DevPackage/view/widget/ESConditionsIndexWidget',
            //         'leftTopContent' => 'ElastiSitePackage/view/widget/ESConditionsDocumentsWidget'
            //     )
            // ),
            // array(
            //     'name' => 'elastisite_conditions_indexWidget',
            //     'paramChains' => array(
            //         'elastisite/conditions/indexWidget' => 'default'
            //     ),
            //     'controller' => 'projects/Meheszellato/controller/ElastiSiteConditionsWidgetController',
            //     'action' => 'eSConditionsIndexWidgetAction',
            //     'permission' => 'viewGuestContent'
            // ),

            // array(
            //     'name' => 'elastisite_conditions_documents',
            //     'paramChains' => array(
            //         'elastisite/conditions/documents/{slug}' => 'default'
            //     ),
            //     'controller' => 'projects/Meheszellato/controller/ElastiSiteConditionsController',
            //     'action' => 'eSConditionsDocumentReaderAction',
            //     'permission' => 'viewGuestContent',
            //     'title' => 'document.reader',
            //     'structure' => 'ElastiSitePackage/view/structure/ESBasic',
            //     // 'skinName' => 'ModernObsidian',
            //     'backgroundColor' => '96070b',
            //     'widgetChanges' => array(
            //         'mainContent' => 'ElastiSitePackage/view/widget/ESConditionsDocumentReaderWidget',
            //         'leftTopContent' => 'ElastiSitePackage/view/widget/ESConditionsDocumentsWidget'
            //     ),
            // ),
            // array(
            //     'name' => 'elastisite_conditions_documents_widget',
            //     'paramChains' => array(
            //         'elastisite/conditions/documentsWidget' => 'default'
            //     ),
            //     'controller' => 'projects/Meheszellato/controller/ElastiSiteConditionsWidgetController',
            //     'action' => 'eSConditionsDocumentsWidgetAction',
            //     'permission' => 'viewGuestContent'
            // ),
            // array(
            //     'name' => 'elastisite_conditions_documentRaderWidget',
            //     'paramChains' => array(
            //         'elastisite/conditions/documentReaderWidget' => 'default'
            //     ),
            //     'controller' => 'projects/Meheszellato/controller/ElastiSiteConditionsWidgetController',
            //     'action' => 'eSConditionsDocumentReaderWidgetAction',
            //     'permission' => 'viewGuestContent'
            // ),

            // array(
            //     'name' => 'elastisite_customers_index',
            //     'paramChains' => array(
            //         'elastisite/customers' => 'default'
            //     ),
            //     'controller' => 'projects/Meheszellato/controller/ElastiSiteCustomersController',
            //     'action' => 'customersIndexAction',
            //     'permission' => 'viewGuestContent',
            //     'title' => 'elastisite.customers',
            //     'structure' => 'ElastiSitePackage/view/structure/ESBasic',
            //     'backgroundColor' => '49a938',
            //     'widgetChanges' => array(
            //         'mainContent' => 'DevPackage/view/widget/ESCustomersIndexWidget',
            //         'leftTopContent' => 'ElastiSitePackage/view/widget/ESCustomersDocumentsWidget'
            //     )
            // ),
            // array(
            //     'name' => 'elastisite_customers_indexWidget',
            //     'paramChains' => array(
            //         'elastisite/customers/indexWidget' => 'default'
            //     ),
            //     'controller' => 'projects/Meheszellato/controller/ElastiSiteCustomersWidgetController',
            //     'action' => 'eSCustomersIndexWidgetAction',
            //     'permission' => 'viewGuestContent'
            // ),
            // array(
            //     'name' => 'elastisite_customers_documents',
            //     'paramChains' => array(
            //         'elastisite/customers/documents/{slug}' => 'default'
            //     ),
            //     'controller' => 'projects/Meheszellato/controller/ElastiSiteCustomersController',
            //     'action' => 'eSCustomersDocumentsAction',
            //     'permission' => 'viewGuestContent',
            //     'title' => 'document.reader',
            //     'structure' => 'ElastiSitePackage/view/structure/ESBasic',
            //     'widgetChanges' => array(
            //         'mainContent' => 'LegalPackage/view/widget/DocumentReaderWidget',
            //         'leftTopContent' => 'SchedulePackage/view/widget/ESCustomersDocumentsWidget'
            //     ),
            // ),
            // array(
            //     'name' => 'elastisite_customers_documentsWidget',
            //     'paramChains' => array(
            //         'elastisite/customers/documentsWidget' => 'default'
            //     ),
            //     'controller' => 'projects/Meheszellato/controller/ElastiSiteCustomersWidgetController',
            //     'action' => 'eSCustomersDocumentsWidgetAction',
            //     'permission' => 'viewGuestContent'
            // ),
            // array(
            //     'name' => 'elastisite_customers_documentReaderWidget',
            //     'paramChains' => array(
            //         'elastisite/customers/documentReaderWidget' => 'default'
            //     ),
            //     'controller' => 'projects/Meheszellato/controller/ElastiSiteCustomersWidgetController',
            //     'action' => 'eSCustomersDocumentReaderWidgetAction',
            //     'permission' => 'viewGuestContent'
            // )
        );
    }
}
