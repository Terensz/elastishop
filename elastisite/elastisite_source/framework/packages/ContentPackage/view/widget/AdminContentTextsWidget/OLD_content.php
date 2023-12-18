<?php 
// dump($frameTextDef); 
// dump($frameTexts['en'][$frameTextDef['code']]);
// $contentTextEn = $contentTexts['en'][$contentTextDef['code']];
?>
<div class="tagFrame-col" id="">
    <div class="tag-light">
        <div>
            <?php if ($contentTextDef['phraseLocation'] == 'database'): ?>
            <a onclick="ContentTexts.reset(event, '<?php echo $documentType; ?>_content_hu_<?php echo $contentTextDef['code']; ?>');" href=""><?php echo trans('reset') ?></a>
            <?php endif; ?>
        </div>
        <table onclick="ContentTexts.edit(event, '<?php echo $documentType; ?>_content_hu_<?php echo $contentTextDef['code']; ?>', false);" id="hu_<?php echo $contentTextDef['code']; ?>" style="width: 100%; cursor: pointer;">
            <tbody>
                <tr>
                    <td id="">
                        <b><?php echo trans($contentTextDef['translationFormat']); ?></b> (HU)
                    </td>
                </tr>
                <tr>
                    <td id="">
                        <?php echo $contentTextDef['lead']; ?>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="rowSeparator-dark"></div>

        <div>
            <?php if ($contentTextEn['phraseLocation'] == 'database'): ?>
            <a onclick="ContentTexts.reset(event, '<?php echo $documentType; ?>_content_en_<?php echo $contentTextDef['code']; ?>');" href=""><?php echo trans('reset') ?></a>
            <?php endif; ?>
        </div>
        <table onclick="ContentTexts.edit(event, '<?php echo $documentType; ?>_content_en_<?php echo $contentTextDef['code']; ?>', false);" id="en_<?php echo $contentTextDef['code']; ?>" style="width: 100%; cursor: pointer;">
            <tbody>
                <tr>
                    <td id="">
                        <b><?php echo trans($contentTextDef['translationFormat'], [], 'en'); ?></b> (EN)
                    </td>
                </tr>
                <tr>
                    <td id="">
                        <?php if ($contentTextEn['lead'] && $contentTextEn['lead'] != ''): ?>
                            <?php echo $contentTextEn['lead']; ?>
                        <?php else: ?>
                            <div class="error"><?php echo trans('missing.text'); ?></div>
                        <?php endif; ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

