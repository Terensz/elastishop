<?php

use framework\packages\WebshopPackage\responseAssembler\WebshopResponseAssembler_GWOReply;

App::getContainer()->wireService('WebshopPackage/responseAssembler/WebshopResponseAssembler_GWOReplyBarion');

include('framework/packages/WebshopPackage/view/Common/ShipmentList/ShipmentList.php');
?>

<div class="card card-<?php echo $result; ?>">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <div class="card-header-textContainer">
            <h6 class="mb-0 text-white"><?php echo trans('information'); ?></h6>
        </div>
    </div>
    <div class="card-body">
        <div class="mb-0">
            <?php if ($result == WebshopResponseAssembler_GWOReply::RESULT_SUCCESS): ?>
                <?php echo trans('payment.successful'); ?>
            <?php else: ?>
                <?php echo trans('payment.failed'); ?>
            <?php endif; ?>
        </div>
    </div>
</div>