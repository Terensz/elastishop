<div style="padding-top: 26px;"></div>
<div class="article-title">
    <?php echo trans('pick.up.point'); ?> *
</div>

<?php 
// dump($selectedPickUpPoint);
// dump((int)$selectedPickUpPoint === 1);
?>

<div class="row" style="padding-top: 10px;">
    <div class="col-md-12">
        <div class="form-group" style="padding-bottom: 0px; margin-bottom: 0px;">
            <div class="input-group">
                <select name="WebshopPackage_checkout_pickUpPoint" id="WebshopPackage_checkout_pickUpPoint" class="inputField form-control">

                    <option value=""<?php echo (empty($selectedPickUpPoint) ? ' selected' : ''); ?>><?php echo trans('please.choose'); ?></option>
<?php foreach ($addressData as $rowKey => $addressRowData): ?>
    <?php if (in_array($thisWebsite, $addressRowData['availableOnWebsites'])): ?>
                    <option value="<?php echo $rowKey; ?>"<?php echo ($selectedPickUpPoint == $rowKey ? ' selected' : ''); ?>><?php echo $addressRowData['fullAddress']; ?></option>
    <?php endif; ?>
<?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="validationMessage error" id="WebshopPackage_checkout_orgCountry-validationMessage">
            <?php if ($validateForm): echo $message; endif; ?>
        </div>
    </div>
</div>