<?php

use framework\packages\UserPackage\entity\User;
use framework\packages\WebshopPackage\entity\Shipment;

App::getContainer()->wireService('WebshopPackage/entity/Shipment');

if (!isset($config['isAdmin'])) {
    $config['isAdmin'] = App::getContainer()->getUser()->getType() == User::TYPE_ADMINISTRATOR ? true : false;
}
if (!isset($config['colorPriorizedForAdmin'])) {
    $config['colorPriorizedForAdmin'] = true;
}
// dump($packDataSet);
?>
<?php if (count($packDataCollection) > 0): ?>

    <?php foreach ($packDataCollection as $packDataSet): ?>
<?php 

$cardHeaderStyleClasses = 'bg-primary text-white';
if (in_array($packDataSet['pack']['status'], Shipment::STATUS_COLLECTION_UNFINISHED_ORDER_STATUSES)) {
    // $cardTitleText = trans('unfinished.order');
    $cardHeaderStyleClasses = 'bg-danger text-white';
} elseif (in_array($packDataSet['pack']['status'], [Shipment::SHIPMENT_STATUS_CLOSED])) {
    // $cardTitleText = trans('unfinished.order');
    $cardHeaderStyleClasses = 'bg-success text-white';
} 
// else {
//     $cardTitleText = trans('order.in.progress');
//     if (isset(Shipment::$statuses[$packDataSetRow['pack']['status']])) {
//         $cardTitleText = Shipment::$statuses[$packDataSetRow['pack']['status']][($config['isAdmin'] ? 'adminTitle' : 'publicTitle')];
//     }
//     $cardHeaderStyleClasses = 'bg-primary text-white';
// }

$cardTitleText = trans('order.in.progress');
if (isset(Shipment::$statuses[$packDataSet['pack']['status']])) {
    $cardTitleText = trans(Shipment::$statuses[$packDataSet['pack']['status']][($config['isAdmin'] ? 'adminTitle' : 'publicTitle')]);
}

if (App::getContainer()->getUser()->getType() == User::TYPE_ADMINISTRATOR && $config['colorPriorizedForAdmin'] && $packDataSet['pack']['priority'] == Shipment::PRIORITY_HIGH) {
    $cardHeaderStyleClasses = 'bg-warning text-white';
}

?>
    <?php  
        $shipmentCode = $packDataSet['pack']['code'];
        // dump($packDataSetRow);
        include('ShipmentCard.php');
    ?>

    <?php endforeach; ?>
<?php endif; ?>