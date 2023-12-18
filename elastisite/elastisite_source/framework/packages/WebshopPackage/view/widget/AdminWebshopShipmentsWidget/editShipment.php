<!-- <script>
$(document).ready(function() {
    $('body').on('click', '#WebshopPackage_editShipment_submit', function() {
        $('#WebshopPackage_editShipment_zipCode').val($('#WebshopPackage_editShipment_addressZipCode').val());
        $('#WebshopPackage_editShipment_city').val($('#WebshopPackage_editShipment_addressCity').val());
    });
});
</script> -->
<?php 

if (!empty($shipmentDataSet)) {
    include('framework/packages/WebshopPackage/view/Common/ShipmentList/ShipmentList.php');
}

$editable = false;
// dump($closedShipmentIsEditable);
if ($closedShipmentIsEditable || (!$closedShipmentIsEditable && (!$form->getEntity()->getClosed() || $form->getEntity()->getClosed() == 0))) {
    $editable = true;
}
// dump($form->getEntity()->getOrganization());
// editShipment

// dump($form);
// dump($form->getEntity()->getTemporaryAccount());

$formView = $viewTools->create('form')->setForm($form);

// $formView->setToInactive();
// countryId
// dump($form);
$formView->setResponseBodySelector('#editorModalBody');
$formView->add($editable ? 'text' : 'inactiveField')->setPropertyReference('code')->setLabel(trans('code'));

// dump($form->getEntity()->getShipmentItem());
// $shipmentItemsStr = '';
// foreach ($form->getEntity()->getShipmentItem() as $shipmentItem) {
//     $shipmentItemsStr .= '<>';
// }


// $formView->add('custom')->setPropertyReference(null)->setLabel()->addCustomData('view', $shipmentItemsView);

if ($editable) {
    $statusSelect = $formView->add('select')
        ->setPropertyReference('status')
        ->setLabel(trans('status'));
    foreach ($statuses as $statusIndex => $statusProperties) {
        $statusSelect->addOption(
            $statusIndex, 
            trans($statusProperties['adminTitle']), 
            false
        );
    }
} else {
    $statusIndex = $form->getValueCollector()->getDisplayed('status');
    // dump($form->getValueCollector()->getDisplayed('status'));
    // dump($statuses);
    $formView->add('inactiveField')->setPropertyReference('status')->setLabel(trans('status'))->setValue(trans($statuses[$statusIndex]['adminTitle']));
}

if ($form->getEntity()->getTemporaryAccount()->getTemporaryPerson()->getOrganization() && $form->getEntity()->getTemporaryAccount()->getTemporaryPerson()->getOrganization()->getId()):
?>
<div class="card" id="">
    <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
        <div class="card-header-textContainer">
            <h6 class="mb-0 text-white"><?php echo trans('organization'); ?></h6>
        </div>
    </div>
    <div class="card-body">
        <div class="mb-0">
            <div class="form-group">
                <b><?php echo trans('corporate.customer'); ?></b> (<?php echo $form->getEntity()->getTemporaryAccount()->getTemporaryPerson()->getOrganization()->getName(); ?>)
            </div>
        </div>
    </div>
</div>
<?php
endif;
    
// $formView->add('hidden')->setPropertyReference('zipCode')->setLabel(trans('zip.code'));
// $formView->add('hidden')->setPropertyReference('city')->setLabel(trans('city'));
$customerNote = $form->getEntity()->getTemporaryAccount()->getTemporaryPerson()->getCustomerNote();
// $noticeView = '<div class="widgetWrapper-info">'.((!$notice || $notice == '') ? trans('no.notice') : $notice).'</div>';

$customerNoteView = '
<div class="card" id="">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <div class="card-header-textContainer">
            <h6 class="mb-0 text-white">'.trans('customer.notice').'</h6>
        </div>
    </div>
    <div class="card-body">
        <div class="mb-0">
            <div class="form-group">
                '.((!$customerNote || $customerNote == '') ? '<div class="widgetWrapper-light">'.trans('no.notice').'</div>' : '<div class="widgetWrapper-info">'.$customerNote.'</div>').'
            </div>
        </div>
    </div>
</div>
';
// '<span style="margin-top: 10px;">'.trans('no.notice').'</span>'

