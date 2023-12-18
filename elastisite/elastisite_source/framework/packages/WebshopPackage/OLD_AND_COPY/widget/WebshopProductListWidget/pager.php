<style>
    .webshopProductList-pager-interface {
        border-radius: 6px;
        box-shadow: 0 4px 6px #353535;
        width: 100%;
        background-color: #fff;
    }
</style>

<?php 
// dump($listParams);
// dump($webshopService->assembleLink($listParams));//exit;

use framework\packages\WebshopPackage\service\WebshopService;

if ($totalPages != 0 && $totalPages > 1):
?>
<div class="webshopProductList-pager-interfaceContainer" style="padding-top: 10px;">
    <div class="webshopProductList-pager-interface">
        <nav aria-label="">
            <ul class="pagination webshopProductList-pager-interface-pagination">
            <?php 
                if ($currentPage > 1):
                    $goToPage = $currentPage - 1;
                    $listParams['currentPage'] = $goToPage; 
                    $link = WebshopService::assembleLink($listParams);
            ?>
                <li class="page-item"><a class="page-link triggerModal" href="" onclick="WebshopProductListPager.goToLink(event, '<?php echo $link; ?>');"><?php echo trans('previous.page'); ?></a></li>
            <?php 
                else: 
            ?>
                <li class="page-item disabled"><span class="page-link"><?php echo trans('previous.page'); ?></span></li>
            <?php 
                endif; 
            ?>
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
                        $goToPage = $i;
                        $listParams['currentPage'] = $goToPage; 
                        $link = WebshopService::assembleLink($listParams);
                ?>
                        <li class="page-item<?php echo $currentPage == $i ? ' active' : '' ; ?>"><a class="page-link triggerModal" href="" onclick="WebshopProductListPager.goToLink(event, '<?php echo $link; ?>');"><?php echo $i; ?></a></li>
                <?php 
                    }
                ?>
            <?php endfor; ?>
            <?php 
                if ($currentPage == $totalPages): 
            ?>
                <li class="page-item disabled"><span class="page-link"><?php echo trans('next.page'); ?></span></li>
            <?php 
                else: 
                    $goToPage = $currentPage + 1;
                    $listParams['currentPage'] = $goToPage; 
                    $link = WebshopService::assembleLink($listParams);
            ?>
                <li class="page-item"><a class="page-link triggerModal" href="" onclick="WebshopProductListPager.goToLink(event, '<?php echo $link; ?>');"><?php echo trans('next.page'); ?></a></li>
            <?php 
                endif; 
            ?>
            </ul>
        </nav>
    </div>
</div>

<script>
var WebshopProductListPager = {
    goToLink: function(event, link) {
        let fullLink = '<?php echo App::getContainer()->getUrl()->getHttpDomain(); ?>/' + link;
        event.preventDefault();
        console.log(fullLink);
		Structure.handlePageSwitchBehavior();
		Structure.call(fullLink);
    }
};
</script>
<?php 
endif;
?>