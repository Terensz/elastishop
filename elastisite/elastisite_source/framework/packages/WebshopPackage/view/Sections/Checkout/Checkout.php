<?php

use framework\packages\UserPackage\entity\User;

?>
<?php if (App::getContainer()->getUser()->getType() != User::TYPE_ADMINISTRATOR): ?>
    <div class="row">
        <div class="col-md-12 card-pack-header">
            <h4><?php echo trans('products.in.cart'); ?></h4>
        </div>
    </div>

    <?php 
    // $options['skipFooter'] = true;
    // dump($productsData);
    include('framework/packages/WebshopPackage/view/Sections/ProductList/ProductList.php');
    ?>

    <div class="row">
        <div class="col-md-12 card-pack-header">
            <h4><?php echo trans('invoice.header.data'); ?></h4>
        </div>
    </div>

    <?php 
    include('CustomerType.php');
    ?>
    <div id="WebshopPackage_checkout_organizationContainer"<?php if ($customerType != 'Organization') { echo ' style="display: none;"'; } ?>>
    <?php 
    include('Organizations.php');
    ?>
    </div>
    <?php 
    // $invoiceItemsData = $invoiceData['invoiceItemsData'];
    include('framework/packages/FinancePackage/view/Invoice/Invoice.php');
    ?>

    <div class="row">
        <div class="col-md-12 card-pack-header">
            <h4><?php echo trans('delivery.data'); ?></h4>
        </div>
    </div>

    <?php 
    include('Addresses.php');
    ?>

    <?php 
    include('DeliveryInformation.php');
    ?>

    <?php 
    include('TermsAndConditions.php');
    ?>

    <?php if ($errors['summary']['checkoutPermitted']): ?>
    <div class="mb-4">
        <button type="button" onclick="Webshop.finishCheckout(event);" class="btn btn-success"><?php echo trans('finish.checkout'); ?></button>
    </div>
    <?php else: ?>

    <?php endif; ?>
<?php endif; ?>