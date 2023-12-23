<?php 

/**
 * @var $mainProductImageLink
 * @var $productName
 * @var $grossItemPriceFormatted
 * @var $quantity
 * @var $grossUnitPriceFormatted
*/

?>
<!-- Common/ProductTinyCardFooter -->
<div class="card-footer">
    <table style="width: 100%;">
        <tr>
            <td class="table-m-1" style="text-align: right; width: 66px;">
                <?php if ($mainProductImageLink): ?>
                <img src="<?php echo $mainProductImageLink; ?>" class="img-fluid rounded-3" alt="Shopping item" style="width: 65px; height:65px;">
                <?php endif; ?>
            </td>
            <td class="table-m-1" style="width: 10px;"></td>
            <td class="table-m-1" style="width: auto;">
                <div>
                    <?php echo $productName; ?> (<b><?php echo $quantity; ?></b>)
                </div>
                <div>
                    <b><?php echo $grossItemPriceFormatted.($quantity > 1 ? ' ('.$quantity.' * '.$grossUnitPriceFormatted.')' : '').' '.$currencyCode; ?></b>
                </div>
            </td>
            <td class="table-m-1" style="text-align: right; width: 40px;">
                <?php if (isset($editIconOnclick) && $editIconOnclick): ?>
                    <div>
                        <!-- <a href="" onclick="<?php echo $editIconOnclick; ?>" style="color: #cecece;"><i class="fas fa-edit"></i></a> -->
                        <a href="" onclick="<?php echo $editIconOnclick; ?>" style="color: #cecece;">
                            <img src="/public_folder/plugin/Bootstrap-icons/Dashkit-blue/cart.svg" style="width:16px; height: 16px;">
                        </a>
                    </div>
                <?php endif; ?>
            </td>
        </tr>
    </table>
</div>