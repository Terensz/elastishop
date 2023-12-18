<div class="article-title">
    <?php echo trans('create.price'); ?>
</div>

<?php 
// dump($productPrices);

$formView2 = $viewTools->create('form')->setForm($form);
$formView2->setResponseBodySelector('#newProductPrice-form');
$formView2->add('hidden')->setPropertyReference('productId')->setLabel(trans('product.id'));
$formView2->add('text')->setPropertyReference('title')->setLabel(trans('short.note'));
$formView2->add('text')->setPropertyReference('grossPrice')->setLabel(trans('gross.price'));

$text = '<div class="mb-3"><div id="WebshopPackage_newProductPrice_netPriceSuggestion"></div></div>';
$formView2->add('custom')->setPropertyReference(null)->addCustomData('view', $text);

$formView2->add('custom')->setPropertyReference(null)->addCustomData('view', '<div class="widgetWrapper-info2">'.trans('vat.profile').': <b>'.$vatProfile.'</b>. '.trans('in.case.of.missing.vat.percent.check.tax.office.settings').'</div>');
// $formView2->add('text')->setPropertyReference('vat')->setLabel(trans('vat'));


// $vatPercentages
$vatPercentageSelect = $formView2->add('select')->setPropertyReference('vat')->setLabel(trans('vat'));
foreach ($vatPercentages as $vatPercentage) {
    $vatPercentageSelect->addOption($vatPercentage, $vatPercentage.'%');
}

$priceTypeSelect = $formView2->add('select')->setPropertyReference('priceType')->setLabel(trans('price.type'));
if ($listPricesCount == 0) {
    $priceTypeSelect->addOption('list', 'list.price');
}
if ($listPricesCount > 0) {
    $priceTypeSelect->addOption('discount', 'discount.price');
}

// $formView2->add('submit')->setPropertyReference('submit')->setValue(trans('send'));
$formView2->setFormMethodPath('admin/webshop/productPrice/new');
// $formView2->displayForm()->displayScripts();
$formView2->displayForm();
?>

<div class="mb-3">
    <button name="WebshopPackage_newProductPrice_submit" id="WebshopPackage_newProductPrice_submit" type="button" class="btn btn-primary btn-block" style="width: 200px;" onclick="ProductPrice.save(event);" value=""><?php echo trans('save.price'); ?></button>

    <button name="WebshopPackage_newProductPrice_cancel" id="WebshopPackage_newProductPrice_cancel" type="button" class="btn btn-secondary btn-block" style="width: 200px;" onclick="ProductPrice.cancelNew(event);" value=""><?php echo trans('cancel'); ?></button>
</div>

<!-- <div class="row">
    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
    </div>
    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
        <div class="form-group">
            <button name="WebshopPackage_newProductPrice_submit" id="WebshopPackage_newProductPrice_submit" type="button" class="btn btn-secondary btn-block" style="width: 200px;" onclick="ProductPrice.save(event);" value=""><?php echo trans('save.price'); ?></button>
        </div>
    </div>
    <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5">
        <div class="form-group">
            <button name="WebshopPackage_newProductPrice_submit" id="WebshopPackage_newProductPrice_submit" type="button" class="btn btn-secondary btn-block" style="width: 200px;" onclick="ProductPrice.cancelNew(event);" value=""><?php echo trans('cancel'); ?></button>
        </div>
    </div>
</div> -->
<script>

console.log('WebshopPackage_newProductPrice_netPrice document ready');

$(document).ready(function() {
    $('#WebshopPackage_newProductPrice_netPrice').on('input', function () {
        console.log('WebshopPackage_newProductPrice_netPrice input');

        // Frissítsd a vat értékét
        var inputNetPrice = $('#WebshopPackage_newProductPrice_netPrice').val();
        var vat = $('#WebshopPackage_newProductPrice_vat').val();
        var suggestedNetPrice = inputNetPrice; // Alapértelmezett érték, nem változtatjuk meg, ha a bruttó kerek
        var grossPrice = inputNetPrice * (1 + (vat / 100))

        // Ha nem kerek a szamitott brutto, akkor csinalunk egy kerek bruttot
        var roundedGrossPrice = Math.round(grossPrice);
        if (grossPrice !== roundedGrossPrice) {
            suggestedNetPrice = roundedGrossPrice / (1 + (vat / 100));
        }

        // Ha a kiszámolt érték változott, akkor frissítsd a mezőt
        if (suggestedNetPrice !== inputNetPrice) {
            $('#WebshopPackage_newProductPrice_netPriceSuggestion').html(suggestedNetPrice);
        } else {
            $('#WebshopPackage_newProductPrice_netPriceSuggestion').html('');
        }
    });

    // $('#WebshopPackage_newProductPrice_netPrice').on('input', function () {
    //     console.log('WebshopPackage_newProductPrice_netPrice input');
    //     // Frissítsd a vat értékét
    //     var vat = parseFloat($('#WebshopPackage_newProductPrice_vat').val()) || 0;

    //     // Számold ki az ajánlott nettó értéket
    //     var inputNetPrice = parseFloat($(this).val()) || 0;

    //     // Kerekített bruttó érték
    //     var roundedGross = Math.round(inputNetPrice * (1 + vat / 100) * 100) / 100;

    //     // Amíg a bruttó nem kerek, javasolj új nettót
    //     while (roundedGross % 1 !== 0) {
    //         inputNetPrice--;
    //         roundedGross = Math.round(inputNetPrice * (1 + vat / 100) * 100) / 100;
    //     }

    //     $('#WebshopPackage_newProductPrice_netPriceSuggestion').html(inputNetPrice.toFixed(2));
    // });
});
    // $('document').ready(function() {
    //     $('#WebshopPackage_newProductPrice_productId').val(<?php echo $productId; ?>);
    // });
</script>