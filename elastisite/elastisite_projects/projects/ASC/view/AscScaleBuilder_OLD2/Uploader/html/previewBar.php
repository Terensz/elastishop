<?php  
// dump($ascUnitFiles);
?>

<div class="widgetWrapper" style="overflow: visible; display: table; width: 100%;">
<?php if (count($ascUnitFiles) == 0): ?>
    <?php echo trans('no.uploaded.files.yet'); ?>
<?php endif; ?>
<?php foreach ($ascUnitFiles as $ascUnitFile): ?>
        <div class="tagFrame-thumbnail">
            <div class="tag-thumbnail">
                <table style="width: 100%;">
                    <tbody>
                        <tr>
                            <td>
                                <img class="webshopThumbnail" style="max-height: 88px;" src="/asc/unitImage/thumbnail/<?php echo $ascUnitFile->getFileName(); ?>">
                            </td>
                            <td style="width: 20px; padding-left: 4px; height: 88px;">
                                <a class="" href="" onclick="Upload.initDelete(event, '<?php echo $ascUnitFile->getId(); ?>');">X</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
<?php endforeach; ?>
        <!-- <div class="tagFrame-thumbnail">
            <div class="tag-thumbnail">
                <table style="width: 100%;">
                    <tbody>
                        <tr>
                            <td>
                                <img class="webshopThumbnail" style="max-height: 88px;" src="/openGraph/image/vwebDuMw_hEcELTVcvZZG_thumbnailW120.jpg">
                            </td>
                            <td style="width: 20px; padding-left: 4px; height: 88px;">
                                <a class="" href="" onclick="OpenGraphImageHandler.deleteGalleryImage(event, '59004');">X</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div> -->

            
</div>