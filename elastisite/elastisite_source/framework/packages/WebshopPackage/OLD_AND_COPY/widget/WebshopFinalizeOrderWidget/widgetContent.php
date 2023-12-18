<?php 
// dump($cart);
?>

<?php 
include('framework/packages/WebshopPackage/view/Parts/TestWebshopWarning.php');
?>

<div class="widgetWrapper">
    <?php //dump($selectedPaymentMethod); ?>
    <div class="article-container">
        <div class="article-head">
            <div class="article-title"><?php echo trans('finalize.order'); ?></div>
        </div>
        <div class="article-content" style=""><?php echo $text; ?></div>

<?php if ($paymentParams['paymentId']): ?>
    <?php 
    dump($closeAllowed);
        dump($paymentParams); 
    ?>
<?php else: ?>

        <form id="WebshopPackage_orderFinalize_form" name="WebshopPackage_orderFinalize_form" action="" method="POST" style="padding-top: 20px;">
            <div class="row">
                <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                    <div class="form-group formLabel">
                        <label for="WebshopPackage_orderFinalize_paymentMethod">
                            <b><?php echo trans('payment.method'); ?></b>
                        </label>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
                    <div class="form-group">
                        <div class="input-group">
                            <select name="WebshopPackage_orderFinalize_paymentMethod" id="WebshopPackage_orderFinalize_paymentMethod" class="inputField form-control">
                                <option value="*null*">-- <?php echo trans('please.choose'); ?> --</option>
                                <?php foreach ($paymentMethods as $paymentMethod): ?>
                                <option value="<?php echo $paymentMethod['referenceName']; ?>"<?php if ($selectedPaymentMethod == $paymentMethod['referenceName']) { echo ' selected'; } ?>><?php echo $paymentMethod['displayedName']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="validationMessage error" id="WebshopPackage_orderFinalize_paymentMethod-validationMessage" style="padding-top:4px;"></div>
                    </div>
                </div>
            </div>
    <?php if ($closeAllowed): ?>
            <div class="row">
                <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                </div>
                <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
                    <div class="form-group">
                        <button name="WebshopPackage_orderFinalize_submit" id="WebshopPackage_orderFinalize_submit" type="button" class="btn btn-secondary btn-block" style="width: auto;" onclick="" value="">
                            <?php echo trans('finalize.order'); ?>
                        </button>
                    </div>
                </div>
            </div>
    <?php endif; ?>
        </form>
<?php endif; ?>
        <div class="articleFooter"></div>
    </div>
</div>

<script>
var WebshopOrderFinalize = {
    submit: function(closeRequest) {
        LoadingHandler.start();
        var form = $('#WebshopPackage_orderFinalize_form');
        var formData = form.serialize();
        var additionalData = {
            'closeRequest': closeRequest
        };
        // console.log(additionalData);
        ajaxData = formData + '&' + $.param(additionalData);
        $.ajax({
            'type' : 'POST',
            'url' : '<?php echo $httpDomain; ?>/webshop/finalizeOrderWidget',
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                $('#WebshopPackage_orderFinalize_form-container').html(response.view);
                // console.log(response.data);
                if (response.data.paymentMethodMessage != null) {
                    $('#WebshopPackage_orderFinalize_paymentMethod-validationMessage').html(response.data.paymentMethodMessage);
                }
                LoadingHandler.stop();
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
                LoadingHandler.stop();
            },
        });
    }
    // close: function() {
    //     WebshopOrderFinalize.submit(false);
    // }
};

$('document').ready(function() {
    // $('body').off('contextmenu');
    // $('body').on('contextmenu', function(e) {
    //     let targetText = window.getSelection();
    //     let targetSelected = !targetText['isCollapsed'];
    //     let selectionStart = targetText['focusOffset'];
    //     let selectionEnd = targetText['anchorOffset'];
    //     let paragraph = targetText['anchorNode'].data;
    //     let selection = paragraph.substring(selectionStart, selectionEnd);
    //     // console.log(targetText);
    //     // console.log(paragraph);
    //     console.log(selection);
    //     if (selection != '') {
    //         e.preventDefault();
    //         // ide jön az, hogy mi legyen a kijelölt szöveggel.
    //     }
    // });
    $('body').off('change', '#WebshopPackage_orderFinalize_paymentMethod');
    $('body').on('change', '#WebshopPackage_orderFinalize_paymentMethod', function() {
        WebshopOrderFinalize.submit(false);
    });

    $('body').off('click', '#WebshopPackage_orderFinalize_submit');
    $('body').on('click', '#WebshopPackage_orderFinalize_submit', function() {
        WebshopOrderFinalize.submit(true);
    });
});
</script>