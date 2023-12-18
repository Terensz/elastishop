<div class="article-head">
    <div class="article-title"><?php echo trans('data.handling.title'); ?></div>
</div>
<div class="article-teaser"><?php echo nl2br(trans('data.handling.body', [array('from' => '[companyName]', 'to' => '<i>'.$container->getCompanyData('name').'</i>')])); ?></div>
