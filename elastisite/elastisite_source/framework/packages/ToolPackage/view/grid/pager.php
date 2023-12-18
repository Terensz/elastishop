<?php
if ($grid->getTotalPageCount() > 1) {

    if ($grid->getPage() > 1) {
        $firstPager = '<a onclick="'.ucfirst($grid->getGridName()).'GridPager.first()" class="buttonLink" href=""> << '.trans('first').'</a>';
        $prevPager = '<a onclick="'.ucfirst($grid->getGridName()).'GridPager.prev()" class="buttonLink" href=""> < '.trans('prev').'</a>';
    } else {
        $firstPager = '<< '.trans('first');
        $prevPager = '< '.trans('prev');
    }
?>
<div id="<?php echo ucfirst($grid->getGridName()); ?>-gridPagerContainer">
    <?php echo $firstPager; ?>
 |  <?php echo $prevPager; ?>
 |
<?php

echo $grid->getPage();

    if ($grid->getTotalPageCount() > $grid->getPage()) {
        $nextPager = '<a onclick="'.ucfirst($grid->getGridName()).'GridPager.next()" class="buttonLink" href="">'.trans('next').' > </a>';
        $lastPager = '<a onclick="'.ucfirst($grid->getGridName()).'GridPager.last()" class="buttonLink" href="">'.trans('last').' >> </a>';
    } else {
        $nextPager = ''.trans('next').' > ';
        $lastPager = ''.trans('last').' >> ';
    }

?>
 |  <?php echo $nextPager; ?>
 |  <?php echo $lastPager; ?>

</div>
<?php
}
?>
<script>
var <?php echo ucfirst($grid->getGridName()); ?>GridPager = {
    page: <?php echo $grid->getPage(); ?>,
    first: function() {
        <?php echo ucfirst($grid->getGridName()); ?>GridPager.page = 1;
        <?php echo ucfirst($grid->getGridName()); ?>Search.search(<?php echo ucfirst($grid->getGridName()); ?>GridPager.page);
    },
    prev: function() {
        <?php echo ucfirst($grid->getGridName()); ?>GridPager.page = <?php echo ucfirst($grid->getGridName()); ?>GridPager.page - 1;
        <?php echo ucfirst($grid->getGridName()); ?>Search.search(<?php echo ucfirst($grid->getGridName()); ?>GridPager.page);
    },
    next: function() {
        <?php echo ucfirst($grid->getGridName()); ?>GridPager.page = <?php echo ucfirst($grid->getGridName()); ?>GridPager.page + 1;
        <?php echo ucfirst($grid->getGridName()); ?>Search.search(<?php echo ucfirst($grid->getGridName()); ?>GridPager.page);
    },
    last: function() {
        var page = '<?php echo $grid->getTotalPageCount(); ?>';
        if (page == '') {
            page = <?php echo $grid->getPage(); ?>;
        }
        <?php echo ucfirst($grid->getGridName()); ?>GridPager.page = page;
        <?php echo ucfirst($grid->getGridName()); ?>Search.search(<?php echo ucfirst($grid->getGridName()); ?>GridPager.page);
        // console.log(UserAccountGridPager.page);
    },
    toPage: function(page) {
        if (typeof(page) == 'number') {
            <?php echo ucfirst($grid->getGridName()); ?>GridPager.page = page;
            <?php echo ucfirst($grid->getGridName()); ?>Search.search(<?php echo ucfirst($grid->getGridName()); ?>GridPager.page);
        }
        else {
            console.log('page is not numeric: ', page);
        }
    }
};
</script>
