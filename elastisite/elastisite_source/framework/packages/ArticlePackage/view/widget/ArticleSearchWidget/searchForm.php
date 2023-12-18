<div class="article-wrapper">
    <div class="article-title"><?php echo trans('search.article'); ?></div>
    <form id="ArticlePackage_articleSearch_form" name="ArticlePackage_articleSearch_form" action="" method="post" autocomplete="off">
                                <div style="padding: 0px !important;" class="row noPadding">
                                    <div style="padding: 0px !important;" class="col-lg-8 noPadding">
                                        <div class="form-group noPadding">

                                            {{ csrfTokenInput }}

                                            <input id="ArticlePackage_articleSearch_mixed" name="ArticlePackage_articleSearch_mixed"
                                                class="form-control noPadding inputField"
                                                type="text" value="" style="padding: 0px !important;" placeholder="" />

                                        </div>
                                    </div>

                                    <div style="padding: 0px !important;" class="col-lg-4 noPadding">
                                        <div class="input-group">

                                        <button id="ArticlePackage_articleSearch_submit" name="ArticlePackage_articleSearch_submit"
                                            type="button" class="btn btn-outline-secondary btn-block"
                                            onclick="ArticleSearchWidget.submit()"
                                            placeholder=""><?php echo trans('search'); ?>
                                        </button>

                                        </div>
                                    </div>
                                </div>

                                <?php
                                if (isset($message) && $message != '') {
                                ?>
                                <div class="row messageContainer">
                                    <div class="col-sm-12 noPadding">
                                        <div class="form-group">
                                            <div class="<?php echo $message['level']; ?>"><?php echo trans($message['text']); ?></div>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                }
                                ?>

                            </form>
</div>
