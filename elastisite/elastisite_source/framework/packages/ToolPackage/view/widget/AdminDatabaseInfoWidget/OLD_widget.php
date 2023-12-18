<?php
// dump($alterSchemaQueries);
if ($schemaUpToDate) {
?>
    <img style="width: 60px;" src="<?php echo $container->getUrl()->getHttpDomain(); ?>/public_folder/icon/tick-icon.png">
<?php
    echo trans('database.schema.is.up.to.date');
} else {
?>
<form name="AdminDatabaseInfoWidget_form">
    <div class="row">
        <div class="col-4">
            <button id="tool_schemaUpdate_submit" name="tool_schemaUpdate_submit"
                class="btn btn-secondary btn-block"><?php echo trans('update.schema'); ?></button>
        </div>
    </div>
</form><br>

<div class="article-title">
    <?php echo trans('database.schema.updates'); ?>
</div>

<?php echo trans('database.update.existing.tables'); ?>
<?php echo $grid1; ?><br>

<?php echo trans('database.create.missing.tables'); ?>
<?php echo $grid2; ?>
<?php
}
?>

<script>
$('body').on('click', '#tool_schemaUpdate_submit', function(e) {
    e.preventDefault();
    AdminDatabaseInfoWidget.call(true);
});
</script>
