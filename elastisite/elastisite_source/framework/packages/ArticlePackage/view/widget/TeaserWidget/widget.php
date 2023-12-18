<style>
.noPadding {
    padding-left: 0px;
}
</style>

<?php
if ($container->isGranted('viewProjectAdminContent')) {
    include('framework/packages/ArticlePackage/view/widget/TeaserWidget/articleEdit/formScripts.php');
}

if ($articles) {
    $widgetJsClass = 'TeaserWidget';
    foreach ($articles as $article) {
        include('framework/packages/ArticlePackage/view/widget/TeaserWidget/teaser.php');
    }
}
?>
