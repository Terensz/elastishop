<?php 
if (!isset($data['iconset']) && isset($data['iconsetConfig'])) {
    $data['iconset'] = array_keys($data['iconsetConfig']);
}
$subjectCategoryStr = '';
if (isset($data['subjectCategory'])) {
    $subjectCategoryStr = '<span class="ascScaleLister-iconbar-label-subjectCategory">('.$data['subjectCategory'].')</span> ';
}
?>
<div class="ascScaleLister-iconbar ascScaleLister-iconbar-adminScale">
    <div class="ascScaleLister-leftIconbar-iconContainer">
        <?php if (isset($data['iconset']) && in_array('Edit', $data['iconset'])): ?>
<?php 
// $editOnClickStr = '';
// if (isset($data['iconsetConfig']['Edit']['onClickFunction'])) {
//     $editOnClickStr = 'onclick="'.$data['iconsetConfig']['Edit']['onClickFunction'].'" ';
// }
?>
        <div href="/asc/scaleBuilder/scale/<?php echo $data['id']; ?>">
            <div class="ajaxCaller ascScaleLister-iconbar-icon ascScaleLister-iconbar-icon-left">
                <img class="icon-32px" src="/image/icon_edit.png">
            </div>
        </div>
        <?php endif; ?>
        <?php if (isset($data['iconset']) && in_array('CreateFrame', $data['iconset'])): ?>
<?php 
$createFrameOnClickStr = '';
if (isset($data['iconsetConfig']['CreateFrame']['onClickFunction'])) {
    $createFrameOnClickStr = 'onclick="'.$data['iconsetConfig']['CreateFrame']['onClickFunction'].'" ';
}
?>
        <div <?php echo $createFrameOnClickStr; ?>class="ascScaleLister-iconbar-icon ascScaleLister-iconbar-icon-left">
            <img class="icon-32px" src="/image/icon_createFrame.png">
        </div>
        <?php endif; ?>
    </div>
    <div class="ascScaleLister-iconbar-labelContainer">
        <?php echo $subjectCategoryStr.$data['title']; ?>
    </div>
    <div class="ascScaleLister-rightIconbar-iconContainer">
        <?php if (isset($data['iconset']) && in_array('RemoveFrame', $data['iconset'])): ?>
<?php 
$removeFrameOnClickStr = '';
if (isset($data['iconsetConfig']['RemoveFrame']['onClickFunction'])) {
    $removeFrameOnClickStr = 'onclick="'.$data['iconsetConfig']['RemoveFrame']['onClickFunction'].'" ';
}
?>
        <div <?php echo $removeFrameOnClickStr; ?>class="ascScaleLister-iconbar-icon ascScaleLister-iconbar-icon-right">
            <img class="icon-32px" src="/image/icon_removeFrame.png">
        </div>
        <?php endif; ?>
        <?php if (isset($data['iconset']) && in_array('Delete', $data['iconset'])): ?>
<?php 
$deleteOnClickStr = '';
if (isset($data['iconsetConfig']['Delete']['onClickFunction']) && $data['iconsetConfig']['Delete']['onClickFunction']) {
    $deleteOnClickStr = 'onclick="'.$data['iconsetConfig']['Delete']['onClickFunction'].'" ';
}
?>
        <div <?php echo $deleteOnClickStr; ?>class="ascScaleLister-iconbar-icon ascScaleLister-iconbar-icon-right">
            <img class="icon-32px" src="/image/icon_delete.png">
        </div>
        <?php endif; ?>
    </div>
    <div class="ascScaleLister-iconbar-clear"></div>
</div>