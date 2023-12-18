<?php
namespace framework\packages\UserPackage\service;

use App;
use framework\component\parent\Service;
use framework\packages\UserPackage\entity\User;
use framework\component\exception\ElastiException;

/**
* Ez az osztaly keresi meg a jogosultsagot a jogosultsagi csoport alapjan.
*/
class Permission extends Service
{
    /**
     * When you are logged in, this permission is not available for you.
    */
    const LOGGED_OUT_PERMISSION_GROUP = 'loggedOut';

    /**
     * You are just a visitor. User's content will be unavailable for you.
    */
    const GUEST_PERMISSION_GROUP = 'guest';

    /**
     * This comes available for you, when the database differs from the program code, or the site is brand new and not configured yet.
    */
    const RECOVERY_GUEST_PERMISSION_GROUP = 'recoveryGuest';

    /**
     * You are logged in as a registered user.
    */
    const USER_PERMISSION_GROUP = 'user';

    /**
     * Site admins can promote your user account to tester.
    */
    const TESTER_PERMISSION_GROUP = 'tester';

    /**
     * You can visit the webshop even if it's turned off, and both the payment and VAT declaration will go on the test interfaces.
    */
    const WEBSHOP_TESTER_PERMISSION_GROUP = 'webshopTester';

    /**
     * As a user, you will automatically get this group as well. Admins cannot see this content.
    */
    const ONLY_USER_NOT_ADMIN_PERMISSION_GROUP = 'onlyUserNotAdmin';

    /**
     * You are not logged in as a user until you won't prove, tha you receive the token.
    */
    const TOKEN_REQUIRED_PERMISSION_GROUP = 'tokenRequired';

    /**
     * Member of the SITE CO-WORKERS
     * ================================
     * You are now a mini-admin of a site created within this project. Site admins can create this account, and they also can get it back.
    */
    const SITE_SUPERVISOR_PERMISSION_GROUP = 'siteSupervisor';

    /**
     * Member of the PROJECT CO-WORKERS
     * ================================
     * You are a user, but registered a site, and you own it until your subscription is cancelled.
    */
    const SITE_ADMIN_PERMISSION_GROUP = 'siteAdmin';

    const PROJECT_AUDITOR_PERMISSION_GROUP = 'projectAuditor';

    /**
     * Member of the PROJECT CO-WORKERS
     * ================================
     * You are now a mini-admin of the entire project. Project admins can create this account, and they also can get it back.
    */
    const PROJECT_SUPERVISOR_PERMISSION_GROUP = 'projectSupervisor';

    /**
     * Member of the PROJECT CO-WORKERS
     * ================================
     * You are in charge on the project customer's side. But you don'T know, that spmeone is above you ;-)
    */
    const PROJECT_ADMIN_PERMISSION_GROUP = 'projectAdmin';

    const STAFF_MEMBER_PERMISSION_GROUP = 'staffMember';

    /**
     * You are the developer, and your customer sometimes needs your support. You can see anything except the registered users, because of the GDPR.
    */
    const SYSTEM_ADMIN_PERMISSION_GROUP = 'systemAdmin';

    // const PERMISSIONS_OF_GROUPS = [
    //     'loggedOut' => ['viewLoggedOutContent'],
    //     'guest' => ['viewGuestContent'],
    //     'recoveryGuest' => ['loadSetup'],
    //     'user' => ['viewGuestContent', 'viewUserContent'],
    //     'tester' => ['viewGuestContent', 'viewUserContent', 'viewTesterContent'],
    //     'onlyUserNotAdmin' => ['viewOnlyUserNotAdminContent'],
    //     'tokenRequired' => ['viewGuestContent', 'viewTokenRequiredContent'],
    //     'projectSupervisor' => ['viewGuestContent', 'viewUserContent', 'viewProjectCoworkerContent', 'viewProjectAdminContent', 'adminUsers', 'loadSetup'],
    //     'projectAdmin' => ['viewGuestContent', 'viewUserContent', 'viewProjectCoworkerContent', 'viewProjectAdminContent', 'adminUsers', 'loadSetup'],
    //     'systemAdmin' => ['viewGuestContent', 'viewUserContent', 'viewProjectCoworkerContent', 'viewProjectAdminContent' , 'viewSystemAdminContent', 'adminUsers', 'adminAdmins', 'loadSetup']
    // ];

