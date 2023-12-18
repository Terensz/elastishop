<div class="widgetWrapper">
<?php
echo '<b>'.trans('all.personal search.string.must.be.full.because.encryption').'</b>';
include('framework/packages/UserPackage/view/widget/AdminUserAccountsWidget/adminUserAccountsControlPanel.php');
?>
    <div id="adminUserAccountsGrid">
    <?php echo $renderedGrid; ?>
    </div>
</div>
<?php echo $gridAjaxInterface; ?>
