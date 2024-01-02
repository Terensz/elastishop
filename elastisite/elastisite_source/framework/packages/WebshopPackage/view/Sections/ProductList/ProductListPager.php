<?php 
$pagesCount = $pagerData['totalPages'];
$currentPage = $pagerData['currentPage'];
$category = '';
$dataGridId = 'Alma';
// dump($currentPage);
// dump($pagesCount);
?>
<div id="pager-<?php echo $category; ?>-container" class="card-footer">
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <?php 

                $onClickString = $currentPage == 1 ? "" : $dataGridId.".setPage(event, '".($currentPage - 1)."');";
                // $onClickString = $dataGridId.".setPage(event, '".($currentPage - 1)."');";
                $disabledString = ($currentPage == 1) ? ' disabled' : '';
                // $disabledString = '';
            ?>
            <li class="page-item<?php echo $disabledString; ?>">
                <a class="page-link" href="<?php echo $pagerData['prevPageLink']; ?>" onclick="" aria-label="<?php echo trans('previous'); ?>">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <?php for ($i = 1; $i <= $pagesCount; $i++): ?>
            <?php 
                $activeString = $i == $currentPage ? ' active' : '';
                $onClickString = $dataGridId.".setPage(event, '".$i."');";
            ?>
            <li class=" page-item<?php echo $activeString; ?>" onclick="">
                <a class="page-link ajaxCallerLink" href="<?php echo $pagerData['variablePageLink'] ? str_replace('[page]', $i, $pagerData['variablePageLink']) : 'ALMA'; ?>" onclick=""><?php echo $i; ?></a>
            </li>
            <?php endfor; ?>
            <?php 
                $onClickString = $currentPage == $pagesCount ? "" : $dataGridId.".setPage(event, '".($currentPage + 1)."');";
                // $onClickString = $dataGridId.".setPage(event, '".($currentPage + 1)."');";
                $disabledString = ($currentPage == $pagesCount) ? ' disabled' : '';
                // $disabledString = '';
            ?>
            <li class="page-item<?php echo $disabledString; ?>">
                <a class="page-link" href="<?php echo $pagerData['nextPageLink']; ?>" onclick="" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
</div>