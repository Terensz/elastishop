<?php 

use framework\packages\WebshopPackage\service\WebshopService;

App::getContainer()->wireService('WebshopPackage/service/WebshopService');

?>

<!-- <section class="w-100 p-4 pb-4 d-flex justify-content-center align-items-center flex-column">
    <div>
        <div class="input-group">
            <div class="form-outline">
                <input type="search" id="form1" class="form-control">
                <label class="form-label" for="form1" style="margin-left: 0px;">Search</label>
                <div class="form-notch"><div class="form-notch-leading" style="width: 9px;"></div><div class="form-notch-middle" style="width: 47.2px;"></div><div class="form-notch-trailing"></div></div>
            </div>
            <button type="button" class="btn btn-primary">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>
</section> -->

<div style="display: none;">
    <a id="webshopSearchLink" class="ajaxCallerLink" href=""></a>
</div>
<?php  
// dump($searchLinkData);
?>
<div class="card">
    <div class="card-body">
        <div class="input-group mb-0">
            <button class="btn btn-primary" type="webshop_search_submit" id="webshop_search_submit" name="webshop_search_submit" onclick="Webshop.search(event);"><?php echo trans('search'); ?></button>
            <input id="webshop_search_term" name="webshop_search_term" type="text" class="form-control" placeholder="<?php echo trans('search.in.the.webshop'); ?>" value="<?php echo $searchTerm; ?>">
            <?php if ($categorySlug && $categorySlug != WebshopService::TAG_ALL_PRODUCTS): ?>
            <select class="form-select" id="webshop_search_linkBase" name="webshop_search_linkBase">
                <option value="<?php echo $searchLinkData['searchLinkBaseAll']; ?>"><?php echo trans('search.in.all.products'); ?></option>
                <option <?php if ($isMixedSearch) { echo 'selected '; } ?>value="<?php echo $searchLinkData['searchLinkBaseCategory']; ?>"><?php echo trans('search.in.this.category'); ?></option>
            </select>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- <div class="card">
    <div class="card-body">
        <div class="input-group mb-0">
            <button class="btn btn-primary" type="button" id="button-addon1">Keresés indítása</button>
            <input type="text" class="form-control" placeholder="Keresés a webáruházban" aria-label="Recipient's username" aria-describedby="button-addon2">
        </div>
    </div>
</div> -->