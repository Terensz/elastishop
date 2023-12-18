<!-- <h1><?php echo trans('invoice'); ?></h1> -->
<div style="font-family: Arial;">
<table style="width: 100%;">
    <tr>
        <td>
            <div><?php echo $issuerName; ?></div>
        </td>
        <td>
            <div style="font-size: 13px;">
                <div>
                    <b><?php echo trans('issuer.of.this.invoice'); ?></b>
                </div>
                <div>
                    <?php echo trans('issuer.name') . ': <b>' . $taxOfficeConfig['issuer.name'] . '</b>'; ?>
                </div>
                <div>
                    <?php echo trans('issuer.address') . ': <b>' . $issuerConcatenatedAddress . '</b>'; ?>
                </div>
                <div>
                    <?php echo trans('issuer.tax.id') . ': <b>' . $taxOfficeConfig['issuer.displayedTaxId'] . '</b>'; ?>
                </div>
                <div>
                    <?php echo trans('issuer.bank.account.number') . ': <b>' . $taxOfficeConfig['issuer.bankAccountNumber'] . '</b> (' . $taxOfficeConfig['issuer.bankName'] . ')'; ?>
                </div>
                <?php if (!empty($taxOfficeConfig['issuer.iban'])): ?>
                <div>
                    <?php echo trans('issuer.iban') . ': <b>' . $taxOfficeConfig['issuer.iban'] . '</b>'; ?>
                </div>
                <?php endif; ?>
                <?php if (!empty($taxOfficeConfig['issuer.swift'])): ?>
                <div>
                    <?php echo trans('issuer.iban') . ': <b>' . $taxOfficeConfig['issuer.swift'] . '</b>'; ?>
                </div>
                <?php endif; ?>
            </div>
        </td>
    </tr>
</table>

<div style="height: <?php echo $viewSpaceHeight; ?>;"></div>

<div style="width: 100%; background-color: #c0c0c0; padding: 10px;">
    <div style="font-size: 20px; font-weight: bold;"><?php echo trans('invoice.number') . ': ' . $invoiceHeader->getInvoiceNumber(); ?></div>
</div>

<div style="height: <?php echo $viewSpaceHeight; ?>;"></div>

<table style="width: 100%;">
    <tr>
        <td>
            <div style="font-size: 13px;">
                <div>
                    <b><?php echo trans('buyer.data'); ?></b>
                </div>
                <div>
                    <?php echo trans('name') . ': <b>' . $invoiceHeader->getBuyerName() . '</b>'; ?>
                </div>
                <div>
                    <?php echo trans('address') . ': <b>' . $invoiceHeader->getBuyerAddress() . '</b>'; ?>
                </div>
                <?php if ($invoiceHeader->getBuyerPersonType() == $invoiceHeader::PERSON_TYPE_ORGANIZATION): ?>
                <div>
                    <?php echo trans('issuer.tax.id') . ': <b>' . $invoiceHeader->getBuyerTaxId() . '</b>'; ?>
                </div>
                <?php endif; ?>
            </div>
        </td>
        <td>
            <div>
                <div style="font-size: 20px;">
                    <?php echo trans('amount.to.be.paid') . ': <b>' . $invoiceTaxData['formattedTotalGross'] . ' ' . $currency . '</b>'; ?>
                </div>
            </div>
        </td>
    </tr>
</table>

<div style="height: <?php echo $viewSpaceHeight; ?>;"></div>

<table style="width: 100%; font-size: 15px;">
    <tr>
        <td style="background-color: #c0c0c0; margin: 2px; padding: 6px;">
            <?php echo trans('item.name'); ?>
        </td>
        <td style="background-color: #c0c0c0; margin: 2px; padding: 6px;">
            <?php echo trans('quantity'); ?>
        </td>
        <td style="background-color: #c0c0c0; margin: 2px; padding: 6px;">
            <?php echo trans('unit.of.measure'); ?>
        </td>
        <td style="background-color: #c0c0c0; margin: 2px; padding: 6px;">
            <?php echo trans('net.unit.price'); ?>
        </td>
        <td style="background-color: #c0c0c0; margin: 2px; padding: 6px;">
            <?php echo trans('vat'); ?>
        </td>
        <td style="background-color: #c0c0c0; margin: 2px; padding: 6px;">
            <?php echo trans('item.gross'); ?>
        </td>
    </tr>
<?php foreach ($invoiceTaxData['invoiceItemsTaxData'] as $invoiceItemTaxData): ?>
    <tr>
        <td style="background-color: #f2f2f2; margin: 2px; padding: 6px;">
            <?php echo $invoiceItemTaxData['productName']; ?>
        </td>
        <td style="background-color: #f2f2f2; margin: 2px; padding: 6px;">
            <?php echo $invoiceItemTaxData['quantity']; ?>
        </td>
        <td style="background-color: #f2f2f2; margin: 2px; padding: 6px;">
            <?php echo trans($invoiceItemTaxData['unitOfMeasure']); ?>
        </td>
        <td style="background-color: #f2f2f2; margin: 2px; padding: 6px;">
            <?php echo $invoiceItemTaxData['formattedUnitNet']; ?> <?php echo $currency; ?>
        </td>
        <td style="background-color: #f2f2f2; margin: 2px; padding: 6px;">
            <?php echo $invoiceItemTaxData['vatPercent']; ?>
        </td>
        <td style="background-color: #f2f2f2; margin: 2px; padding: 6px;">
            <?php echo $invoiceItemTaxData['formattedItemGross']; ?> <?php echo $currency; ?>
        </td>
    </tr>
<?php endforeach; ?>
    <tr>
        <td style="background-color: #f2f2f2; margin: 2px; padding: 6px;">
            <b><?php echo trans('sum'); ?></b>
        </td>
        <td style="background-color: #f2f2f2; margin: 2px; padding: 6px;">
            
        </td>
        <td style="background-color: #f2f2f2; margin: 2px; padding: 6px;">
            
        </td>
        <td style="background-color: #f2f2f2; margin: 2px; padding: 6px;">
            
        </td>
        <td style="background-color: #f2f2f2; margin: 2px; padding: 6px;">
            
        </td>
        <td style="background-color: #f2f2f2; margin: 2px; padding: 6px;">
            <b><?php echo $invoiceTaxData['formattedTotalGross'] . ' ' . $currency ?></b>
        </td>
    </tr>
</table>

<div>
<?php 
// dump($invoiceTaxData['invoiceItemsTaxData']); 
?>
</div>