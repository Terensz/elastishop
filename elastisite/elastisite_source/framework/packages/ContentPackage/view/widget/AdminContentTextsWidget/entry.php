<?php 
$localeCounter = 0;
?>
<div class="tagFrame-col" id="">
    <div class="tag-light">
        <div><h4><?php echo $textDef['packagePublicName']; ?>: <?php echo $textDef['title']; ?></h4></div>

<?php if(in_array('hu', App::getContainer()->getConfig()->getSupportedLocales())): ?>
        <div>
            <b><?php echo trans('hungary.language.ucfirst'); ?></b> | <a onclick="ContentTexts.edit(event, '<?php echo $documentType; ?>-<?php echo $documentPart; ?>-hu-<?php echo $textDef['code']; ?>');" href=""><?php echo trans('edit') ?></a>
            <?php if ($textDef['phraseLocation'] == 'database'): ?>
            | <a onclick="ContentTexts.resetRequest(event, '<?php echo $documentType; ?>-<?php echo $documentPart; ?>-hu-<?php echo $textDef['code']; ?>');" href=""><?php echo trans('reset') ?></a>
            <?php endif; ?>
        </div>
        <table onclick="ContentTexts.edit(event, '<?php echo $documentType; ?>-<?php echo $documentPart; ?>-hu-<?php echo $textDef['code']; ?>', false);" id="hu_<?php echo $textDef['code']; ?>" style="width: 100%; cursor: pointer;">
            <tbody>
                <tr>
                    <td class="link-table-cell">
                        <?php echo $textDef['lead']; ?>
                    </td>
                </tr>
            </tbody>
        </table>
<?php endif; ?>
<?php 
// dump(App::getContainer()->getConfig()->getSupportedLocales());
?>

<?php if($localeCounter == 1): ?>
        <div class="rowSeparator-dark"></div>
<?php endif; ?>
<?php if(in_array('en', App::getContainer()->getConfig()->getSupportedLocales())): ?>
        <div>
            <b><?php echo trans('great.britain.language.ucfirst'); ?></b> | <a onclick="ContentTexts.edit(event, '<?php echo $documentType; ?>-<?php echo $documentPart; ?>-en-<?php echo $textDef['code']; ?>');" href=""><?php echo trans('edit') ?></a>
            <?php if ($textEn['phraseLocation'] == 'database'): ?>
            | <a onclick="ContentTexts.resetRequest(event, '<?php echo $documentType; ?>-<?php echo $documentPart; ?>-en-<?php echo $textDef['code']; ?>');" href=""><?php echo trans('reset') ?></a>
            <?php endif; ?>
        </div>
        <table onclick="ContentTexts.edit(event, '<?php echo $documentType; ?>-<?php echo $documentPart; ?>-en-<?php echo $textDef['code']; ?>', false);" id="en-<?php echo $textDef['code']; ?>" style="width: 100%; cursor: pointer;">
            <tbody>
                <tr>
                    <td class="link-table-cell">
                        <?php if ($textEn['lead'] && $textEn['lead'] != ''): ?>
                            <?php echo $textEn['lead']; ?>
                        <?php else: ?>
                            <div class="error"><?php echo trans('missing.text'); ?></div>
                        <?php endif; ?>
                    </td>
                </tr>
            </tbody>
        </table>
<?php endif; ?>

    </div>
</div>

