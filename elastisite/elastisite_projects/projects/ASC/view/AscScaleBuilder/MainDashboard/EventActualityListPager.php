<?php 
// dump($actualPage);
// dump($pagesCount);
?>
<div id="pager-<?php echo $category; ?>-container" class="card-footer">
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <?php 
                // $onClickString = $actualPage == 1 ? "" : "EventActualityPager.jumpTo(event, '".$category."', '".($actualPage - 1)."');";
                $onClickString = "EventActualityPager.jumpTo(event, '".$category."', '".($actualPage - 1)."');";
                // $disabledString = ($actualPage == 1) ? ' disabled' : '';
                $disabledString = '';
            ?>
            <li class="page-item<?php echo $disabledString; ?>">
                <a class="page-link" href="" onclick="<?php echo $onClickString; ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <?php for ($i = 1; $i <= $pagesCount; $i++): ?>
            <?php 
                $activeString = $i == $actualPage ? ' active' : '';
                $onClickString = "EventActualityPager.jumpTo(event, '".$category."', '".$i."');";
            ?>
            <li class="pager-<?php echo $category; ?>-pageItem pager-<?php echo $category; ?>-pageItem-<?php echo $i; ?> page-item<?php echo $activeString; ?>" onclick="<?php echo $onClickString; ?>">
                <!-- <?php echo $i; ?> -->
                <a class="page-link" href="" onclick="<?php echo $onClickString; ?>"><?php echo $i; ?></a>
            </li>
            <?php endfor; ?>
            <?php 
                // $onClickString = $actualPage == $pagesCount ? "" : "EventActualityPager.jumpTo(event, '".$category."', '".($actualPage + 1)."');";
                $onClickString = "EventActualityPager.jumpTo(event, '".$category."', '".($actualPage + 1)."');";
                // $disabledString = ($actualPage == $pagesCount) ? ' disabled' : '';
                $disabledString = '';
            ?>
            <li class="page-item<?php echo $disabledString; ?>">
                <a class="page-link" href="" onclick="<?php echo $onClickString; ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
</div>