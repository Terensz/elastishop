@charset "UTF-8";
<?php 
// echo $container->getSkinData('defaultFont');

?>
<?php foreach ($fonts->registeredFonts as $font): ?>
@font-face {
    font-family: <?php echo $font->fontFamily; ?>;
    src: url('<?php echo $font->source; ?>');
}
<?php endforeach; ?>
