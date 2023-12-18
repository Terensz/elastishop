<?php if (count($imageHeaders) > 0): ?>
<!-- <div class="widgetWrapper" style="overflow: visible; display: table; width: 100%;"> -->
<?php endif; ?>

<?php foreach ($imageHeaders as $imageHeader): ?>
    <?php foreach ($imageHeader->getImageFile() as $imageFile): ?>
        <?php if ($imageFile->getImageType() == 'thumbnail_w120'): ?>
<?php  
// dump($imageHeader->getId());
// dump($imageHeader->getRepository()->isDeletable($imageHeader->getId()));
?>
        <div class="thumbnail-frame-outer">
            <div class="thumbnail-frame-inner">
                <table style="width: 100%;">
                    <tbody>
                        <tr>
                            <td>
                                <img class="webshopThumbnail" style="max-height: 88px;" src="/openGraph/image/<?php echo $imageFile->getFile()->getFileName().'.'.$imageFile->getFile()->getExtension(); ?>">
                            </td>
                            <td style="width: 20px; padding-left: 4px; height: 88px;">
<?php  
if ($imageHeader->getRepository()->isDeletable($imageHeader->getId())):
?>
                                <a class="" href="" onclick="OpenGraphImageHandler.deleteGalleryImage(event, '<?php echo $imageHeader->getId(); ?>');">X</a>
<?php
endif;
?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <?php endif; ?>
    <?php endforeach; ?>
<?php endforeach; ?>

<?php if (count($imageHeaders) > 0): ?>
<!-- </div> -->
<?php endif; ?>