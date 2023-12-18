<?php 
include('framework/packages/WebshopPackage/view/Parts/TestWebshopWarning.php');
?>

<div class="widgetWrapper">

<?php 
// echo trans('test.webshop.warning'); 
?>

<?php echo trans('you.have.a.started.payment'); ?> <a target="_self" href="<?php echo $paymentParams['gatewayUrl']; ?>"><?php echo $paymentParams['gatewayUrl']; ?></a>
<br><br>
<?php echo trans('if.you.want.to.cancel.payment'); ?>

<?php 
//dump($paymentParams); 
?>
</div>
<?php if ($paymentParams['gatewayUrl']): ?>
<script>
    // window.location.href = '<?php echo $paymentParams['gatewayUrl']; ?>';
</script>
<?php endif; ?>