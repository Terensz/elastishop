<!-- Pager -->
<?php 
if ($totalPages != 0):
?>
<div style="padding-top: 10px;">
    <nav aria-label="">
        <ul class="pagination">
        <?php if ($currentPage > 1): ?>
            <li class="page-item"><a class="page-link triggerModal" href="" onclick="<?php echo $dataGridId; ?>.setPage(event, '<?php echo ($currentPage - 1); ?>');"><?php echo trans('previous'); ?></a></li>
        <?php else: ?>
            <li class="page-item disabled"><span class="page-link"><?php echo trans('previous'); ?></span></li>
        <?php endif; ?>
        <?php $pageListBreakOccurred = false; ?>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <?php 
                if ($i > 9 && $i != $totalPages && $currentPage != $i) {
                    if (!$pageListBreakOccurred) {
            ?>
                        <li class="page-item"><span class="page-link"><?php echo '...'; ?></span></li>
            <?php 
                        $pageListBreakOccurred = true;
                    }
                } else {
            ?>
                    <li class="page-item<?php echo $currentPage == $i ? ' active' : '' ; ?>"><a class="page-link triggerModal" href="" onclick="<?php echo $dataGridId; ?>.setPage(event, '<?php echo $i; ?>');"><?php echo $i; ?></a></li>
                    <!-- <li class="page-item<?php echo $currentPage == $i ? ' active' : '' ; ?>"><a class="page-link triggerModal" href="" onclick=""><?php echo $i; ?></a></li> -->
            <?php 
                }
            ?>
        <?php endfor; ?>
        <?php if ($currentPage == $totalPages): ?>
            <li class="page-item disabled"><span class="page-link"><?php echo trans('next'); ?></span></li>
        <?php else: ?>
            <li class="page-item"><a class="page-link triggerModal" href="" onclick="<?php echo $dataGridId; ?>.setPage(event, '<?php echo ($currentPage + 1); ?>');"><?php echo trans('next'); ?></a></li>
        <?php endif; ?>
        </ul>
    </nav>
</div>
<?php 
endif;
?>
<!-- End of pager -->