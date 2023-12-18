<?php 
include('framework/packages/WebshopPackage/view/Parts/TestWebshopWarning.php');
?>

<div class="widgetWrapper">

Átirányítás ide: <?php echo $paymentParams['gatewayUrl']; ?>

<?php 
//dump($paymentParams); 
?>
</div>
<?php if ($paymentParams['gatewayUrl']): ?>
<script>
    window.location.href = '<?php echo $paymentParams['gatewayUrl']; ?>';
</script>
<?php endif; ?>