<?php  

use framework\packages\PaymentPackage\entity\Payment;

// dump($paymentService);
// dump($paymentService->preparePayment());

$paymentService->preparePayment();
$packDataSet = $paymentService->packDataSet;
// dump($packDataSet);
// dump($paymentService->providerApiResponse);

if ($paymentService->providerApiResponse['Errors'] == [] 
    && $paymentService->providerApiResponse['Status'] == Payment::PAYMENT_STATUS_PREPARED 
    // && $packDataSet['summary']['sumGrossItemPriceRounded2'] == $paymentService->providerApiResponse['Status']
    ): ?>

<script>
window.location.href = "<?php echo $paymentService->providerApiResponse['GatewayUrl']; ?>";
</script>
    
<?php else: ?>
    Hiba
<?php endif; ?>
<!-- PaymentModal!!! -->