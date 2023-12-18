<?php 
// dump($frameTexts); 
// dump($frameTexts['en'][$frameTextDef['code']]);
// $frameTextEn = $frameTexts['en'][$frameTextDef['code']];
?>

<div class="tagFrame-col" id="">
    <div class="tag-light">
        <div>
            <?php if ($frameTextDef['phraseLocation'] == 'database'): ?>
            <a onclick="ContentTexts.reset(event, '<?php echo $documentType; ?>_frame_hu_<?php echo $frameTextDef['code']; ?>');" href=""><?php echo trans('reset') ?></a>
            <?php endif; ?>
        </div>
        <table onclick="ContentTexts.edit(event, '<?php echo $documentType; ?>_frame_hu_<?php echo $frameTextDef['code']; ?>', false);" id="hu_<?php echo $frameTextDef['code']; ?>" style="width: 100%; cursor: pointer;">
            <tbody>
                <tr>
                    <td id="">
                        <b><?php echo trans($frameTextDef['translationFormat']); ?></b> (HU)
                    </td>
                </tr>
                <tr>
                    <td id="">
                        <?php echo $frameTextDef['lead']; ?>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <div class="rowSeparator-dark"></div>

        <div>
            <?php if ($frameTextEn['phraseLocation'] == 'database'): ?>
            <a onclick="ContentTexts.reset(event, '<?php echo $documentType; ?>_frame_en_<?php echo $frameTextDef['code']; ?>');" href=""><?php echo trans('reset') ?></a>
            <?php endif; ?>
        </div>
        <table onclick="ContentTexts.edit(event, '<?php echo $documentType; ?>_frame_en_<?php echo $frameTextDef['code']; ?>', false);" id="en_<?php echo $frameTextDef['code']; ?>" style="width: 100%; cursor: pointer;">
            <tbody>
                <tr>
                    <td id="">
                        <b><?php echo trans($frameTextDef['translationFormat'], [], 'en'); ?></b> (EN)
                    </td>
                </tr>
                <tr>
                    <td id="">
                        <?php if ($frameTextEn['lead'] && $frameTextEn['lead'] != ''): ?>
                            <?php echo $frameTextEn['lead']; ?>
                        <?php else: ?>
                            <div class="error"><?php echo trans('missing.text'); ?></div>
                        <?php endif; ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