    const PERMISSIONS_OF_GROUPS = [
        self::LOGGED_OUT_PERMISSION_GROUP => ['viewLoggedOutContent'],
        self::GUEST_PERMISSION_GROUP => ['viewGuestContent'],
        self::RECOVERY_GUEST_PERMISSION_GROUP => ['loadSetup'],
        self::USER_PERMISSION_GROUP => ['viewGuestContent', 'viewUserContent'],
        self::TESTER_PERMISSION_GROUP => ['viewTesterContent'],
        self::WEBSHOP_TESTER_PERMISSION_GROUP => ['viewWebshopTesterContent'],
        self::ONLY_USER_NOT_ADMIN_PERMISSION_GROUP => ['viewOnlyUserNotAdminContent'],
        self::TOKEN_REQUIRED_PERMISSION_GROUP => ['viewGuestContent', 'viewTokenRequiredContent'],

        /**
         * Site co-workers
        */
        self::SITE_SUPERVISOR_PERMISSION_GROUP => ['viewGuestContent', 'viewUserContent', 'viewProjectCoworkerContent', 'viewSiteSupervisorContent', 'viewSiteHelperContent'],
        self::SITE_ADMIN_PERMISSION_GROUP => ['viewGuestContent', 'viewUserContent', 'viewProjectCoworkerContent', 'viewSiteSupervisorContent', 'viewSiteHelperContent', 'viewSiteAdminContent'],

        /**
         * Project co-workers
        */
        // pass: P&mXs9UNC&qfZ21
        self::PROJECT_AUDITOR_PERMISSION_GROUP => ['viewGuestContent', 'viewUserContent', 'viewSiteHelperContent', 'viewProjectCoworkerContent'],
        self::PROJECT_SUPERVISOR_PERMISSION_GROUP => ['viewGuestContent', 'viewUserContent', 'viewSiteHelperContent', 'viewProjectCoworkerContent', 'viewProjectSupervisorContent', 'loadSetup'],
        self::PROJECT_ADMIN_PERMISSION_GROUP => ['viewGuestContent', 'viewUserContent', 'viewSiteHelperContent', 'viewProjectCoworkerContent', 'viewProjectSupervisorContent', 'viewProjectAdminContent', 'adminUsers', 'loadSetup'],
        self::STAFF_MEMBER_PERMISSION_GROUP => ['viewGuestContent', self::VIEW_STAFF_MEMBER_CONTENT],

        /**
         * Developer
        */
        self::SYSTEM_ADMIN_PERMISSION_GROUP => ['viewGuestContent', 'viewUserContent', 'viewProjectCoworkerContent', 'viewProjectAdminContent' , 'viewSystemAdminContent', 'adminUsers', 'adminAdmins', 'loadSetup']
    ];

    const BASIC_PERMISSION_GROUPS = ['loggedOut', 'guest'];
    const BASIC_USER_PERMISSION_GROUPS = ['user', 'onlyUserNotAdmin'];
    const BASIC_STAFF_MEMBER_PERMISSION_GROUPS = [self::STAFF_MEMBER_PERMISSION_GROUP];

    const VIEW_STAFF_MEMBER_CONTENT = 'viewStaffMemberContent';

    public static function check($permission, $userObject = null)
    {
        $user = $userObject ? $userObject : App::getContainer()->getUser();

        if (!is_object($user) || !($user instanceof User)) {
            App::getContainer()->setUser(null);
            App::getContainer()->getSession()->set('userId', null);
            App::getContainer()->getSession()->set('user', null);
            App::getContainer()->getSession()->set('userStorageType', null);
            // header('Location: /');
            // throw new ElastiException('Missing User object', ElastiException::ERROR_TYPE_SECRET_PROG);
        }
        $permissionGroups = $user->getPermissionGroups();

        return (in_array($permission, self::getPermissions($permissionGroups)) !== false) ? true : false;
    }

    public static function getPermissions($permissionGroups)
    {
        $permissions = [];
        foreach (self::PERMISSIONS_OF_GROUPS as $group => $value) {
            if (in_array($group, $permissionGroups)) {
                foreach ($value as $groupPermission) {
                    if (!in_array($groupPermission, $permissions)) {
                        $permissions[] = $groupPermission;
                    }
                }
            }
        }
        // dump($this->getContainer()->getUser());//exit;
        
        return $permissions;
    }
}
