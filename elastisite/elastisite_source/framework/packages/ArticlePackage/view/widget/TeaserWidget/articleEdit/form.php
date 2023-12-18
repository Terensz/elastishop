<div class="articleEditForm">
    <style>
    .nicEdit-main {
        border: 0px;
        padding: 0px;
        margin: 0px;
        outline:none;
        user-select: all;
        line-height: normal;
        /* column-count: 2; */
    }
    </style>
    <script src="/public_folder/asset/TextareaEditor/TextareaEditor.js"></script>
    <script src="/public_folder/plugin/nicEdit/nicEdit.js"></script>
    <div class="widgetWrapper">
        <form name="ArticlePackage_article_form" id="ArticlePackage_article_form" method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="ArticlePackage_article_title"><?php echo trans('title'); ?></label>
                <input name="ArticlePackage_article_title" id="ArticlePackage_article_title" type="text"
                    class="inputField form-control" value="<?php echo $form->getValueCollector()->getDisplayed('title'); ?>" aria-describedby="" placeholder="">
            </div>
        <?php
        if ($hardCodedArticles != array()) {
            $selectedNormalStr = $form->getValueCollector()->getDisplayed('teaserType') == $article::TEASER_TYPE_NORMAL ? ' selected' : '';
            $selectedHardCodedStr = $form->getValueCollector()->getDisplayed('teaserType') == $article::TEASER_TYPE_HARD_CODED ? ' selected' : '';
        ?>
            <!-- <div class="form-group">
                <label for="ArticlePackage_article_teaserType"><?php echo trans('teaser.type'); ?></label>
                <select name="ArticlePackage_article_teaserType" id="ArticlePackage_article_teaserType" class="inputField form-control">
                    <option value="<?php echo $article::TEASER_TYPE_NORMAL; ?>"<?php echo $selectedNormalStr; ?>><?php echo trans('normal.teaser'); ?></option>
                    <option value="<?php echo $article::TEASER_TYPE_HARD_CODED; ?>"<?php echo $selectedHardCodedStr; ?>><?php echo trans('hard.coded.teaser'); ?></option>
                </select>
            </div> -->
            <?php
        }
        ?>
            <div class="form-group">
              <label for="ArticlePackage_article_teaser"><?php echo trans('teaser'); ?></label>
              <small id="ArticlePackage_article_teaserHelp" class="form-text text-muted"><?php echo trans('teaser.displayed.in.the.list'); ?></small>
              <div class="textarea-container">
                <textarea style="display: none;" name="ArticlePackage_article_teaser" id="ArticlePackage_article_teaser"
                    class="inputField form-control" rows="3"><?php echo $form->getValueCollector()->getDisplayed('teaser'); ?></textarea>
              </div>
            </div>

        <?php
        if ($hardCodedArticles != array()) {
        ?>
    <!--        <div class="form-group">
              <label for="ArticlePackage_article_hardCodedSlug"><?php echo trans('add.hard.coded.article.slug'); ?></label>
              <select name="ArticlePackage_article_hardCodedSlug" id="ArticlePackage_article_hardCodedSlug" class="inputField form-control">
                  <option value="*null*"><?php echo trans('not.selected'); ?></option>
            <?php
                foreach ($hardCodedArticles as $hardCodedArticle) {
                    $hardCodedSlug = str_replace('hardCodedArticle_', '', $hardCodedArticle->getName());
                    $selectedStr = ($form->getValueCollector()->getDisplayed('hardCodedSlug') == $hardCodedSlug) ? ' selected' : '';
            ?>
                <option value="<?php echo $hardCodedSlug; ?>"<?php echo $selectedStr; ?>><?php echo $hardCodedArticle->getTitle(); ?></option>
            <?php
                }
            ?>
              </select>
          </div>-->
        <?php
        }
        ?>
            <div class="form-group">
              <label for="ArticlePackage_article_body"><?php echo trans('article.body'); ?></label>
                <div class="textarea-container">
                  <textarea name="ArticlePackage_article_body" id="ArticlePackage_article_body"
                    class="inputField form-control" rows="3"><?php echo $form->getValueCollector()->getDisplayed('body'); ?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <button id="ArticlePackage_article_submit"
                        class="btn btn-secondary btn-block editSaveButton" style="width: 200px;" type="button"
                        onclick="ArticleEditPanel.save(<?php echo $articleId; ?>);"><?php echo trans('article.save.changes'); ?></button>

                </div>
                <div class="col-lg-4">
                    <button id="ArticlePackage_article_submit"
                        class="btn btn-outline-secondary btn-block editSaveButton" style="width: 200px;" type="button"
                        onclick="ArticleEdit.refresh();"><?php echo trans('cancel'); ?></button>

                </div>
            </div>
        </form>
    </div>
    <script>

    var ArticleEditPanel = {
        copyTeaserToTextarea: function() {
            var content = nicEditors.findEditor("ArticlePackage_article_teaser").getContent();
            $('#ArticlePackage_article_teaser').html(content);
        },
        copyBodyToTextarea: function() {
            var content = nicEditors.findEditor("ArticlePackage_article_body").getContent();
            $('#ArticlePackage_article_body').html(content);
        },
        save: function(articleId) {
            ArticleEditPanel.copyTeaserToTextarea();
            ArticleEditPanel.copyBodyToTextarea();
            ArticleEdit.save(articleId);
        }
    };

    $(document).ready(function() {
        $('textarea').keypress(function(e) {
            if (e.which == 13) {
                e.stopPropagation();
            }
        });

        $('body').on('click', '.contentImage', function(e) {
            let selection = window.getSelection();
            selection.removeAllRanges();
            let range = document.createRange();
            range.selectNodeContents(e.currentTarget);
            selection.addRange(range);
        });

        var nicArticleTeaser = new nicEditor({fullPanel : true}).panelInstance('ArticlePackage_article_teaser', {hasPanel : true});

        var nicArticleBody = new nicEditor({fullPanel : true}).panelInstance('ArticlePackage_article_body', {hasPanel : true});

    });
    </script>
</div>
