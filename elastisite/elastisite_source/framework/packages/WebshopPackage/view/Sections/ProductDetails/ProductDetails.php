<?php 
// dump($offeredQuantity);
// dump($productData);
// dump(!empty($packDataSet));
// dump(isset($productData['id']));
// dump(!empty($packDataSet) && isset($productData['id']));
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
<?php if (!empty($packDataSet) && isset($productData['id'])): ?>
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
            // dump($productData);
            // $offeredQuantity = 12;
            $options = [
                'displaySaveButton' => true
            ];
            $closeModalAfterSubmit = false;
            include('framework/packages/WebshopPackage/view/Sections/SideCart/SetCartItemQuantityModal.php');
            $productDescription = empty($productData['description']) ? $productData['shortInfo'] : $productData['description']; 
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
<?php else: ?>
<script>
    // location.reload();
</script>
<?php endif; ?>