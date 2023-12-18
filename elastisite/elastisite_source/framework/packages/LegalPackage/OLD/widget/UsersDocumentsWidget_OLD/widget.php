<div class="flex-content-sidebar" id="AscScaleBuilder_PrimarySubjectBar_container" style="width: 380px; height: 100% !important; z-index: 0 !important;">
    <div class="navbar-wrapper" style="width: 100%; height: 100% !important;">
        <div class="navbar-contentss ps">
            <div id="viewSection-ShipmentHelper">
                <div class="card mb-3 card-noBorderRadius">
                    <div class="card-footer">
                        <div class="col-md-12 sidebar-text-container d-flex align-items-center">
                            <h5 class="mb-2">Segítségnyújtás a megrendeléshez</h5>
                        </div>
                    </div>
                    <div class="card-footer">
                        <!-- <div class="m-0">
                            <a href="" class="sidebar-link">Alma</a>
                        </div> -->

<div class="widgetWrapper-noPadding" style="position: relative; z-index: 3;">
    <div class="widgetHeader widgetHeader-color">
        <div class="widgetHeader-titleText"><?php echo trans('users.documents'); ?></div>
    </div>
    <div class="widgetWrapper-textContainer widgetWrapper-textContainer-bottomMargin">
<?php if (App::getContainer()->isGranted('viewProjectAdminContent') && App::getContainer()->getSession()->get('site_adminViewState')): ?>
    <a class="ajaxCallerLink" href="<?php echo App::getContainer()->getUrl()->getHttpDomain(); ?>/admin/articleContentTexts">
        <div class="sideMenu-highlightedItem">
            <?php echo trans('admin.articles'); ?>
        </div>
    </a>
<?php endif; ?>
<?php 
    // dump(App::getContainer()->getRouting()->getPageRoute()->getName());exit;
$activeStr = App::getContainer()->getRouting()->getPageRoute()->getName() == 'documents_terms-of-use' 
    ? ' sideMenu-active' : '';
?>
    <!-- <div class="sideMenu-item">
        <a class="ajaxCallerLink<?php echo $activeStr; ?>" href="<?php echo App::getContainer()->getUrl()->getHttpDomain(); ?>/documents/terms-of-use"><?php echo trans('terms.of.use'); ?></a>
    </div> -->
<?php 
$activeStr = App::getContainer()->getRouting()->getPageRoute()->getName() == 'documents_what-is-gdpr' 
    ? ' sideMenu-active' : '';
?>
        <div class="sideMenu-item">
            <a class="ajaxCallerLink<?php echo $activeStr; ?>" href="<?php echo App::getContainer()->getUrl()->getHttpDomain(); ?>/documents/what-is-gdpr"><?php echo trans('what.is.gdpr'); ?></a>
        </div>
<?php 
$activeStr = App::getContainer()->getRouting()->getPageRoute()->getName() == 'documents_how-do-we-protect-personal-data' 
    ? ' sideMenu-active' : '';
?>
        <div class="sideMenu-item">
            <a class="ajaxCallerLink<?php echo $activeStr; ?>" href="<?php echo App::getContainer()->getUrl()->getHttpDomain(); ?>/documents/how-do-we-protect-personal-data"><?php echo trans('how.do.we.protect.personal.data'); ?></a>
        </div>
<?php 
$activeStr = App::getContainer()->getRouting()->getPageRoute()->getName() == 'documents_privacy-statement' 
    ? ' sideMenu-active' : '';
?>
        <div class="sideMenu-item">
            <a class="ajaxCallerLink<?php echo $activeStr; ?>" href="<?php echo App::getContainer()->getUrl()->getHttpDomain(); ?>/documents/privacy-statement"><?php echo trans('privacy.statement'); ?></a>
        </div>
<?php 
$activeStr = App::getContainer()->getRouting()->getPageRoute()->getName() == 'documents_cookie-info' 
    ? ' sideMenu-active' : '';
?>
        <div class="sideMenu-item">
            <a class="ajaxCallerLink<?php echo $activeStr; ?>" href="<?php echo App::getContainer()->getUrl()->getHttpDomain(); ?>/documents/cookie-info"><?php echo trans('cookie.handling.information'); ?></a>
        </div>
    </div>
</div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>