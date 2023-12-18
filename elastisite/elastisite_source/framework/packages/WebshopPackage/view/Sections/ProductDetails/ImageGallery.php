<?php  
// dump($productData);
// dump($productData['productImages']);

// $productData['productImages']:
//  [0] Array
//      [slug] u8PXfyKTh98T
//      [link] /webshop/image/thumbnail/u8PXfyKTh98T
//      [isMain] false
?>

<!-- <img src="<?php echo $productData['mainProductImageLink']; ?>" style="height: 500px;"> -->

<style>
    /* .gallery-container {
        display: flex;
    }

    .thumbnails-column {
        flex: 0 0 120px;
        padding: 10px;
    }

    .thumbnails-column img {
        width: 100%;
        height: auto;
        margin-bottom: 10px;
        cursor: pointer;
    }

    .main-image-column {
        flex: 1;
        padding: 10px;
    }

    .main-image {
        width: 100%;
        height: 500px;
        object-fit: cover;
    } */

    .gallery-container {
        display: flex;
    }

    .thumbnails-column {
        flex: 0 0 120px;
        padding: 10px;
    }

    .thumbnails-column img {
        width: 100%;
        height: auto;
        margin-bottom: 10px;
        cursor: pointer;
    }

    .main-image-column {
        position: relative;
        overflow: hidden;
        flex: 1;
        padding: 10px;
    }

    .main-image {
        width: 100%;
        height: auto;
        object-fit: cover;
        max-height: 500px;
        transition: transform 0.3s ease-out;
    }

    /* .gallery-container:hover .main-image {
        transform: translateY(-10%);
    } */
</style>

<div class="gallery-container">
    <div class="thumbnails-column" id="thumbnailColumn">
        <?php foreach ($productData['productImages'] as $productImageData): ?>
            <a href="" onclick="WebshopImageGallery.changeMainImage(event, '<?php echo $productImageData['slug']; ?>')">
                <img src="/webshop/image/thumbnail/<?php echo $productImageData['slug']; ?>" alt="Thumbnail">
            </a>
        <?php endforeach; ?>
    </div>

    <!-- <div class="main-image-column">
        <?php if (!empty($productData['productImages'])): ?>
            <img src="/webshop/image/big/<?php echo $productData['productImages'][0]['slug']; ?>" alt="Main Image" class="main-image" id="mainImage">
        <?php else: ?>
            <p>No images available</p>
        <?php endif; ?>
    </div> -->
    <div class="main-image-column">
        <?php if (!empty($productData['productImages'])): ?>
            <!-- A nagykép első eleme lesz az alapértelmezett -->
            <img src="/webshop/image/big/<?php echo $productData['productImages'][0]['slug']; ?>" alt="Main Image" class="main-image" id="webshop_imageGallery_mainImage">
        <?php else: ?>
        <?php endif; ?>
    </div>
</div>

<script>
    var WebshopImageGallery = {
        changeMainImage: function(event, slug) {
            if (event) {
                event.preventDefault();
            }
            var mainImage = document.getElementById('webshop_imageGallery_mainImage');
            mainImage.src = '/webshop/image/big/' + slug;
        }
    };
</script>