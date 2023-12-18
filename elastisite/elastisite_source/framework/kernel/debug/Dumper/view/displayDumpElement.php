<?php
foreach ($blocks as $block) {
?>
    <div style="padding-left: <?php echo (int)$block['tab'] * 40; ?>px">
        <?php echo $block['wrappedKey']; ?> <?php echo $block['wrappedValue']; ?>
    </div>
<?php
}
?>