<?php 
// echo $form->getEntity()->getRepository()->find(1368);exit;
?>

<b><?php echo trans('ordered.products'); ?></b>:
<?php foreach ($shipmentItems as $shipmentItem): ?>
<?php  
// dump($shipmentItem->getProduct()->getProductImage());
$discountPercent = $priceData[$shipmentItem->getProduct()->getId()]['discount_percent'];
$productNameStr = $shipmentItem->getProduct()->getName();
$productNameStr = $discountPercent > 0 ? '<span style="color: #8e167b;">'.$productNameStr.' ('.trans('discounted').')</span>' : $productNameStr;
?>
<div class="row">
    <div class="tagFrame-col">
        <div class="tag-light">
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
<?php 
// dump($shipmentItem->getProductPrice());
?>
<?php endforeach; ?>