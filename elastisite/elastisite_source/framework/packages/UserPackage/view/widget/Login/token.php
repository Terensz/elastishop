<form id="LoginWidget_form" name="LoginWidget_form" action="" method="post" autocomplete="off">
                            <div class="row">
                                <div class="col-sm-12 noPadding">
                                    <div class="form-group">

                                        {{ csrfTokenInput }}

                                        <input id="LoginWidget_token" name="LoginWidget_token"
                                            class="form-control" style="width: 100%;"
                                            type="text" value="" placeholder="<?php echo trans('login.token'); ?>" />
                                        <!-- <a onClick="LoginWidget.logout(event)" href=""><?php echo trans('logout'); ?></a> -->
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12 noPadding">
                                    <div class="input-group">

                                    <button id="LoginWidget_submit" name="LoginWidget_submit"
                                        type="button" class="btn btn-secondary btn-block"
                                        onclick="LoginWidget.submit()"
                                        placeholder="Bejelentkezés"><?php echo trans('send'); ?>
                                    </button>

                                    </div>
                                </div>
                            </div>
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


                        <!-- <br>Nincs még felhasználóneved?<br>
                        <a href="">Regisztrálj itt</a> -->
