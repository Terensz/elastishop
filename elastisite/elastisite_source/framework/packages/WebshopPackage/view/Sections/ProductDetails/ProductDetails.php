<?php 
// dump($offeredQuantity);
// dump($packDataSet);
?>
<style>
    .productInfoModal-flex-container {
        display: flex;
    }
    @media (max-width: 991px) {
        .productInfoModal-flex-container {
            flex-direction: column; /* Váltás függőleges elrendezésre kis képernyőn */
        }
    }
</style>
<div class="productInfoModal-flex-container">
    <div class="productInfoModal-productCard" style="flex: 0 0 300px;">
        <?php 
        $cartItemData = null;
        if (isset($packDataSet['pack']['packItems']['productId-'.$productData['id']])) {
            $cartItemData = $packDataSet['pack']['packItems']['productId-'.$productData['id']];
        }
        $maxWidthPixels = '300';
        $options['skipFooter'] = true;
        // $cartItemData
        $cartItemData = null;
        if (isset($packDataSet['pack']['packItems']['productId-'.$productData['id']])) {
            $cartItemData = $packDataSet['pack']['packItems']['productId-'.$productData['id']];
        }
        include('framework/packages/WebshopPackage/view/Sections/ProductList/ProductCard.php');
        ?>
    </div>
    <!-- Ennek le kellene ugrania a masik melle -->
    <div class="productInfoModal-productDetails" style="flex-grow: 1; margin-left: 20px;">
        <div class="card">
            <div class="card-body">
            <?php 
            // $offeredQuantity = 12;
            $options = [
                'displaySaveButton' => true
            ];
            $closeModalAfterSubmit = false;
            include('framework/packages/WebshopPackage/view/Sections/SideCart/SetCartItemQuantityModal.php');
            $productDescription = $productData['description']; 
            include('ProductDescription.php');
            ?>
            </div>
        </div>
    </div>
</div>
<?php if (count($productData['productImages']) > 0): ?>
<div class="card">
    <div class="card-body">
    <?php
    include('ImageGallery.php');
    ?>
    </div>
</div>
<?php endif; ?>