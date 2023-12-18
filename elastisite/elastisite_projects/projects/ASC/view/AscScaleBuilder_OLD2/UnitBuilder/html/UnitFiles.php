<div style="padding: 4px; padding-bottom: 6px;">
<?php foreach ($unitData['files'] as $unitFile): ?>
    <div style="float: left; width: 80px; margin: 4px; box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;">
        <img style="width: 80px;" src="/asc/unitImage/thumbnail/<?php echo $unitFile->getCode(); ?>">
    </div>
<?php endforeach; ?>
</div>
<div style="clear: both;"></div>