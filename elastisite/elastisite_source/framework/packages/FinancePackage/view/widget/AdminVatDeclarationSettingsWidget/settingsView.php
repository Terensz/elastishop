<?php 
$colUnit = $settings['allowModify'] ? '4' : '6';
?>
<div class="grid-container" id="AdminWebshopConfig-list" onclick="AdminWebshopConfig.edit(event, false);">
    <form name="AdminWebshopConfig_form" id="AdminWebshopConfig_form" method="get" action="">
        <input type="hidden" autocomplete="false">
        <div class="row grid-title-row breakLongText">
            <div class="col-<?php echo $colUnit; ?> grid-title-cell grid-title-cell-background">
                Megnevezés
            </div>
            <div class="col-<?php echo $colUnit; ?> grid-title-cell grid-title-cell-background">
                Érték
            </div>
            <?php if ($settings['allowModify']): ?>
            <div class="col-4 grid-title-cell grid-title-cell-background">
                Módosítható
            </div>
            <?php endif; ?>
        </div>
    </form>

<?php foreach ($settings['settingArray'] as $setting): ?>
<?php 
$fieldStyleStr = $setting['modifiable'] == 'no' && $settings['allowModify'] ? ' style="background-color: #a6c3d3;"' : '';
?>
    <div class="row grid-body-row breakLongText" id="">
        <div data-id="vatDeclarationSetting_<?php echo $setting['property'] ?>" data-status="" class="col-<?php echo $colUnit; ?> grid-body-cell"<?php echo $fieldStyleStr; ?>>
            <?php echo $setting['title']; ?>
        </div>
        <div data-id="" data-status="" class="col-<?php echo $colUnit; ?> grid-body-cell"<?php echo $fieldStyleStr; ?>>
            <?php echo $setting['value']; ?>
        </div>
        <?php if ($settings['allowModify']): ?>
        <div data-id="" data-status="" class="col-4 grid-body-cell"<?php echo $fieldStyleStr; ?>>
            <?php echo trans($setting['modifiable']); ?>
        </div>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
</div>