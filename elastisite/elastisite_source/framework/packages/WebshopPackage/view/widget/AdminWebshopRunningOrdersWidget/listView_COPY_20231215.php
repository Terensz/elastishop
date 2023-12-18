<?php
// dump($shipments);
?>
<?php foreach ($shipments as $shipment): ?>


<div class="row" style="cursor: pointer;" onclick="RunningOrders.edit(event, <?php echo $shipment->getId(); ?>);">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="tagFrame-col" id="">
            <div class="tag-light">
                <div class="">
                    <div class="">
                        <?php echo trans('name'); ?>: <b><?php echo $shipment->getTemporaryAccount()->getTemporaryPerson()->getName(); ?></b>
                    </div>
                    <?php if ($shipment->getOrganization()): ?>
                        <div class="widgetWrapper-info2"><?php echo trans('corporate.customer'); ?>: <b><?php echo $shipment->getOrganization()->getName(); ?></b></div>
                    <?php endif; ?>
                    <div class="">
                        <?php echo trans('delivery.address'); ?>: <?php echo $shipment->getTemporaryAccount()->getTemporaryPerson()->getAddress(); ?>
                    </div>
                    <div class="">
                        <?php echo trans('status'); ?>: <b><?php echo trans($statuses[$shipment->getStatus()]['adminTitle']); ?></b>
                    </div>
                    <?php 
                    // echo trans('ordered.products').':'; 
                    ?>
                    <?php foreach ($shipment->getShipmentItem() as $shipmentItem): ?>
                        <?php  
                            $priceData = $stackedPriceData[$shipment->getId()];
                            // dump($shipmentItem->getProduct()->getProductImage());
                            $discountPercent = $priceData[$shipmentItem->getProduct()->getId()]['discount_percent'];
                            $productNameStr = $shipmentItem->getProduct()->getName();
                            $productNameStr = $discountPercent > 0 ? '<span style="color: #8e167b;">'.$productNameStr.' ('.trans('discounted').')</span>' : $productNameStr;
                            ?>
                            <div class="row">
                                <div class="tagFrame-col">
                                    <div class="tag-ultraLight">
                                        <b><?php echo $shipmentItem->getQuantity(); ?></b> <?php echo trans('pcs.of'); ?> <b><?php echo $productNameStr; ?></b>
                                        (<?php echo $priceData[$shipmentItem->getProduct()->getId()]['net_price'].' '.$currency; ?> + <?php echo $priceData[$shipmentItem->getProduct()->getId()]['vat']; ?>% <?php echo trans('vat2'); ?>)
                                        <?php 
                                        if ($priceData[$shipmentItem->getProduct()->getId()]['price_changed_to']) { 
                                            echo '<br><b>'.trans('order.time.price.changed').'</b>.'; 
                                            echo ' ('.trans('actual.net.price').': '.$priceData[$shipmentItem->getProduct()->getId()]['price_changed_to'].' '.$currency.')';
                                        } 
                                        ?>
                                    </div>
                                </div>
                            </div>
                    <?php endforeach; ?>
                </div>
                <!-- <table style="width: 100%;">
                    <tbody>
                        <tr>
                            <td id="">
                                <b>Kapcsolat</b>
                            </td>
                        </tr>
                        <tr>
                            <td id="">
                                contact (<b>en</b>)<br>kapcsolat (<b>hu</b>)
                            </td>
                        </tr>
                    </tbody>
                </table> -->
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>