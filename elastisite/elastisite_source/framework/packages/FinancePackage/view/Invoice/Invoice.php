<?php
// dump($invoiceData);
if (!isset($invoiceData)) {
    $invoiceData = [];
    dump('invoiceData is missing');
}

if (!isset($invoiceData['invoiceItems'])) {
    dump('invoiceItems is missing');
    $invoiceData['invoiceItems'] = [];
}

if (!isset($invoiceData['invoiceItemsSummary']['grossAmountRounded2'])) {
    $invoiceData['invoiceItemsSummary']['grossAmountRounded2'] = null;
    dump('invoiceItemsSummary/grossAmountRounded2 is missing');
}

if (!isset($invoiceData['currencyCode'])) {
    $invoiceData['currencyCode'] = null;
    dump('currencyCode is missing');
}

// $summaryGrossAmountRounded2
// dump($invoiceData);
?>

<?php  
// include('InvoiceHeader.php');

include('InvoiceItems.php');
?>