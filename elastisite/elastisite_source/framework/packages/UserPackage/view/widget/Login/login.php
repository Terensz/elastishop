            <ul class="pc-navbar">
                
                <li class="pc-item pc-caption">
                    <label><?php echo trans('login'); ?></label>
                </li>

                <li>
                    <div class="m-3">
                        <form id="LoginWidget_form" name="LoginWidget_form" action="" method="post" autocomplete="off">
                            <div class="mb-3">
                                {{ csrfTokenInput }}
                                <label for="LoginWidget_username" class="form-label"><?php echo trans('username'); ?></label>
                                <div class="input-group has-validation">

                                    <input type="text" class="form-control inputField" name="LoginWidget_username" id="LoginWidget_username" maxlength="250" placeholder="" value="">

                                    <div class="invalid-feedback validationMessage" id="LoginWidget_username-validationMessage"></div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="LoginWidget_password" class="form-label"><?php echo trans('password'); ?></label>
                                <div class="input-group has-validation">
                                    <input type="password" class="form-control inputField" name="LoginWidget_password" id="LoginWidget_password" maxlength="250" placeholder="" value="">
                                    <div class="invalid-feedback validationMessage" id="LoginWidget_password-validationMessage"></div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <button class="btn btn-primary" name="LoginWidget_submit" id="LoginWidget_submit" type="button" onclick="Login.submit();" value=""><?php echo trans('login'); ?></button>
                            </div>
                        </form>
                    </div>
                </li>

                <li>
                    <div class="m-3">
                    <?php
                    // dump('alma232');
                    // if (isset($message)) {
                    //     dump($message);
                    // }
                    if (isset($message) && $message != ''):
                    ?>
                        <div class="<?php echo $message['level']; ?>">
                            <?php echo trans($message['text']); ?>
                        </div>
                    <?php
                    endif;
                    if ($displayRegLink && App::getContainer()->getRouting()->getPageRoute()->getName() != 'user_registration' && App::getContainer()->getRoutingHelper()->routeExists('user_registration')):
                    ?>
                        <div class="sideMenu-item">
                            <a class="" href="<?php echo $container->getRoutingHelper()->getLink('user_registration'); ?>"><?php echo trans('i.dont.have.an.account'); ?></a>
                        </div>
                    <?php
                    endif;
                    ?>
                        <div class="sideMenu-item">
                            <a class="" onclick="LoginHandler.recoverPasswordModalOpen(event, '<?php echo trans('forgotten.password'); ?>');" href=""><?php echo trans('i.have.forgotten.my.password'); ?></a>
                        </div>
                    </div>
                </li>

            </ul>

            <!-- <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
                <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;">
                </div>
            </div>
            <div class="ps__rail-y" style="top: 0px; right: 0px;">
                <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;">
                </div>
            </div> -->

<!-- <div class="widgetWrapper">
    <form id="LoginWidget_form" name="LoginWidget_form" action="" method="post" autocomplete="off">
        <div class="row">
            <div class="col-sm-12 noPadding">
                <div class="form-group">

                    {{ csrfTokenInput }}

                    <input id="LoginWidget_username" name="LoginWidget_username"
                        class="inputField form-control" style="width: 100%;"
                        type="text" value="" placeholder="<?php echo trans('username'); ?>" />

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 noPadding">
                <div class="form-group">

                    <input id="LoginWidget_password" name="LoginWidget_password"
                        class="inputField form-control" style="width: 100%;"
                        type="password" value="" placeholder="<?php echo trans('password'); ?>" />

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 noPadding">
                <div class="input-group">

                <button id="LoginWidget_submit" name="LoginWidget_submit"
                    type="button" class="btn btn-secondary btn-block"
                    onclick="Login.submit()"
                    placeholder="Bejelentkezés">BejelentkezésALMA
                </button>

                </div>
            </div>
        </div>
    </form>

<?php
if (isset($message) && $message != ''):
?>
    <div style="padding-top: 10px;" class="<?php echo $message['level']; ?>">
        <?php echo trans($message['text']); ?>
    </div>
<?php
endif;
if ($displayRegLink && App::getContainer()->getRouting()->getPageRoute()->getName() != 'user_registration' && App::getContainer()->getRoutingHelper()->routeExists('user_registration')):
?>
    <div class="sideMenu-item">
        <a class="" href="<?php echo $container->getRoutingHelper()->getLink('user_registration'); ?>"><?php echo trans('i.dont.have.an.account'); ?></a>
    </div>
<?php
endif;
?>
    <div class="sideMenu-item">
        <a class="" onclick="LoginHandler.recoverPasswordModalOpen(event, '<?php echo trans('forgotten.password'); ?>');" href=""><?php echo trans('i.have.forgotten.my.password'); ?></a>
    </div>

</div> -->