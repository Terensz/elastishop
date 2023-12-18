<?php if ($openGraph->hasImageHeader()): ?>
<?php 
    // $imageFiles = $openGraph->getMainImageFile()->getImageFile();
    // dump($openGraph->getMainImageFile('fullSize')->getFile());
    $imageFile = $openGraph->getMainImageFile('fullSize');
    // $imageFile->getFile()->getFileName().'.'.$imageFile->getFile()->getExtension()
?>
    <a href="" onclick="OpenGraphImageHandler.unbindImage(event)"><?php echo trans('unbind.image.from.og'); ?></a><br>
    <img class="webshopThumbnail" style="width: 100%; max-width: 500px;" src="/openGraph/image/<?php echo $imageFile->getFile()->getFileName().'.'.$imageFile->getFile()->getExtension(); ?>">

<?php else: ?>
<?php 
?>
    <div id="AdminOpenGraphs_selectorGallery" style="padding-bottom: 10px;">
    </div>

    <script>
        $('document').ready(function() {
            OpenGraphImageHandler.loadSelectorGallery();
        });
    </script>
<?php endif; ?>
<?php 
// dump('alma2');
//     dump($openGraph); 
?>