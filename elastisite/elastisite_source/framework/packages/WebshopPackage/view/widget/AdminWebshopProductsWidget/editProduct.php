<?php

use framework\packages\WebshopPackage\entity\Product;

App::getContainer()->wireService('WebshopPackage/entity/Product');

?>
<style>
.productTabs {
    padding-bottom: 20px;
    /* border-top: 1px solid #c0c0c0; */
}
.productTab-active {
    height: 100%;
    width: 100%;
    border-top: 1px solid #c0c0c0;
    border-left: 1px solid #c0c0c0;
    border-right: 1px solid #c0c0c0;
    padding: 10px;
    margin: 0px;
    z-index: 20;
    text-align: center;
    cursor: pointer;
}
.productTab-inactive {
    height: 100%;
    width: 100%;
    border: 1px solid #eaeaea;
    padding: 10px;
    margin: 0px;
    background-color: #d7d7d7;
    color: #fff;
    text-align: center;
    cursor: pointer;
}
</style>
<?php
\App::get()->includeOnce('framework/packages/ToolPackage/view/upload/js.php');
// dump($form->getEntity()->getId());//exit;
// dump($productCategories);
// dump($form);exit;
$formView = $viewTools->create('form')->setForm($form);
$formView->setResponseBodySelector('#editorModalBody');
$formView->setResponseLabelSelector('#editorModalLabel');
$formView->add('text')->setPropertyReference('name')->setLabel(trans('name'));
if (in_array('en', App::getContainer()->getConfig()->getSupportedLocales())) {
    $formView->add('text')->setPropertyReference('nameEn')->setLabel(trans('english.name'));
}

$formView->add('select')->setPropertyReference('specialPurpose')->setLabel(trans('special.purpose'))
    ->addOption('null', 'none')
    ->addOption(Product::SPECIAL_PURPOSE_DELIVERY_FEE, 'delivery.fee')
    ->addOption(Product::SPECIAL_PURPOSE_GIFT, 'gift')
    ;

$formView->add('text')->setPropertyReference('shortInfo')->setLabel(trans('short.info'));
if (in_array('en', App::getContainer()->getConfig()->getSupportedLocales())) {
    $formView->add('text')->setPropertyReference('shortInfoEn')->setLabel(trans('english.info'));
}
$formView->add('text')->setPropertyReference('code')->setLabel(trans('code'));

// $codeInfoText = '<div class="widgetWrapper-info">'.trans('product.code.not.required.info').'</div>';
$codeInfoText = '<div class="card">
<div class="bg-primary text-white card-header d-flex justify-content-between align-items-center">
    <div class="card-header-textContainer">
        <h6 class="mb-0 text-white">'.trans('information').'</h6>
    </div>
</div>
<div class="card-body">
    <span>
        '.trans('product.code.not.required.info').'
    </span>
</div>
</div>';

$formView->add('custom')->setPropertyReference(null)->setLabel(trans('image'))->addCustomData('view', $codeInfoText);
$productCategorySelect = $formView->add('select')->setPropertyReference('productCategoryId')->setLabel(trans('product.category'));
// dump($formView);
// dump($productCategorySelect);
$productCategorySelect->addOption(0, trans('main.category'));
foreach ($productCategories as $productCategory) {
    // dump('alma');
    // dump($product);
    $productCategorySelect->addOption(
        // $product->getproduct() ? $product->getproduct()->getId() : null,
        $productCategory->getId(),
        $productCategory->getName()
    );
}
$formView->add('select')->setPropertyReference('status')->setLabel(trans('status'))
    ->addOption('1', 'active')
    ->addOption('2', 'out.of.stock')
    ->addOption('3', 'discontinued')
    ->addOption('0', 'disabled')
    ;
$formView->add('textarea')->setPropertyReference('description')->setLabel(trans('description'));
if (in_array('en', App::getContainer()->getConfig()->getSupportedLocales())) {
    $formView->add('textarea')->setPropertyReference('descriptionEn')->setLabel(trans('english.description'));
}
// dump($productSelect);exit;
$formView->add('submit')->setPropertyReference('submit')->setValue(trans('save'));
$formView->setFormMethodPath('admin/webshop/product/edit');
// dump($productId);
?>



<div id="productId" style="display: none;"><?php echo $productId; ?></div>

