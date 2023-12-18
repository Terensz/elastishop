<style>
.webshopTriggerProductInfo {
    cursor: pointer;
}
</style>
<?php 
// dump(get_defined_vars());
?>
<?php if ($grantedViewProjectAdminContent): ?>
<script src="/public_folder/asset/TextareaEditor/TextareaEditor.js"></script>
<script src="/public_folder/plugin/nicEdit/nicEdit.js"></script>
<?php endif; ?>

<div class="modal fade" id="productInfoModal" tabindex="-1" role="dialog" aria-labelledby="productInfoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="productInfoModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div id="productInfoModalBody" class="modal-body"></div>
      <!-- <div class="modal-footer">
        <button id="productInfoModalClose" type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo trans('close'); ?></button>
        <button id="productInfoModalConfirm" type="button" class="btn btn-primary"><?php echo trans('confirm'); ?></button>
      </div> -->
    </div>
  </div>
</div>

<?php 
include('framework/packages/WebshopPackage/view/Parts/TestWebshopWarning.php');
?>

<form id="webshop_search_form" name="webshop_search_form">

<div class="widgetWrapper">
    <div class="input-group">
        <input style="height: 52px;" type="text" class="form-control inputField enterSubmits" id="webshop_search_term" name="webshop_search_term"
            placeholder="<?php echo trans('search.in.all.products'); ?>" value="<?php echo $searchString; ?>">
        <div class="input-group-append">
            <button id="webshop_search_submit" class="btn btn-info" style="background-color: #e0862c; border: 1px solid #dd5b2b;" type="button">
                <img style="border: 0px;" src="<?php echo $httpDomain; ?>/public_folder/plugin/Bootstrap-icons/search.svg">
            </button>
        </div>
    </div>

    <div style="display: none;">
        <a id="webshopSearchLink" class="ajaxCallerLink" href=""></a>
    </div>
</div>

<div class="row">

<?php if ($specialCategorySlugKey != 'all_products' && !empty($categoryName)): ?>
        <div class="tagFrame">
            <div class="tag" style="padding-top: 2px !important; margin-top: 0px !important;">
                <table style="width: 100%">
                    <tr>
                        <td>
                            <div class="input-groups" style="padding-top: 0px !important; margin-top: 0px !important;">
                                <div class="form-groups" style="padding-top: 0px !important; margin-top: 0px !important;">
                                    <select style="height: 41px; " name="webshop_search_category" id="webshop_search_category" class="form-control inputField">
                                        <!-- <option value="searchInAll"><?php echo trans('search.in.all.products'); ?></option>
                                        <option value="searchInCategory-<?php echo $localeRequest; ?>-<?php echo $categorySlug; ?>"><?php echo trans('search.in.category').' ('.$categoryName.')'; ?></option> -->
                                        <option value="<?php echo $listAllCategorySlug; ?>"<?php echo $categoryFilter == 'all' ? ' selected' : ''; ?>><?php echo trans('search.in.all.products'); ?></option>
                                        <option value="<?php echo $categorySlug; ?>"<?php echo $categoryFilter == 'category' ? ' selected' : ''; ?>><?php echo trans('search.in.category').' ('.$categoryName.')'; ?></option>
                                    </select>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
<?php else: ?>
        <div class="tagFrame">
            <div class="tag">
                <table style="width: 100%">
                    <tr>
                        <td>
                            <?php echo trans('search.in.all.products'); ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
<?php endif; ?>
</form>

<?php 
        /**
         * Here we make a tag of:
         *  - if we have a product category: that.
         *  - if we are on the homepage, the tag will be decided by the WebshopRequestService::getProcessedRequestData()['WebshopPackage_homepageListType']. In that case we won't make X to close this tag. (Nowhere to fallback from the homepage)
        */
        // if ($productCategory): 
        if ($specialCategoryTransRef):
?>
        <div class="tagFrame">
            <div class="tag">
                <table style="width: 100%">
                    <tr>
                        <td>
                        <?php echo trans($specialCategoryTransRef); ?>
                        </td>
<?php if ($specialCategorySlugKey != 'all_products'): ?>
                        <td style="width: 30px; text-align: center">
                            <?php 
                                // echo $listAllLink; 
                            ?>
                            <a class="ajaxCallerLink" href="<?php echo $httpDomain.'/'.$listAllLink; ?>">X</a>
                        </td>
<?php endif; ?>
                    </tr>
                </table>
            </div>
        </div>
<?php 
        elseif ($categoryName):
?>
        <div class="tagFrame">
            <div class="tag">
                <table style="width: 100%">
                    <tr>
                        <td>
                        <?php echo $categoryName; ?>
                        </td>
                        <td style="width: 30px; text-align: center">
                            <?php 
                                // echo $linkWithoutCategory; 
                            ?>
                            <a class="ajaxCallerLink" href="<?php echo $httpDomain.'/'.$linkWithoutCategory; ?>">X</a>
                        </td>
                    </tr>
                </table>
                <!-- <span class="tag2 align-middle"><?php echo trans('category'); ?></span> -->
                <!-- <?php echo trans('product.category'); ?> -->
                <!-- <?php echo $categoryName; ?> -->
            </div>
        </div>
<?php 
        endif;
?>
    <?php if ($searchString): ?>
        <div class="tagFrame">
            <div class="tag">
                <table style="width: 100%">
                    <tr>
                        <td>
                        <?php echo $searchString; ?>
                        </td>
                        <td style="width: 30px; text-align: center">
                            <?php 
                                // echo $linkWithoutSearch; 
                            ?>
                            <a class="ajaxCallerLink" href="<?php echo $httpDomain.'/'.$linkWithoutSearch; ?>">X</a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    <?php endif ?>
</div>

<div id="webshopProductList-pager"><?php include('pager.php'); ?></div>

<div id="webshopProductList"><?php echo $renderedWebshopProductList; ?></div>

<div id="webshopProductList-pager"><?php include('pager.php'); ?></div>

<div style="margin-bottom: 20px;"></div>

<?php 
// dump($listAllCategorySlug);
include('framework/packages/WebshopPackage/view/widget/WebshopProductListWidget/widgetJS.php'); 
?>