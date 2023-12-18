<?php

use framework\packages\LegalPackage\entity\VisitorConsentAcceptance;

?>
<!-- <div id="cookieNotice-frame-titleRow" class="">
</div> -->
<div>
    <b><?php echo trans('this.website.uses.cookies'); ?></b>
</div>
<div class="cookieNotice-button-container" style="width: 100%; padding-top: 10px;">
    <div style="float: left; padding-right: 5px;">
        <button onclick="CookieNoticeWidget.submit(null, null, true, false);" type="button" class="btn btn-success"><?php echo trans('i.consent.all'); ?></button>
    </div>
    <div class="cookieNotice-lead" style="float: left; padding-right: 5px;">
            <button onclick="CookieNoticeWidget.moreInfo(event);" type="button" class="btn btn-info"><?php echo trans('more.info'); ?></button>
        </div>
        <div class="cookieNotice-description" style="float: left; padding-right: 5px; display: none;">
            <button onclick="CookieNoticeWidget.lessInfo(event);" type="button" class="btn btn-info"><?php echo trans('less.info'); ?></button>
        </div>
    <div class="">
        <button onclick="CookieNoticeWidget.submit(null, null, false, true);" type="button" class="btn btn-warning"><?php echo trans('i.refuse.all'); ?></button>
    </div>
</div>

<form name="cookieNotice_form" id="cookieNotice_form" action="" method="POST">
    <div class="cookieNotice-container" style="width: 100%;">
        <div style="width: 64px; float: left; margin-right: 10px; box-shadow: 0 4px 6px #424242;">
            <!-- <img style="width: 64px;" src="/cookieConsent/showLogo/general"> -->
        </div>
        <div class="cookieNotice-description" style="display: none;">
            <div class="rowSeparator-extra"></div>
            <?php echo $generalTextView; ?>
        </div>
    </div>
<?php foreach ($textViews as $subscriber => $textView): ?>
    <?php if (!in_array($subscriber, $subscribersFound)): ?>
<?php
$textViewParts = explode('<br>', $textView);
$lead = $textViewParts[0];
?>
    <div class="rowSeparator-extra"></div>
    <div class="cookieNotice-container" style="width: 100%;">
        <div style="width: 64px; float: left; margin-right: 10px; box-shadow: 0 4px 6px #424242;">
            <img style="width: 64px;" src="/cookieConsent/showLogo/<?php echo $subscriber; ?>">
        </div>
        <div class="cookieNotice-lead">
            <?php echo $lead; ?>
        </div>
        <div class="cookieNotice-description" style="display: none;">
            <?php echo $textView; ?>
        </div>
    </div>
    <div style="clear: both;"></div>
    <div class="cookieNotice-button-container cookieNotice-description" style="width: 100%; padding-top: 10px; display: none;">
        <button onclick="CookieNoticeWidget.submit('<?php echo $subscriber; ?>', '<?php echo VisitorConsentAcceptance::ACCEPTANCE_ACCEPTED; ?>', false, false);" type="button" class="btn btn-success"><?php echo trans('i.consent'); ?></button>
        <button onclick="CookieNoticeWidget.submit('<?php echo $subscriber; ?>', '<?php echo VisitorConsentAcceptance::ACCEPTANCE_REFUSED; ?>', false, false);" type="button" class="btn btn-warning"><?php echo trans('i.refuse'); ?></button>
    </div>
<?php 

?>
    <?php endif; ?>
<?php endforeach; ?>
</form>
<style>
    .rowSeparator-extra {
        height: 24px;
    }
</style>