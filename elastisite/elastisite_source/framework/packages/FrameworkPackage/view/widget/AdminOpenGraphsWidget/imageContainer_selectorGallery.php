<?php if (count($imageHeaders) > 0): ?>
    <div class="widgetWrapper-info">
        <?php echo trans('choose.an.image.from.the.gallery'); ?>
    </div>
<div class="widgetWrapper" style="overflow: visible; display: table; width: 100%;">

<?php foreach ($imageHeaders as $imageHeader): ?>
    <?php foreach ($imageHeader->getImageFile() as $imageFile): ?>
        <?php if ($imageFile->getImageType() == 'thumbnail_w120'): ?>
<?php  
// dump($imageFile);
?>
        <div class="thumbnail-frame-outer">
            <a href="" onclick="OpenGraphImageHandler.selectingImage(event, '<?php echo $imageHeader->getId(); ?>')">
                <div class="thumbnail-frame-inner">
                    <table style="width: 100%;">
                        <tbody>
                            <tr>
                                <td>
                                    <img class="webshopThumbnail" style="max-height: 88px;" src="/openGraph/image/<?php echo $imageFile->getFile()->getFileName().'.'.$imageFile->getFile()->getExtension(); ?>">
                                </td>
                                <td style="width: 20px; padding-left: 4px; height: 88px;">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </a>
        </div>

        <!-- <div class="tagFrame-thumbnail">
            <a href="" onclick="OpenGraphImageHandler.selectingImage(event, '<?php echo $imageHeader->getId(); ?>')">
                <div class="tag-thumbnail">
                    <table style="width: 100%;">
                        <tbody>
                            <tr>
                                <td>
                                    <img class="webshopThumbnail" style="max-height: 88px;" src="/openGraph/image/<?php echo $imageFile->getFile()->getFileName().'.'.$imageFile->getFile()->getExtension(); ?>">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </a>
        </div> -->

        <?php endif; ?>
    <?php endforeach; ?>
<?php endforeach; ?>

</div>
<?php else: ?>
    <div class="widgetWrapper-info">
        <?php echo trans('please.upload.an.image.first'); ?>
    </div>
<?php endif; ?>