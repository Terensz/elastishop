<?php
namespace framework\packages\UserPackage\routeMap;

class UserRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'logout',
                'paramChains' => array(
                    'logout' => 'default'
                ),
                'controller' => 'framework/packages/UserPackage/controller/UserController',
                'action' => 'logoutAction',
                'permission' => 'viewGuestContent',
                'title' => 'ElastiSite',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                // 'skinName' => 'ModernObsidian',
                // 'backgroundColor' => '3897c5',
                // 'widgetChanges' => array(
                //     'left1' => 'UserPackage/view/widget/Login2Widget',
                //     // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
                // ),
            ),
            array(
                'name' => 'widget_LoginWidget',
                'paramChains' => array(
                    'widget/LoginWidget' => 'default'
                ),
                'controller' => 'framework/packages/UserPackage/controller/LoginWidgetController',
                'action' => 'loginWidgetAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'widget_LoginNoRegLinkWidget',
                'paramChains' => array(
                    'widget/LoginNoRegLinkWidget' => 'default'
                ),
                'controller' => 'framework/packages/UserPackage/controller/LoginWidgetController',
                'action' => 'loginNoRegLinkWidgetAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'widget_Login2Widget',
                'paramChains' => array(
                    'widget/Login2Widget' => 'default'
                ),
                'controller' => 'framework/packages/UserPackage/controller/LoginWidgetController',
                'action' => 'login2WidgetAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'ajax_logout',
                'paramChains' => array(
                    'ajax/logout' => 'default'
                ),
                'controller' => 'framework/packages/UserPackage/controller/LogoutWidgetController',
                'action' => 'logoutAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'ajax_changePassword',
                'paramChains' => array(
                    'ajax/changePassword' => 'default'
                ),
                'controller' => 'framework/packages/UserPackage/controller/UserWidgetController',
                'action' => 'changePasswordAction',
                'permission' => 'viewUserContent'
            ),
            array(
                'name' => 'ajax_forgottenPassword',
                'paramChains' => array(
                    'ajax/forgottenPassword' => 'default'
                ),
                'controller' => 'framework/packages/UserPackage/controller/UserWidgetController',
                'action' => 'forgottenPasswordAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'ajax_forgottenPassword_send',
                'paramChains' => array(
                    'ajax/forgottenPassword/send' => 'default'
                ),
                'controller' => 'framework/packages/UserPackage/controller/UserWidgetController',
                'action' => 'forgottenPasswordSendAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'ajax_redeemPasswordRecoveryToken',
                'paramChains' => array(
                    'redeemPasswordRecoveryToken/{token}' => 'default'
                ),
                'controller' => 'framework/packages/UserPackage/controller/UserController',
                'action' => 'redeemPasswordRecoveryTokenAction',
                'permission' => 'viewGuestContent',
                'inMenu' => 'main',
                'title' => 'user.area',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                'widgetChanges' => array(
                    'mainContent' => 'ArticlePackage/view/widget/RedeemPasswordRecoveryTokenWidget',
                    // 'left1' => 'UserPackage/view/widget/LoginWidget',
                    // // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
                )
            ),
            array(
                'name' => 'widget_RedeemPasswordRecoveryTokenWidget',
                'paramChains' => array(
                    'widget/RedeemPasswordRecoveryTokenWidget' => 'default'
                ),
                'controller' => 'framework/packages/UserPackage/controller/UserWidgetController',
                'action' => 'redeemPasswordRecoveryTokenWidgetAction',
                'permission' => 'viewGuestContent'
            ),
            // array(
            //     'name' => 'user_index',
            //     'paramChains' => array(
            //         'felhasznalok' => 'hu',
            //         'users' => 'en'
            //     ),
            //     'controller' => 'framework/packages/UserPackage/controller/UserController',
            //     'action' => 'userIndexAction',
            //     'permission' => 'viewGuestContent',
            //     'inMenu' => 'main',
            //     'title' => 'user.area',
            //     'structure' => 'FrameworkPackage/view/structure/BasicStructure',
            //     'widgetChanges' => array(
            //         'left1' => 'UserPackage/view/widget/LoginWidget',
            //         // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
            //     )
            // ),
            // array(
            //     'name' => 'user_removePersonalData',
            //     'paramChains' => array(
            //         'felhasznalo/szemelyesAdataimTorlese' => 'hu',
            //         'user/removeMyPersonalData' => 'en'
            //     ),
            //     'controller' => 'framework/packages/UserPackage/controller/UserController',
            //     'action' => 'userRegistrationAction',
            //     'permission' => 'viewUserContent',
            //     'inMenu' => 'main',
            //     'title' => 'removing.my.personal.data',
            //     'structure' => 'FrameworkPackage/view/structure/BasicStructure',
            //     'widgetChanges' => array(
            //         'mainContent' => 'UserPackage/view/widget/RemoveMyPersonalDataWidget',
            //         'left1' => 'UserPackage/view/widget/LoginWidget',
            //         // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
            //     ),
            //     'pageSwitchBehavior' => array(
            //         'UserRegistrationWidget' => 'restore'
            //     )
            // ),
            // array(
            //     'name' => 'user_RemoveMyPersonalDataWidget',
            //     'paramChains' => array(
            //         'user/RemoveMyPersonalDataWidget' => 'default'
            //     ),
            //     'controller' => 'framework/packages/UserPackage/controller/UserWidgetController',
            //     'action' => 'removeMyPersonalDataWidgetAction',
            //     'permission' => 'viewUserContent'
            // ),
            array(
                'name' => 'widget_LoginGuideWidget',
                'paramChains' => array(
                    'widget/LoginGuideWidget' => 'default'
                ),
                'controller' => 'framework/packages/UserPackage/controller/LoginWidgetController',
                'action' => 'loginGuideWidgetAction',
                'permission' => 'viewLoggedOutContent'
            ),

            array(
                'name' => 'user_registration',
                'paramChains' => array(
                    'regisztracio' => 'hu',
                    'registration' => 'en'
                ),
                'controller' => 'framework/packages/UserPackage/controller/UserController',
                'action' => 'userRegistrationAction',
                'permission' => 'viewGuestContent',
                'inMenu' => 'main',
                'title' => 'registration',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                'widgetChanges' => array(
                    'mainContent' => 'UserPackage/view/widget/UserRegistrationWidget',
                    // 'left1' => 'UserPackage/view/widget/LoginWidget',
                    // // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
                ),
                // 'pageSwitchBehavior' => array(
                //     'UserRegistrationWidget' => 'restore'
                // )
            ),
            array(
                'name' => 'user_registration_activation',
                'paramChains' => array(
                    'regisztracio/aktivalas/{mixedToken}' => 'hu',
                    'registration/activation/{mixedToken}' => 'en'
                ),
                'controller' => 'framework/packages/UserPackage/controller/UserController',
                'action' => 'userRegistrationActivationAction',
                'permission' => 'viewGuestContent',
                'title' => 'registration.activation',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                'widgetChanges' => array(
                    'mainContent' => 'UserPackage/view/widget/RegActivationWidget',
                    // 'left1' => 'UserPackage/view/widget/LoginWidget',
                    // // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
                )
            ),
            array(
                'name' => 'user_registration_widget',
                'paramChains' => array(
                    'user/registration/widget' => 'default'
                ),
                'controller' => 'framework/packages/UserPackage/controller/UserWidgetController',
                'action' => 'userRegistrationWidgetAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'user_registration_activation_widget',
                'paramChains' => array(
                    'user/registration/activation/widget' => 'default'
                ),
                'controller' => 'framework/packages/UserPackage/controller/UserWidgetController',
                'action' => 'regActivationWidgetAction',
                'permission' => 'viewGuestContent'
            ),
            // Admin FBSUsers
            array(
                'name' => 'admin_FBSUsers',
                'paramChains' => array(
                    'admin/FBSUsers' => 'default'
                ),
                'controller' => 'framework/packages/UserPackage/controller/UserController',
                'action' => 'adminFBSUsersAction',
                'permission' => 'adminAdmins',
                'title' => 'admin.admins.title',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'skinName' => 'Basic',
                'widgetChanges' => array(
                    'mainContent' => 'UserPackage/view/widget/AdminFBSUsersWidget'
                )
            ),
            array(
                'name' => 'admin_FBSUsers_widget',
                'paramChains' => array(
                    'admin/FBSUsers/widget' => 'default'
                ),
                'controller' => 'framework/packages/UserPackage/controller/UserWidgetController',
                'action' => 'adminFBSUsersWidgetAction',
                'permission' => 'adminAdmins'
            ),
            array(
                'name' => 'admin_FBSUser_edit',
                'paramChains' => array(
                    'admin/FBSUser/edit' => 'default'
                ),
                'controller' => 'framework/packages/UserPackage/controller/UserWidgetController',
                'action' => 'adminFBSUserEditAction',
                'permission' => 'adminAdmins'
            ),
            array(
                'name' => 'admin_FBSUser_new',
                'paramChains' => array(
                    'admin/FBSUser/new' => 'default'
                ),
                'controller' => 'framework/packages/UserPackage/controller/UserWidgetController',
                'action' => 'adminFBSUserNewAction',
                'permission' => 'adminAdmins'
            ),
            array(
                'name' => 'admin_FBSUser_delete',
                'paramChains' => array(
                    'admin/FBSUser/delete' => 'default'
                ),
                'controller' => 'framework/packages/UserPackage/controller/UserWidgetController',
                'action' => 'adminFBSUserDeleteAction',
                'permission' => 'adminAdmins'
            ),
            // Admin users
            array(
                'name' => 'admin_userAccounts',
                'paramChains' => array(
                    'admin/userAccounts' => 'default'
                ),
                'controller' => 'framework/packages/UserPackage/controller/UserController',
                'action' => 'adminUserAccountsAction',
                'permission' => 'adminUsers',
                'title' => 'admin.users.title',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'skinName' => 'Basic',
                'widgetChanges' => array(
                    'mainContent' => 'UserPackage/view/widget/AdminUserAccountsWidget'
                )
                // 'pageSwitchBehavior' => array(
                //     'AdminUsersWidget' => 'restore'
                // )
            ),
            array(
                'name' => 'admin_intrusionAttempts',
                'paramChains' => array(
                    'admin/intrusionAttempts' => 'default'
                ),
                'controller' => 'framework/packages/UserPackage/controller/UserController',
                'action' => 'adminIntrusionAttemptsAction',
                'permission' => 'adminUsers',
                'title' => 'intrusion.attempts',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'skinName' => 'Basic',
                'widgetChanges' => array(
                    'mainContent' => 'UserPackage/view/widget/AdminIntrusionAttemptsWidget'
                )
                // 'pageSwitchBehavior' => array(
                //     'AdminUsersWidget' => 'restore'
                // )
            ),
            array(
                'name' => 'admin_intrusionAttempts_widget',
                'paramChains' => array(
                    'admin/intrusionAttempts/widget' => 'default'
                ),
                'controller' => 'framework/packages/UserPackage/controller/UserWidgetController',
                'action' => 'adminIntrusionAttemptsWidgetAction',
                'permission' => 'adminUsers'
            ),
            array(
                'name' => 'admin_intrusionAttempt_edit',
                'paramChains' => array(
                    'admin/intrusionAttempt/edit' => 'default'
                ),
                'controller' => 'framework/packages/UserPackage/controller/UserWidgetController',
                'action' => 'adminIntrusionAttemptEditAction',
                'permission' => 'adminUsers'
            ),
            // array(
            //     'name' => 'admin_user_search',
            //     'paramChains' => array(
            //         'admin/user/search' => 'default'
            //     ),
            //     'controller' => 'framework/packages/UserPackage/controller/UserWidgetController',
            //     'action' => 'adminUserSearchAction',
            //     'permission' => 'adminUsers'
            // ),
            array(
                'name' => 'admin_userAccounts_widget',
                'paramChains' => array(
                    'admin/userAccounts/widget' => 'default'
                ),
                'controller' => 'framework/packages/UserPackage/controller/UserWidgetController',
                'action' => 'adminUserAccountsWidgetAction',
                'permission' => 'adminUsers'
            ),
            array(
                'name' => 'admin_userAccount_edit',
                'paramChains' => array(
                    'admin/userAccount/edit' => 'default'
                ),
                'controller' => 'framework/packages/UserPackage/controller/UserWidgetController',
                'action' => 'adminUserAccountEditAction',
                'permission' => 'adminUsers'
            ),
            array(
                'name' => 'admin_userAccount_delete',
                'paramChains' => array(
                    'admin/userAccount/delete' => 'default'
                ),
                'controller' => 'framework/packages/UserPackage/controller/UserWidgetController',
                'action' => 'adminUserAccountDeleteAction',
                'permission' => 'adminUsers'
            )
        );
    }
}
