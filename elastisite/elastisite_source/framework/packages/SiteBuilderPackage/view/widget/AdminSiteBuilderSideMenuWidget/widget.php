<div class="widgetWrapper-noPadding">
    <div class="widgetHeader widgetHeader-color">
        <div class="widgetHeader-titleText"><?php echo trans('site.builder.menu'); ?></div>
    </div>
    <div class="widgetWrapper-textContainer widgetWrapper-textContainer-bottomMargin">
        <!-- <div class="sideMenu-title">
            <?php echo trans('general.terms.and.conditions'); ?>
        </div> -->

        <?php 
        $activeStr = App::getContainer()->getUrl()->getParamChain() == 'admin/userAreaMenu' 
            ? ' sideMenu-active' : '';
        ?>
        <div class="sideMenu-item">
            <a class="ajaxCallerLink<?php echo $activeStr; ?>" href="<?php echo App::getContainer()->getUrl()->getHttpDomain(); ?>/admin/userAreaMenu"><?php echo trans('admin.user.area.menu'); ?></a>
        </div>

        <?php 
        $activeStr = App::getContainer()->getUrl()->getParamChain() == 'admin/builtPages' 
            ? ' sideMenu-active' : '';
        ?>
        <div class="sideMenu-item">
            <a class="ajaxCallerLink<?php echo $activeStr; ?>" href="<?php echo App::getContainer()->getUrl()->getHttpDomain(); ?>/admin/builtPages"><?php echo trans('admin.built.pages'); ?></a>
        </div>
    </div>
</div>