<div class="AAW-article-container">
<?php foreach ($article->getArticleParagraph() as $articleParagraph): ?>
    <div id="AAW_viewer_articleParagraph_<?php echo $articleParagraph->getId(); ?>" class="AAW-articleParagraph-container">
    <?php if (App::getContainer()->getSession()->get('site_adminViewState')): ?>
        <?php include('toolbarOfParagraph.php'); ?>
    <?php endif; ?>
    </div>
<?php endforeach; ?>
</div>
<?php dump($article); ?>

<style>
    .AAW-article-container {
        border: 1px solid #f1f1f1;
        padding: 6px;
        margin-top: 6px;
    }
    .AAW-articleParagraph-container {
        border: 1px solid #f1f1f1;
        padding: 6px;
        margin-top: 6px;
    }
</style>