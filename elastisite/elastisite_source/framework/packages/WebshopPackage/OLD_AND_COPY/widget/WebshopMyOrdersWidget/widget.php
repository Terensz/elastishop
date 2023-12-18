<div class="widgetWrapper">
<?php
$counter = 0;
?>
<?php foreach ($shipments as $shipment): ?>
<?php if ($counter > 0): ?>
<div style="padding-top: 10px;">
</div>
<?php endif ?>
    <div class="detailedTable-container">
        <div class="row">
            <div class="col-4">
                <div class="detailedTable-field detailedTable-attribute">
                    <?php echo trans('shipment.identifier'); ?>
                </div>
            </div>
            <div class="col-8">
                <div class="detailedTable-field detailedTable-value">
                    <b><?php echo $shipment->getCode(); ?></b>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-4">
                <div class="detailedTable-field detailedTable-attribute">
                    <?php echo trans('status'); ?>
                </div>
            </div>
            <div class="col-8">
                <div class="detailedTable-field detailedTable-value">
                    <b><?php echo $shipment->getStatusText(); ?></b>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-4">
                <div class="detailedTable-field detailedTable-attribute">
                    <?php echo trans('shipping.address'); ?>
                </div>
            </div>
            <div class="col-8">
                <div class="detailedTable-field detailedTable-value">
                    <b><?php echo $shipment->getTemporaryAccount()->getTemporaryPerson()->getAddress(); ?></b>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-4">
                <div class="detailedTable-field detailedTable-attribute">
                    <?php echo trans('recipient'); ?>
                </div>
            </div>
            <div class="col-8">
                <div class="detailedTable-field detailedTable-value">
                    <b><?php echo $shipment->getTemporaryAccount()->getTemporaryPerson()->getName(); ?></b>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-4">
                <div class="detailedTable-field detailedTable-attribute">
                    <?php echo trans('notice'); ?>
                </div>
            </div>
            <div class="col-8">
                <div class="detailedTable-field detailedTable-value">
                    <b><?php echo $shipment->getCustomerNote(); ?></b>
                </div>
            </div>
        </div>
        <div style="padding: 10px;">
        </div>
        <div class="row">
            <div class="col-12">
            <?php 
            $totalPrice = 0;
            foreach ($shipment->getShipmentItem() as $shipmentItem):
                $netPrice = $shipmentItem->getProductPrice()->getNetPrice();
                $vat = $shipmentItem->getProductPrice()->getVat();
                $grossPiecePrice = ceil($netPrice * (1 + $vat / 100));
                $grossPiecePriceString = number_format($grossPiecePrice, 0, null, ' ');
                $stackGrossPrice = $grossPiecePrice * $shipmentItem->getQuantity();
                $stackGrossPriceString = number_format($stackGrossPrice, 0, null, ' ');
                $totalPrice += $stackGrossPrice;
            ?>
                <div class="row">
                    <div class="col-4">
                        <div class="detailedTable-field detailedTable-value">
                            <?php echo $shipmentItem->getQuantity().' '.trans('pcs.of').' '.$shipmentItem->getProduct()->getName(); ?>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="detailedTable-field detailedTable-value">
                            <?php echo $stackGrossPrice.' '.$container->getConfig()->getProjectData('defaultCurrency').' ('.$netPrice.' '.$container->getConfig()->getProjectData('defaultCurrency').' + '.$vat.'% '.trans('vat2').' '.trans('per.piece').')' ; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
                <div class="row">
                    <div class="col-4">
                        <div class="detailedTable-field detailedTable-attribute">
                            <b><?php echo trans('total'); ?></b>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="detailedTable-field detailedTable-value">
                            <b><?php echo $totalPrice.' '.$container->getConfig()->getProjectData('defaultCurrency'); ?></b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php 
$counter++;
?>
<?php endforeach ?>
</div>