<div class="widgetWrapper">
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
                    placeholder="Bejelentkezés">Bejelentkezés
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

</div>