<?php

use framework\packages\UserPackage\entity\User;

// $temporaryAccountData['temporaryPerson']['organization']['id'];
?>

<?php if ($userType == User::TYPE_USER || ($userType == User::TYPE_GUEST && count($organizationsData) == 0)): ?>
<div class="mb-4">
    <button type="button" onclick="Webshop.addOrganizationInit(event);" class="btn btn-success"><?php echo trans('add.new.organization'); ?></button>
</div>
<?php endif; ?>
<?php if ($userType == User::TYPE_GUEST && count($organizationsData) > 0): ?>
<!-- <div class="mb-4">
    <button type="button" onclick="Webshop.editOrganizationInit(event, '<?php echo $temporaryAccountData['temporaryPerson']['organization']['id']; ?>');" class="btn btn-info"><?php echo trans('edit.organization'); ?></button>
</div> -->
<?php endif; ?>
<?php 
$cardErrorClass = ' card-success';
if ($errors['messages']['organizationSelected']) {
    $cardErrorClass = ' card-error';
}
// dump($organizationData);exit;
?>
<div class="card<?php echo $cardErrorClass; ?>">

    <div class="bg-secondary text-white card-header d-flex justify-content-between align-items-center">
        <div class="card-header-textContainer">
            <h6 class="mb-0 text-white"><?php echo trans('customer.organization'); ?></h6>
        </div>
    </div>

    <?php if ($errors['messages']['organizationSelected']): ?>
    <div class="card-body text-danger">
    <?php echo $errors['messages']['organizationSelected']; ?>
    </div>
    <?php endif; ?>

    <div class="pro-scroll">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover m-b-0">
                    <thead>
                        <tr>
                            <th><?php echo trans('organization.name'); ?></th>
                            <th><?php echo trans('organization.tax.id'); ?></th>
                            <th><?php echo trans('organization.address'); ?></th>
                        </tr>
                    </thead>
                    <tbody style="cursor: pointer;">
                        <?php foreach ($organizationsData as $organizationData): ?>
                        <?php 
                        $tdActiveClassStr = '';
                        $optionClassStringAdd = '';
                        if ($organizationData['organizationId'] === $temporaryAccountData['temporaryPerson']['organization']['id']) {
                            $tdActiveClassStr = ' table-dark';
                            $optionClassStringAdd = '-triggerModal';
                        }
                        ?>
                        <tr class="WebshopPackage_checkout_organization_option<?php echo $optionClassStringAdd; ?>" data-id="<?php echo $organizationData['organizationId']; ?>" id="WebshopPackage_checkout_organization_<?php echo $organizationData['organizationId']; ?>">
                            <td class="<?php echo $tdActiveClassStr; ?>"><?php echo $organizationData['organizationName']; ?></td>
                            <td class="<?php echo $tdActiveClassStr; ?>"><?php echo $organizationData['organizationTaxId']; ?></td>
                            <td class="<?php echo $tdActiveClassStr; ?>"><?php echo $organizationData['addressString']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- <div class="cart-body">
    </div> -->
</div>

<?php 
    // dump($customerData);
    // dump('alma');
    // echo "&nbsp; (".App::getElapsedLoadingTime().")";
    ?>