$formView->add('custom')->setPropertyReference(null)->setLabel()->addCustomData('view', $customerNoteView);
$formView->add($editable ? 'textarea' : 'inactiveField')->setPropertyReference('adminNote')->setLabel(trans('admin.note'));
$formView->add($editable ? 'text' : 'inactiveField')->setPropertyReference('temporaryPersonName')->setLabel(trans('name'));
$formView->add($editable ? 'text' : 'inactiveField')->setPropertyReference('temporaryPersonEmail')->setLabel(trans('email'));
$formView->add($editable ? 'text' : 'inactiveField')->setPropertyReference('temporaryPersonMobile')->setLabel(trans('mobile'));
// dump($countries);
if ($editable) {
    $countrySelect = $formView->add('select')
        ->setPropertyReference('countryId')
        ->setLabel(trans('country'));
    foreach ($countries as $country) {
        $countrySelect->addOption(
            $country->getId(), 
            $country->getTranslationReference().'.country', 
            true
        );
    }
} else {
    $countryIndex = $form->getValueCollector()->getDisplayed('countryId');
    foreach ($countries as $country) {
        $countryId = $country->getId();
        if ($countryIndex == $countryId) {
            $formView->add('inactiveField')->setPropertyReference('countryId')->setLabel(trans('country'))->setValue(trans($country->getTranslationReference().'.country'));
        }
    }
    // dump($countryIndex);
}
$formView->add($editable ? 'text' : 'inactiveField')->setPropertyReference('addressZipCode')->setLabel(trans('zip.code'));
$formView->add($editable ? 'text' : 'inactiveField')->setPropertyReference('addressCity')->setLabel(trans('city'));
$formView->add($editable ? 'text' : 'inactiveField')->setPropertyReference('addressStreet')->setLabel(trans('street'));
$formView->add($editable ? 'text' : 'inactiveField')->setPropertyReference('addressStreetSuffix')->setLabel(trans('street.suffix'));
$formView->add($editable ? 'text' : 'inactiveField')->setPropertyReference('addressHouseNumber')->setLabel(trans('house.number'));

$formView->add($editable ? 'text' : 'inactiveField')->setPropertyReference('addressStaircase')->setLabel(trans('staircase'));
$formView->add($editable ? 'text' : 'inactiveField')->setPropertyReference('addressFloor')->setLabel(trans('floor'));
$formView->add($editable ? 'text' : 'inactiveField')->setPropertyReference('addressDoor')->setLabel(trans('door.number'));

if ($form->getEntity()->getTemporaryAccount()->getTemporaryPerson()->getOrganization() && $form->getEntity()->getTemporaryAccount()->getTemporaryPerson()->getOrganization()->getId()):
    $formView->add('custom')->setPropertyReference(null)->addCustomData('view', '<div class="card"><div class="card-header bg-primary text-white">'.trans('organization').'</div><div class="card-body">');


    /**
     * If the VAT-declaration already done, than neither the admin (or even God himself) can modify the data of the tax payer. 
    */
    // dump();



    // dump($form->getEntity()->getOrganization());
    $formView->add($editable ? 'text' : 'inactiveField')->setPropertyReference('orgName')->setLabel(trans('organization.name'));
    $formView->add($editable ? 'text' : 'inactiveField')->setPropertyReference('orgTaxId')->setLabel(trans('organization.tax.id'));
    if ($editable) {
        $orgCountrySelect = $formView->add('select')
            ->setPropertyReference('countryId')
            ->setLabel(trans('organization.country'));
        foreach ($countries as $country) {
            $orgCountrySelect->addOption(
                $country->getId(), 
                $country->getTranslationReference().'.country', 
                true
            );
        }
    } else {
        $orgCountryIndex = $form->getValueCollector()->getDisplayed('orgCountryId');
        foreach ($countries as $country) {
            $orgCountryId = $country->getId();
            if ($orgCountryIndex == $orgCountryId) {
                $formView->add('inactiveField')->setPropertyReference('orgCountryId')->setLabel(trans('organization.country'))->setValue(trans($country->getTranslationReference().'.country'));
            }
        }
        // dump($countryIndex);
    }
    $formView->add($editable ? 'text' : 'inactiveField')->setPropertyReference('orgZipCode')->setLabel(trans('organization.zip.code'));
    $formView->add($editable ? 'text' : 'inactiveField')->setPropertyReference('orgCity')->setLabel(trans('organization.city'));
    $formView->add($editable ? 'text' : 'inactiveField')->setPropertyReference('orgStreet')->setLabel(trans('organization.street'));
    $formView->add($editable ? 'text' : 'inactiveField')->setPropertyReference('orgStreetSuffix')->setLabel(trans('organization.street.suffix'));
    $formView->add($editable ? 'text' : 'inactiveField')->setPropertyReference('orgHouseNumber')->setLabel(trans('organization.house.number'));

    $formView->add('custom')->setPropertyReference(null)->addCustomData('view', '</div></div>');
