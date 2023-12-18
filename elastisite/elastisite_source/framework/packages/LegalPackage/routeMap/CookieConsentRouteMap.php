<?php
namespace framework\packages\LegalPackage\routeMap;

class CookieConsentRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'widget_CookieBoxWidget',
                'paramChains' => array(
                    'widget/CookieBoxWidget' => 'default'
                ),
                'controller' => 'framework/packages/LegalPackage/controller/CookieConsentWidgetController',
                'action' => 'cookieBoxWidgetAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'widget_CookieNoticeWidget',
                'paramChains' => array(
                    'widget/CookieNoticeWidget' => 'default'
                ),
                'controller' => 'framework/packages/LegalPackage/controller/CookieConsentWidgetController',
                'action' => 'cookieNoticeWidgetAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'widget_CookieNoticeWidget_submit',
                'paramChains' => array(
                    'widget/CookieNoticeWidget_submit' => 'default'
                ),
                'controller' => 'framework/packages/LegalPackage/controller/CookieConsentWidgetController',
                'action' => 'cookieNoticeWidgetSubmitAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'documents_cookie-info',
                'paramChains' => array(
                    'documents/cookie-info' => 'en',
                    'dokumentumok/sutikezelesi-tajekoztato' => 'hu'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/FrameworkPageController',
                'action' => 'standardAction',
                'permission' => 'viewGuestContent',
                'title' => 'cookie.handling.information',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                'widgetChanges' => array(
                    'mainContent' => 'LegalPackage/view/widget/DocumentReaderWidget',
                    // 'left1' => 'UserPackage/view/widget/LoginWidget',
                    // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
                )
            ),
            array(
                'name' => 'cookieConsent_showLogo',
                'paramChains' => array(
                    'cookieConsent/showLogo/{subscriber}' => 'default'
                ),
                'controller' => 'framework/packages/LegalPackage/controller/CookieConsentImageController',
                'action' => 'cookieConsentShowLogoAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'widget_CookieConsentWidget_removeAllConsent',
                'paramChains' => array(
                    'widget/CookieConsentWidget_removeAllConsent' => 'default'
                ),
                'controller' => 'framework/packages/LegalPackage/controller/CookieConsentWidgetController',
                'action' => 'removeAllConsentAction',
                'permission' => 'viewGuestContent'
            ),
            // array(
            //     'name' => 'widget_CookieConsentWidget_removeRefusedConsent',
            //     'paramChains' => array(
            //         'widget/CookieConsentWidget_removeRefusedConsent' => 'default'
            //     ),
            //     'controller' => 'framework/packages/LegalPackage/controller/CookieConsentWidgetController',
            //     'action' => 'removeRefusedConsentAction',
            //     'permission' => 'viewGuestContent'
            // ),
        );
    }
}
