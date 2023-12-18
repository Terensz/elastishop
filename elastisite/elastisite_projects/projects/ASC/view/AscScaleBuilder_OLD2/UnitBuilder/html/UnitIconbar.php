<?php
if (!isset($data['iconset']) && isset($data['iconsetConfig'])) {
    $data['iconset'] = array_keys($data['iconsetConfig']);
}
$subjectCategoryStr = '';
if (isset($data['subjectCategory'])) {
    $subjectCategoryStr = '<span class="UnitBuilder-UnitIconbar-label-subjectCategory">('.$data['subjectCategory'].')</span> ';
}
?>
    <div class="UnitBuilder-UnitIconbar-iconbar AdminScaleBuilder-iconbar-size UnitBuilder-UnitIconbar-colors-<?php if (isset($data['unitType']) && is_string($data['unitType'])) : echo $data['unitType']; else : echo 'adminScale'; endif; ?>">
        <div class="UnitBuilder-UnitIconbar-left-iconContainer">
        <?php if (isset($data['iconset']) && in_array('Edit', $data['iconset'])): ?>
<?php
$editOnClickStr = '';
if (isset($data['iconsetConfig']['Edit']['onClickFunction'])) {
    $editOnClickStr = 'onclick="'.$data['iconsetConfig']['Edit']['onClickFunction'].'" ';
}
?>
            <div <?php echo $editOnClickStr; ?>class="UnitBuilder-UnitIconbar-icon UnitBuilder-UnitIconbar-left-icon">
                <img class="AdminScaleBuilder-icon-image AdminScaleBuilder-icon-size" src="/image/icon_edit.png">
            </div>
            <?php endif; ?>
            <?php if (isset($data['iconset']) && in_array('CreateFrame', $data['iconset'])): ?>
<?php
$createFrameOnClickStr = '';
if (isset($data['iconsetConfig']['CreateFrame']['onClickFunction'])) {
    $createFrameOnClickStr = 'onclick="'.$data['iconsetConfig']['CreateFrame']['onClickFunction'].'" ';
}
?>
            <div <?php echo $createFrameOnClickStr; ?>class="UnitBuilder-UnitIconbar-icon UnitBuilder-UnitIconbar-left-icon">
                <img class="AdminScaleBuilder-icon-image AdminScaleBuilder-icon-size" src="/image/icon_createFrame.png">
            </div>
            <?php endif; ?>
        </div>

<?php  
// dump($unit);
$debug = '';
// dump($dragDebug);
$dragDebug = '';
// $debug = isset($ascUnitId) ? '('.$ascUnitId.') ' : '';
$subjectStr = isset($data['subject']) && $data['subject'] != null && $data['subject'] != '' ? ' <span class="UnitBuilder-subjectSpan-'.(isset($unitType) ? $unitType : 'primary').'">['.trans(strtolower($data['subject'])).']</span> ' : '';
?>
        <!-- Label container -->
        <div class="UnitBuilder-UnitIconbar-labelContainer">
            <?php echo (isset($dragDebug) ? $dragDebug : '') . $debug; ?><?php echo $subjectStr; ?><?php echo $subjectCategoryStr.$data['title']; ?>
        </div>

        <div class="UnitBuilder-UnitIconbar-right-iconContainer">
        <?php if (isset($data['iconset']) && in_array('RemoveFrame', $data['iconset'])): ?>
<?php
$removeFrameOnClickStr = '';
if (isset($data['iconsetConfig']['RemoveFrame']['onClickFunction'])) {
    $removeFrameOnClickStr = 'onclick="'.$data['iconsetConfig']['RemoveFrame']['onClickFunction'].'" ';
}
?>
            <div <?php echo $removeFrameOnClickStr; ?>class="UnitBuilder-UnitIconbar-icon UnitBuilder-UnitIconbar-right-icon">
                <img class="AdminScaleBuilder-icon-image AdminScaleBuilder-icon-size" src="/image/icon_removeFrame.png">
            </div>
        <?php endif; ?>
        <?php if (isset($data['iconset']) && in_array('Delete', $data['iconset'])): ?>
<?php
$deleteOnClickStr = '';
if (isset($data['iconsetConfig']['Delete']['onClickFunction']) && $data['iconsetConfig']['Delete']['onClickFunction']) {
    $deleteOnClickStr = 'onclick="'.$data['iconsetConfig']['Delete']['onClickFunction'].'" ';
}
?>
            <div <?php echo $deleteOnClickStr; ?>class="UnitBuilder-UnitIconbar-icon UnitBuilder-UnitIconbar-right-icon">
                <img class="AdminScaleBuilder-icon-image AdminScaleBuilder-icon-size" src="/image/icon_delete.png">
            </div>
        <?php endif; ?>
        </div>
    </div>
