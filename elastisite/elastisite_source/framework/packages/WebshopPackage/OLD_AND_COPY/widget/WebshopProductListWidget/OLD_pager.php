<?php 
if ($page > 0):
?>
<div>

</div>
<?php 
endif;
?>

<style>
    .webshopProductList-pager-interfaceContainer {

    }
</style>

<?php
if ($totalPageCount > 1) {

    if ($page > 1) {
        $firstPager = '<a onclick="WebshopProductPager.first()" class="buttonLink" href=""> << '.trans('first').'</a>';
        $prevPager = '<a onclick="WebshopProductPager.prev()" class="buttonLink" href=""> < '.trans('prev').'</a>';
    } else {
        $firstPager = '<< '.trans('first');
        $prevPager = '< '.trans('prev');
    }
?>
<div id="webshopProductList-pager-interfaceContainer" class="widgetWrapper" style="padding: 10px; margin: 10px;">
    <?php echo $firstPager; ?>
 |  <?php echo $prevPager; ?>
 |
<?php

echo $page.'('.$totalPageCount.')';

    if ($totalPageCount > $page) {
        $nextPager = '<a onclick="WebshopProductPager.next()" class="buttonLink" href="">'.trans('next').' > </a>';
        $lastPager = '<a onclick="WebshopProductPager.last()" class="buttonLink" href="">'.trans('last').' >> </a>';
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
var WebshopProductPager = {
    page: <?php echo $page; ?>,
    first: function() {
        WebshopProductPager.page = 1;

    },
    prev: function() {
        WebshopProductPager.page = WebshopProductPager.page - 1;
    },
    next: function() {
        WebshopProductPager.page = WebshopProductPager.page + 1;
    },
    last: function() {
        var page = '<?php echo $totalPageCount; ?>';
        if (page == '') {
            page = <?php echo $page; ?>;
        }
        WebshopProductPager.page = page;

        // console.log(UserAccountGridPager.page);
    },
    toPage: function(page) {
        if (typeof(page) == 'number') {
            WebshopProductPager.page = page;

        }
        else {
            console.log('page is not numeric: ', page);
        }
    }
};
</script>
