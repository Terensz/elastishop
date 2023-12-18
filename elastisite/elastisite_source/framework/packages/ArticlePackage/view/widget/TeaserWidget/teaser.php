<?php
if ($container->isGranted('viewProjectAdminContent')) {
    include('framework/packages/ArticlePackage/view/widget/TeaserWidget/articleEdit/articleToolBar.php');
}
?>
<div id="articleContainer_<?php echo $article->getId(); ?>">
<?php
if ($article->getTeaserType() == $article::TEASER_TYPE_HARD_CODED && $article->getHardCodedSlug()) {
?>
    <script>
    $.ajax({
        'type' : 'POST',
        'url' : 'hardCodedArticle/<?php echo $article->getHardCodedSlug(); ?>',
        'data': {'teaser': true, 'slug': '<?php echo $article->getSlug(); ?>'},
        'async': true,
        'success': function(response) {
            // console.log(response);
            ElastiTools.checkResponse(response);
            var params = <?php echo $widgetJsClass; ?>.getParameters();
            $('#articleContainer_<?php echo $article->getId(); ?>').html(response.view);
        },
        'error': function(request) {
            ElastiTools.checkResponse(request.responseText);
        },
    });
    </script>
<?php
} else {
?>
    <div class="article-wrapper">
<?php
    if ($article->getMainRoute()) {
        $route = $container->getRoutingHelper()->searchRoute($article->getMainRoute());
?>
        <div class="articleHome">
            <a class="ajaxCallerLink" href="<?php echo $container->getUrl()->getHttpDomain().'/'.$article->getMainRoute(); ?>"><?php echo trans($route->getTitle()); ?></a>
        </div>
<?php
    }
?>
        <div class="article-container">

            <div class="article-head">
                <div class="article-info"><?php echo $article->getCreatedAt(); ?></div>
                <div class="article-title"><?php echo $article->getTitle(); ?></div>
            </div>

            <div class="article-teaser"><?php
                    $teaser = $article->getTeaser();
                    $teaser = str_replace(array("\r\n", "\r", "\n"), "<br />", $teaser);
                    // echo nl2br_indent(trim($teaser));
                    echo trim(html_entity_decode($teaser));
            ?></div>
<?php
        if ($article->getBody() || $article->getHardCodedSlug()) {
?>
                <div class="articleFooter">
                    <a class="ajaxCallerLink" href="<?php echo $container->getUrl()->getHttpDomain().'/article/'.$article->getSlug(); ?>"><?php echo trans('read.more'); ?></a>
                </div>
<?php
        }
?>
        </div>
    </div>
<?php
}
?>
</div>
