<ul class="pc-navbar">
                
    <li class="pc-item pc-caption">
        <label><?php echo trans('login'); ?></label>
    </li>

    <?php 
    // dump($message);
    ?>

    <li>
        <div class="m-3">
            <form id="Login2Widget_form" name="Login2Widget_form" action="" method="post" autocomplete="off">
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
                    <button class="btn btn-primary" name="LoginWidget_submit" id="LoginWidget_submit" type="button" onclick="Login2Widget.submit(event);" value=""><?php echo trans('login'); ?></button>
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
        ?>
            <!-- <div class="sideMenu-item">
                <a class="" onclick="LoginHandler.recoverPasswordModalOpen(event, '<?php echo trans('forgotten.password'); ?>');" href=""><?php echo trans('i.have.forgotten.my.password'); ?></a>
            </div> -->
        </div>
    </li>

</ul>