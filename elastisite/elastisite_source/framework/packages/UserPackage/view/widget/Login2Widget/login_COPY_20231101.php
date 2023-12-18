<div class="widgetWrapper">
    <form id="Login2Widget_form" name="Login2Widget_form" action="" method="post" autocomplete="off">
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
                    onclick="Login2Widget.submit(event);"
                    placeholder="Bejelentkezés">Bejelentkezés
                </button>

                </div>
            </div>
        </div>

        <?php 
        // dump($fbsUsers);
        ?>
    </form>

<?php
if (isset($message) && $message != '') {
?>
    <div style="padding-top: 10px;" class="<?php echo $message['level']; ?>">
        <?php echo trans($message['text']); ?>
    </div>
<?php
}
?>

</div>