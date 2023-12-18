<style>
.noPadding {
    /* padding-left: 0px; */
}

.article-title {
    text-align: left;
}

.articleCreatedAt {
    text-align: right;
}
</style>
<?php
include('framework/packages/ArticlePackage/view/widget/ArticleSearchWidget/searchForm.php');
// FileHandler::includeFile('framework/packages/ArticlePackage/view/TeaserWidget/scripts.php');

if ($submitted && $articles == array()) {
?>
<div class="article-wrapper">
    <?php echo trans('search.no.result'); ?>
</div>
<?php
}

if ($articles) {
    // dump($articles);exit;
    foreach ($articles as $article) {
        $widgetJsClass = 'ArticleSearchWidget';
        include('framework/packages/ArticlePackage/view/widget/TeaserWidget/teaser.php');
    }
}
?>
