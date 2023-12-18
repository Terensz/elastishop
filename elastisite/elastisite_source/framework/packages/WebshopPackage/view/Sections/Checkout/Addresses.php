<?php

use framework\packages\UserPackage\entity\User;

?>
<?php if ($userType == User::TYPE_USER || ($userType == User::TYPE_GUEST && count($addressesData) == 0)): ?>
<div class="mb-4">
    <button type="button" onclick="Webshop.addAddressInit(event);" class="btn btn-success"><?php echo trans('add.new.delivery.address'); ?></button>
</div>
<?php endif; ?>
<?php if ($userType == User::TYPE_GUEST && count($addressesData) > 0): ?>
<!-- <div class="mb-4">
    <button type="button" onclick="Webshop.editAddressInit(event, '<?php echo $temporaryAccountData['temporaryPerson']['address']['id']; ?>');" class="btn btn-info"><?php echo trans('edit.delivery.address'); ?></button>
</div> -->
<?php endif; ?>
<?php 
$cardErrorClass = ' card-success';
if ($errors['messages']['addressSelected']) {
    $cardErrorClass = ' card-error';
}
?>
<div class="card<?php echo $cardErrorClass; ?>">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
        <div class="card-header-textContainer">
            <h6 class="mb-0 text-white"><?php echo trans('select.delivery.address'); ?></h6>
            <?php
            // dump($cartData);
            // dump('alma');
            // echo "&nbsp; (".App::getElapsedLoadingTime().")";
            ?>
        </div>
    </div>

    <?php if ($errors['messages']['addressSelected']): ?>
    <div class="card-body text-danger">
    <?php echo $errors['messages']['addressSelected']; ?>
    </div>
    <?php endif; ?>

    <div class="pro-scroll">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover m-b-0">
                    <thead>
                        <tr>
                            <th><?php echo trans('address'); ?></th>
                        </tr>
                    </thead>
                    <tbody style="cursor: pointer;">
                        <?php foreach ($addressesData as $addressData): ?>
                        <?php 
                        $tdActiveClassStr = '';
                        $optionClassStringAdd = '';
                        if ($addressData['addressId'] === $temporaryAccountData['temporaryPerson']['address']['id']) {
                            $tdActiveClassStr = ' table-dark';
                            $optionClassStringAdd = '-triggerModal';
                        }
                        ?>
                        <tr class="WebshopPackage_checkout_address_option<?php echo $optionClassStringAdd; ?>" data-id="<?php echo $addressData['addressId']; ?>" id="WebshopPackage_checkout_address_<?php echo $addressData['addressId']; ?>">
                            <td class="<?php echo $tdActiveClassStr; ?>"><?php echo $addressData['addressString']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>