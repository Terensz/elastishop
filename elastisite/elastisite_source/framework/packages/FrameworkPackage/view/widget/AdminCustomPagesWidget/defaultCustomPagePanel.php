<!-- <div class="widgetWrapper">
<?php if ($defaultCustomPage): ?>
    <div class="grid-container">
        <div class="row grid-title-row breakLongText">
            <div class="col-12 grid-title-cell grid-title-cell-background">
                <div class="" style="padding-bottom: 4px;">
                </div>
                <div>
                <?php echo trans('route.name'); ?>
                </div>
            </div>
        </div>
        <div class="row grid-body-row breakLongText" id="AdminCustomPagesGrid_<?php echo $defaultCustomPage->getId(); ?>" data-id="<?php echo $defaultCustomPage->getId(); ?>" data-status="">
            <div onclick="AdminCustomPagesGrid.edit(event, '<?php echo $defaultCustomPage->getId(); ?>');" data-id="<?php echo $defaultCustomPage->getId(); ?>" class="col-12 grid-body-cell AdminCustomPagesGrid-<?php echo $defaultCustomPage->getId(); ?>">
            <?php echo trans('default.page'); ?>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="widgetWrapper-info">
    <?php echo trans('default.page.role'); ?>
    </div>

    <button id="AdminCustomPagesGrid_createDefault" name="AdminCustomPagesGrid_createDefault" onclick="CustomPage.createDefaultCustomPage()" type="button" class="btn btn-primary"><?php echo trans('create.default.page'); ?></button>
<?php endif; ?>
</div> -->

<?php if ($defaultCustomPage): ?>
<div class="card table-card">
    <div class="pro-scroll">
        <div class="card-body p-0">
            <div class="table-responsive">
                <div id="">
                    <table class="table table-hover m-b-0">
                        <thead>
                            <tr>
                                <th>
                                    <div style="width: 100%; margin-top: 10px;" onclick="AdminCustomPagesGrid.orderBy('routeName', 'DESC');">
                                        <?php echo trans('route.name'); ?>
                                    </div>
                                </th>                                                                                    
                                <th>
                                    <div style="width: 100%; margin-top: 10px;" onclick="AdminCustomPagesGrid.orderBy('title', 'DESC');">
                                    </div>
                                </th>
                                <th>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="3" style="cursor: pointer;" onclick="AdminCustomPagesGrid.edit(event, '<?php echo $defaultCustomPage->getId(); ?>');">
                                    <?php echo trans('default.page'); ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div> <!-- /table-responsive -->
        </div> <!-- /card-body -->
    </div>
</div>
<?php else: ?>
<div class="card">
    <div class="bg-primary text-white card-header d-flex justify-content-between align-items-center">
        <div class="card-header-textContainer">
            <h6 class="mb-0 text-white"><?php echo trans('information'); ?></h6>
        </div>
    </div>
    <div class="card-body">
        <span>
            <?php echo trans('default.page.role'); ?>
        </span>
    </div>
</div>

<div class="newItem mb-4">
    <button id="AdminCustomPagesGrid_createDefault" name="AdminCustomPagesGrid_createDefault" onclick="CustomPage.createDefaultCustomPage()" type="button" class="btn btn-primary"><?php echo trans('create.default.page'); ?></button>
</div>
<?php endif; ?>