<?php  
// dump($ascUnitFiles);
?>

<?php if (count($ascUnitFiles) == 0): ?>
    <?php echo trans('no.uploaded.files.yet'); ?>
<?php endif; ?>
<?php foreach ($ascUnitFiles as $ascUnitFile): ?>
    <img class="webshopThumbnail" style="max-height: 88px;" src="/asc/unitImage/thumbnail/<?php echo $ascUnitFile->getFileName(); ?>">
<?php endforeach; ?>