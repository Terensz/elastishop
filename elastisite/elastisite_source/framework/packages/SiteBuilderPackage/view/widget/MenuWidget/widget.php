<?php

use framework\packages\UserPackage\entity\User;
use framework\packages\UserPackage\service\Permission;

$config = [];
$config['showHandlePersonalDataLink'] = false;

$menuItemRoutes = [
    // [
    //     'routeName' => 'homepage',
    //     'title' => 'homepage',
    //     'permission' => 'viewGuestContent'
    // ],
    [
      'routeName' => 'webshop_productList_noFilter',
      'title' => 'webshop',
      'permission' => 'viewGuestContent'
  ],
    // [
    //     'routeName' => 'loginOrRegister',
    //     'title' => 'login',
    //     'permission' => 'viewLoggedOutContent'
    // ],
];
$actualRouteName = App::getContainer()->getRouting()->getPageRoute()->getName();
// dump($actualRouteName);
?>
<style>
.banner-and-navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #fff;
    /* box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px; */
}

.navbar-container {
    flex: 1;
}

@media (max-width: 767px) {
    .banner-and-navbar {
        flex-direction: column; /* Egymás alá rendezés kisebb képernyőn */
        align-items: flex-start; /* Balra igazítás kisebb képernyőn */
    }

    .navbar-container {
        width: 100%; /* Teljes szélességű navbar kisebb képernyőn */
        margin-top: 10px; /* Kisméretű margó a navbar és a banner között */
    }
}

</style>

<div class="banner-and-navbar">

  <div class="banner-container"></div>
  <div class="navbar-container">
    <nav class="navbar navbar-expand-lg navbar-light">
      <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
    <?php foreach ($menuItemRoutes as $menuItemRoute): ?>
    <?php
        $routeName = $menuItemRoute['routeName'];
        $routePath = $routeFromLink = App::getContainer()->getKernelObject('RoutingHelper')->getLink($menuItemRoute['routeName']);
        $title = trans($menuItemRoute['title']);
        $permission = $menuItemRoute['permission'];

        $linkClass = 'nav-link';
        if ($routeName === $actualRouteName) {
            $linkClass .= ' active';
        }

        if (Permission::check($permission)):
    ?>
            <li class="nav-item">
              <a class="ajaxCallerLink <?php echo $linkClass; ?>" href="<?php echo $routePath; ?>"><?php echo $title; ?></a>
            </li>
    <?php
        endif;
    ?>
    <?php endforeach; ?>

    <?php 
    /**
     * User
    */
    ?>
    <?php if (App::getContainer()->isGranted('viewOnlyUserNotAdminContent') && App::getContainer()->getUser()->getUserAccount()): ?>
    <?php 
    // dump($routeName);
    $linkClass = $actualRouteName == 'user_handlePersonalData' ? ' nav-link active' : ' nav-link';
    ?>
            <?php if ($config['showHandlePersonalDataLink']): ?>
            <li class="nav-item">
              <a class="ajaxCallerLink <?php echo $linkClass; ?>" 
                href="<?php echo App::getContainer()->getKernelObject('RoutingHelper')->getLink('user_handlePersonalData'); ?>"><?php echo trans('logged.in'); ?>: <?php echo App::getContainer()->getUser()->getUserAccount()->getPerson()->getUsername(); ?>
              </a>
            </li>
            <?php endif; ?>
            <li class="nav-item">
              <a class="nav-link" 
                href="/logout"><?php echo trans('logout'); ?>
              </a>
            </li>

    <?php endif; ?>

    <?php 
    /**
     * Admin
    */
    ?>
    <?php if (App::getContainer()->getUser()->getType() == User::TYPE_ADMINISTRATOR): ?>
    <?php 
    // dump($routeName);
    $linkClass = $actualRouteName == 'user_handlePersonalData' ? ' nav-link active' : ' nav-link';
    ?>
            <?php if ($config['showHandlePersonalDataLink']): ?>
            <li class="nav-item">
              <a class="ajaxCallerLink <?php echo $linkClass; ?>" 
                href="<?php echo App::getContainer()->getKernelObject('RoutingHelper')->getLink('user_handlePersonalData'); ?>"><?php echo trans('logged.in'); ?>: <?php echo App::getContainer()->getUser()->getUserAccount()->getPerson()->getUsername(); ?>
              </a>
            </li>
            <?php endif; ?>
            <li class="nav-item">
              <a class="nav-link" 
                href="/logout"><?php echo trans('logout'); ?>
              </a>
            </li>

    <?php endif; ?>

    <?php if (App::getContainer()->isGranted('viewLoggedOutContent') && App::getContainer()->getUser()->getUserAccount()): ?>
    <?php 
    // Every cases.
    $linkClass = ' nav-link';
    ?>
            <li class="nav-item">
              <a class="ajaxCallerLink <?php echo $linkClass; ?>" 
                href="" onclick="LoginHandler.initLogin(event);"><?php echo trans('login'); ?>
              </a>
            </li>
            <li class="nav-item">
              <a class="ajaxCallerLink <?php echo $linkClass; ?>" 
                href="" onclick="CustomRegistration.init(event);"><?php echo trans('registration'); ?>
              </a>
            </li>
            <li class="nav-item">
              <a class="ajaxCallerLink <?php echo $linkClass; ?>" 
                href="" onclick="LoginHandler.recoverPasswordModalOpen(event, '<?php echo trans('forgotten.password'); ?>');"><?php echo trans('forgotten.password'); ?>
              </a>
            </li>

    <?php endif; ?>
          </ul>
        </div>
      </div>
    </nav>
  </div>

</div>