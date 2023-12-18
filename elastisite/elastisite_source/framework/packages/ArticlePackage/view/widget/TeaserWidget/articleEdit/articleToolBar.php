<div class="article-toolbar">
    <a href="" class="triggerModal" onClick="ArticleEdit.edit('<?php echo $article->getId(); ?>');"><?php echo trans('edit'); ?></a>
     |  <a href="" class="triggerModal" onClick="ArticleEdit.deleteRequest('<?php echo $article->getId(); ?>');"><?php echo trans('delete'); ?></a>

<?php if ($article->getPosition() > 0) { ?>
     |  <a href="" class="triggerModal" onClick="ArticleEdit.moveUp('<?php echo $article->getId(); ?>');"><?php echo trans('move.up'); ?></a>
<?php } ?>
</div>
