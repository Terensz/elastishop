<div class="row sheetLevel">
    <div class="col-sm-12 widgetRail widgetRail-noPadding">
        <div class="widgetWrapper-off">
            <div class="widgetContainer" id="widgetContainer-EasyCarousel"></div>
        </div>
    </div>
</div>

<div class="row sheetLevel">
    <div class="col-sm-3 widgetRail widgetRail-first">
        <div class="widgetWrapper">
            <div class="widgetContainer" id="widgetContainer-LoginWidget"></div>
        </div>
        <div class="widgetWrapper">
            <div class="widgetContainer" id="widgetContainer-TeaserPanel"></div>
        </div>
    </div>
    <div class="col-sm-9 widgetRail widgetRail-last">
        <div class="widgetWrapper-off">
            <div class="widgetContainer" id="widgetContainer-2">
                env: <?php echo $container->getEnv(); ?>
                <br>
                addr: <?php echo '"'.$_SERVER['REMOTE_ADDR'].'"'; ?>
                <br>
                <br>
                <?php

                foreach ($users as $user) {
                    echo $user->getName().'<br>';
                    echo $user->getUsername().'<br>';
                    echo $user->getPassword().'<br>';
                    echo 'md5alma: '.md5('alma');
                }

                ?>
            </div>
        </div>
    </div>
</div>

<div class="row sheetLevel">
    <div class="col-sm-12 widgetRail widgetRail-noPadding">
        <div class="widgetContainer" id="widgetContainer-Footer" class="widgetWrapper-off"></div>
    </div>
</div>
