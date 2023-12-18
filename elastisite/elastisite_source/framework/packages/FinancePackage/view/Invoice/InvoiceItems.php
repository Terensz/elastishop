<?php  
$invoiceItemsData = $invoiceData['invoiceItems'];
// dump($invoiceItemsData);
?>
<div class="card">

    <div class="bg-secondary text-white card-header d-flex justify-content-between align-items-center">
        <div class="card-header-textContainer">
            <h6 class="mb-0 text-white"><?php echo trans('invoice.item.data'); ?></h6>
            <?php 
            // dump('alma');
            // echo "&nbsp; (".App::getElapsedLoadingTime().")";
            ?>
        </div>
    </div>

    <div class="pro-scroll">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover m-b-0">
                    <thead>
                        <tr>
                            <th><?php echo trans('product.name'); ?></th>
                            <th><?php echo trans('quantity'); ?></th>
                            <th><?php echo trans('unit.of.measure'); ?></th>
                            <th><div style="width: 90%; text-align: right;"><?php echo trans('net.unit.price'); ?></div></th>
                            <th><?php echo trans('vat'); ?></th>
                            <th><div style="width: 90%; text-align: right;"><?php echo trans('item.gross'); ?></div></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (isset($invoiceItemsData) && is_array($invoiceItemsData)): ?>
                        <?php foreach ($invoiceItemsData as $invoiceItemDataWrapper): ?>
                            <?php 
                            $invoiceItemData = $invoiceItemDataWrapper['invoiceItem'];
                            $debug = false;

                            if ($debug):
                            ?>
                            <!-- <tr>
                                <td>
                            <?php 
                                // dump($projectTeamUserDataRow);
                            ?>
                                </td>
                            </tr> -->
                            <?php 
                            endif;
                            ?>
                        <tr>
                            <td><?php echo $invoiceItemData['product']['name']; ?></td>
                            <td><?php echo $invoiceItemData['quantity']; ?></td>
                            <td><?php echo trans($invoiceItemData['unitOfMeasure']); ?></td>
                            <td><div style="width: 90%; text-align: right;"><?php echo $invoiceItemData['value']['netUnitPriceFormatted']; ?> <?php echo $invoiceItemData['currencyCode']; ?></div></td>
                            <td><?php echo $invoiceItemData['value']['vatPercent']; ?></td>
                            <td><div style="width: 90%; text-align: right;"><?php echo $invoiceItemData['value']['grossItemPriceFormatted']; ?> <?php echo $invoiceItemData['currencyCode']; ?></div></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                        <tr>
                            <td class="table-active" colspan="5" style="text-align: center; font-weight: bold;"><?php echo trans('total.payable'); ?></td>
                            <td class="table-active" style="font-weight: bold;"><div style="width: 90%; text-align: right;">
                                <?php echo $invoiceData['invoiceItemsSummary']['grossAmountFormatted']; ?> <?php echo $invoiceData['currencyCode']; ?></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>