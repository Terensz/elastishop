<?php
namespace framework\packages\LegalPackage\routeMap;

class LegalDocumentsRouteMap
{
    public static function get()
    {
        return array(
            // array(
            //     'name' => 'document_reader',
            //     'paramChains' => array(
            //         'documents/{slug}' => 'default'
            //     ),
            //     'controller' => 'framework/packages/LegalPackage/controller/DocumentsController',
            //     'action' => 'documentReaderAction',
            //     'permission' => 'viewGuestContent',
            //     'title' => 'document.reader',
            //     'structure' => 'FrameworkPackage/view/structure/BasicStructure',
            //     'widgetChanges' => array(
            //         'mainContent' => 'LegalPackage/view/widget/DocumentReaderWidget',
            //         'left1' => 'UserPackage/view/widget/LoginWidget',
            //         // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
            //     )
            // ),
            array(
                'name' => 'document_reader_widget',
                'paramChains' => array(
                    'documents/documentReader/widget' => 'default'
                ),
                'controller' => 'framework/packages/LegalPackage/controller/LegalWidgetController',
                'action' => 'documentReaderWidgetAction',
                'permission' => 'viewGuestContent'
            ),

            array(
                'name' => 'documents_terms-of-use',
                'paramChains' => array(
                    'documents/terms-of-use' => 'en',
                    'dokumentumok/felhasznalasi-feltetelek' => 'hu'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/FrameworkPageController',
                'action' => 'standardAction',
                'permission' => 'viewGuestContent',
                'title' => 'terms.of.use',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                'widgetChanges' => array(
                    'mainContent' => 'LegalPackage/view/widget/DocumentReaderWidget',
                    // 'left1' => 'UserPackage/view/widget/LoginWidget',
                    // // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
                )
            ),
            array(
                'name' => 'documents_what-is-gdpr',
                'paramChains' => array(
                    'documents/what-is-gdpr' => 'en',
                    'dokumentumok/mi-is-az-a-gdpr' => 'hu'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/FrameworkPageController',
                'action' => 'standardAction',
                'permission' => 'viewGuestContent',
                'title' => 'what.is.gdpr',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                'widgetChanges' => array(
                    'mainContent' => 'LegalPackage/view/widget/DocumentReaderWidget',
                    // 'left1' => 'UserPackage/view/widget/LoginWidget',
                    // // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
                )
            ),
            array(
                'name' => 'documents_how-do-we-protect-personal-data',
                'paramChains' => array(
                    'documents/how-do-we-protect-personal-data' => 'en',
                    'dokumentumok/hogyan-vedjuk-a-szemelyes-adatokat' => 'hu'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/FrameworkPageController',
                'action' => 'standardAction',
                'permission' => 'viewGuestContent',
                'title' => 'how.do.we.protect.personal.data',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                'widgetChanges' => array(
                    'mainContent' => 'LegalPackage/view/widget/DocumentReaderWidget',
                    // 'left1' => 'UserPackage/view/widget/LoginWidget',
                    // // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
                )
            ),
            array(
                'name' => 'documents_privacy-statement',
                'paramChains' => array(
                    'documents/privacy-statement' => 'en',
                    'dokumentumok/adatvedelmi-nyilatkozat' => 'hu'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/FrameworkPageController',
                'action' => 'standardAction',
                'permission' => 'viewGuestContent',
                'title' => 'privacy.statement',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                'widgetChanges' => array(
                    'mainContent' => 'LegalPackage/view/widget/DocumentReaderWidget',
                    // 'left1' => 'UserPackage/view/widget/LoginWidget',
                    // // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
                )
            ),
            array(
                'name' => 'documents_service-providers',
                'paramChains' => array(
                    'documents/service-providers' => 'en',
                    'dokumentumok/szolgaltatok' => 'hu'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/FrameworkPageController',
                'action' => 'standardAction',
                'permission' => 'viewGuestContent',
                'title' => 'privacy.statement',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                'widgetChanges' => array(
                    'mainContent' => 'LegalPackage/view/widget/DocumentReaderWidget',
                    // 'left1' => 'UserPackage/view/widget/LoginWidget',
                    // // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
                )
            )
            // array(
            //     'name' => 'documents_about-removing-personal-data',
            //     'paramChains' => array(
            //         'documents/about-removing-personal-data' => 'en',
            //         'dokumentumok/a-szemelyes-adatok-torleserol' => 'hu'
            //     ),
            //     'controller' => 'framework/packages/FrameworkPackage/controller/FrameworkPageController',
            //     'action' => 'standardAction',
            //     'permission' => 'viewGuestContent',
            //     'title' => 'about.removing.personal.data',
            //     'structure' => 'FrameworkPackage/view/structure/BasicStructure',
            //     'widgetChanges' => array(
            //         'mainContent' => 'LegalPackage/view/widget/DocumentReaderWidget',
            //         // 'left1' => 'UserPackage/view/widget/LoginWidget',
            //         // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
            //     )
            // )
        );
    }
}
