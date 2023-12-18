<?php 
// dump($offeredQuantity);
// dump($cartDataSet);
?>
<div style="display: flex;">
    <div style="flex: 0 0 300px;">
        <?php 
        $cartItemData = null;
        if (isset($cartDataSet['cart']['cartItems']['productId-'.$productData['productId']])) {
            $cartItemData = $cartDataSet['cart']['cartItems']['productId-'.$productData['productId']];
        }
        $maxWidthPixels = '300';
        $options['skipFooter'] = true;
        // $cartItemData
        $cartItemData = null;
        if (isset($cartDataSet['cart']['cartItems']['productId-'.$productData['productId']]['cartItem'])) {
            $cartItemData = $cartDataSet['cart']['cartItems']['productId-'.$productData['productId']]['cartItem'];
        }
        include('framework/packages/WebshopPackage/view/Sections/ProductList/ProductCard.php');
        ?>
    </div>
    <div style="flex-grow: 1; margin-left: 20px;">
        <div class="card">
            <div class="card-body">
            <?php 
            // $offeredQuantity = 12;
            $options = [
                'displaySaveButton' => true
            ];
            include('framework/packages/WebshopPackage/view/Sections/SideCart/SetCartItemQuantityModal.php');
            $productDescription = $productData['productDescription']; 
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