endif;

//$productCategorySelect = $formView->add('select')->setPropertyReference('productCategoryId')->setLabel(trans('product.category'));
if ($editable) {
    $formView->add('submit')->setPropertyReference('submit')->setValue(trans('save'));
}
// $orderCloseWarning = '<div class="widgetWrapper-danger">'.trans('order.close.warning').'</div>';

if ($removeTemporaryPersonOnCloseShipment && (!$form->getEntity()->getClosed() || $form->getEntity()->getClosed() == 0)) {
    $orderCloseWarning = '
<div class="card" id="WebshopPackage_editShipment_closeWarning">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <div class="card-header-textContainer">
            <h6 class="mb-0 text-white">'.trans('information').'</h6>
        </div>
    </div>
    <div class="card-body">
        <div class="mb-0">
            <div class="widgetWrapper-danger">'.trans('order.close.warning').'</div>
        </div>
    </div>
</div>
';
    $formView->add('custom')->setPropertyReference(null)->setLabel(trans('image'))->addCustomData('view', $orderCloseWarning);
}

// if ($editable && !$closedStatus) {
// if ($editable) {
if ($editable && !$closedStatus) {
    $formView->add('infoButton')->setPropertyReference('closeOrder')->setValue(trans('close.order'));
}

$formView->setFormMethodPath('admin/webshop/shipment/edit');
$formView->displayForm();

$formView->displayScripts();
?>

<script>
    var EditShipment = {
        closeShipment: function(shipmentId) {
            var form = $('#WebshopPackage_editShipment_form');
            var formData = form.serialize();
            var additionalData = {
                'id': shipmentId,
                'closeShipment': true
                // 'submitted': true
            };
            ajaxData = formData + '&' + $.param(additionalData);
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/webshop/shipment/edit',
                'data': ajaxData,
                'async': true,
                'success': function(response) {
                    ElastiTools.checkResponse(response);
                    AdminWebshopShipmentsGrid.list(true);
                    // $('#editorModalBody').html(response.view);
                    AdminWebshopShipmentsGrid.edit(shipmentId);
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                },
            });
            // $('#editorModal').modal('show');
            // WebshopPackageEditShipmentForm.call('' + shipmentId + '');
        },
        showAndHideSaveAndClose: function(initialCall) {
            // console.log($('#WebshopPackage_editShipment_status').val());
            if ($('#WebshopPackage_editShipment_status').val() == '<?php echo $closedStatus; ?>' || $('#WebshopPackage_editShipment_status').val() == '<?php echo $cancelledStatus; ?>') {
                $('#WebshopPackage_editShipment_closeWarning').show();
                $('#WebshopPackage_editShipment_closeOrder').show();
                if (initialCall == false) {
                    $('#WebshopPackage_editShipment_submit').parent().hide();
                }
            } else {
                $('#WebshopPackage_editShipment_closeWarning').hide();
                $('#WebshopPackage_editShipment_closeOrder').hide();
                $('#WebshopPackage_editShipment_submit').parent().show();
            }
        }
    };

    $('document').ready(function() {
        EditShipment.showAndHideSaveAndClose(true);

        $('#WebshopPackage_editShipment_status').change(function() {
            EditShipment.showAndHideSaveAndClose(false);
        });

        $('#WebshopPackage_editShipment_closeOrder').click(function() {
            // console.log('close order <?php echo $form->getEntity()->getId(); ?>');
            EditShipment.closeShipment('<?php echo $form->getEntity()->getId(); ?>');
        });
    });
</script>