<div class="card-header" style="padding-bottom: 0px !important;">
    <ul class="nav nav-tabs" id="myTabs" role="tablist" style="border-bottom: 0px !important;">
        <li class="nav-item">
            <a class="navLink-priorized nav-link active productTab productTab-property doNotTriggerHref" 
                data-tabid="property" data-toggle="tab" href="" onclick="ProductModal.switchTab(event, 'property');" role="tab" aria-controls="tab1" aria-selected="true">
                <?php echo trans('product.property.settings'); ?>
            </a>
        </li>

    <!-- <div class="row productTabs"> -->

    <!-- <div href="" data-tabid="property" onclick="ProductModal.switchTab(event, 'property');" class="col-lg-3 productTab productTab-property productTab-inactive doNotTriggerHref">
        <a class="doNotTriggerHref" href=""><?php echo trans('product.property.settings'); ?></a>
    </div> -->
<?php
if ($productId):
?>
        <li class="nav-item">
            <a class="navLink-priorized nav-link productTab productTab-price doNotTriggerHref" 
                data-tabid="price" data-toggle="tab" href="" onclick="ProductModal.switchTab(event, 'price');" role="tab" aria-controls="tab1" aria-selected="true">
                <?php echo trans('product.price.settings'); ?>
            </a>
        </li>
        <li class="nav-item">
            <a class="navLink-priorized nav-link productTab productTab-image doNotTriggerHref" 
                data-tabid="image" data-toggle="tab" href="" onclick="ProductModal.switchTab(event, 'image');" role="tab" aria-controls="tab1" aria-selected="true">
                <?php echo trans('product.image.settings'); ?>
            </a>
        </li>

    <!-- <div href="" data-tabid="price" onclick="ProductModal.switchTab(event, 'price');" class="col-lg-3 productTab productTab-price productTab-inactive doNotTriggerHref">
        <a class="doNotTriggerHref" href=""><?php echo trans('product.price.settings'); ?></a>
    </div>
    <div href="" data-tabid="image" onclick="ProductModal.switchTab(event, 'image');" class="col-lg-3 productTab productTab-image productTab-inactive doNotTriggerHref">
        <a class="doNotTriggerHref" href=""><?php echo trans('product.image.settings'); ?></a>
    </div> -->
<?php
else:
?> 
    <!-- <div class="col-lg-3 productTab-price productTab-inactive doNotTriggerHref">
        <?php echo trans('product.price.settings'); ?>
    </div>
    <div class="col-lg-3 productTab-image productTab-inactive doNotTriggerHref">
        <?php echo trans('product.image.settings'); ?>
    </div> -->
<?php
endif;
?>

    </ul>
</div>

<!-- </div> -->
<div class="product-property-container">
<?php
$formView->displayForm()->displayScripts();
?>
</div>
<?php
if ($form->getEntity()->getId()) {
    // include('framework/packages/WebshopPackage/view/widget/AdminWebshopProductsWidget/prices.php');
    // include('framework/packages/WebshopPackage/view/widget/AdminWebshopProductsWidget/images.php');
?>
<div class="product-price-container"></div>
<!-- <div style="margin-top: 20px;">
</div> -->
<div class="product-image-container"></div>
<!-- <div class="product-email-container"></div> -->
<?php
}
// dump($formView);exit;
?>

<script>
$(document).ready(function() {
    ProductModal.switchTab(null, 'property');
    console.log('Call ProductPrice.show 9');
    ProductPrice.show();
    ProductImage.show();
    ProductEmail.show();

    // console.log('edit product !!!');

    new nicEditor({buttonList :['fontSize','bold','italic','underline','ol','ul']}).panelInstance('WebshopPackage_editProduct_description', {hasPanel : true});
    new nicEditor({buttonList :['fontSize','bold','italic','underline','ol','ul']}).panelInstance('WebshopPackage_editProduct_descriptionEn', {hasPanel : true});

    $('.nicEdit-main').on('blur', function() {
        let content1 = nicEditors.findEditor("WebshopPackage_editProduct_description").getContent();
        $('#WebshopPackage_editProduct_description').html(content1);
        let content2 = nicEditors.findEditor("WebshopPackage_editProduct_descriptionEn").getContent();
        $('#WebshopPackage_editProduct_descriptionEn').html(content2);
    });
});
</script>