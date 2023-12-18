<?php
// dump($form->getEntity());
$formView = $viewTools->create('form')->setForm($form);
$formView->setResponseBodySelector('#editorModalBody');
$formView->setResponseLabelSelector('#editorModalLabel');
$routeNameSelect = $formView->add('select')
    ->setPropertyReference('country')
    ->setLabel(trans('country'));

foreach ($countries as $country) {
    $routeNameSelect->addOption(
        $country->getId(), 
        $country->getTranslationReference().'.country', 
        true
    );
}
$formView->add('text')->setPropertyReference('zipCode')->setLabel(trans('zip.code'));
$formView->add('text')->setPropertyReference('city')->setLabel(trans('city'));
$formView->add('text')->setPropertyReference('street')->setLabel(trans('street.name'));

$streetSuffixSelect = $formView->add('select')->setPropertyReference('streetSuffix')->setLabel(trans('street.suffix'));
$streetSuffixSelect->addOption('*null*', '-- '.trans('please.choose').' --');

foreach ($streetSuffixes as $streetSuffixKey => $streetSuffix) {
    $streetSuffixSelect->addOption(trans($streetSuffix), trans($streetSuffix));
}

$formView->add('text')->setPropertyReference('houseNumber')->setLabel(trans('house.number'));
$formView->add('text')->setPropertyReference('staircase')->setLabel(trans('staircase'));
$formView->add('text')->setPropertyReference('floor')->setLabel(trans('floor'));
$formView->add('text')->setPropertyReference('door')->setLabel(trans('door.number'));
// $formView->add('textarea')->setPropertyReference('description')->setLabel(trans('description'));
$formView->add('submit')->setPropertyReference('submit')->setValue(trans('save'));
// $formView->setFormMethodPath('/admin/openGraph/widget');
$formView->displayForm()->displayScripts();

// dump($_COOKIE);
?>
<!-- <div class="row">
    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
    </div>
    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
        <div class="form-group">
            <button name="WebshopPackage_editAddress_submit" id="WebshopPackage_editAddress_submit" type="button" class="btn btn-secondary btn-block" style="width: 200px;" onclick="WebshopPackageAddAddressForm.call(event);" value="">Elküldés</button>
        </div>
    </div>
</div> -->
<?php 
// dump($advanceForm);
?>


<script>
var WebshopPackageAddAddressForm = {
    call: function(e) {
        var form = $('#WebshopPackage_editAddress_form');
        var formData = form.serialize();
        var additionalData = {
            'submitted': true
        };
        ajaxData = formData + '&' + $.param(additionalData);
        $.ajax({
            'type' : 'POST',
            'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/webshop/<?php echo $change ? 'change' : 'add' ?>Address',
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                $('#editorModalBody').html(response.view);
                WebshopCheckout.submit(false, false, response.data.addressId, null);
                if (response.data.addressId != null) {
                    $('#editorModal').modal('hide');
                }
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
            },
        });
    },
};
</script>