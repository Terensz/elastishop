<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="card-header-textContainer">
            <h6 class="mb-0"><?php echo trans('reset.webshop.risks'); ?></h6>
        </div>
    </div>
    <div class="card-body">
        <form name="WebshopPackage_resetWebshop_form" id="WebshopPackage_resetWebshop_form" method="POST" autocomplete="off" action="" enctype="multipart/form-data">
            <div class="m-0">
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="WebshopPackage_resetWebshop_agreement" name="WebshopPackage_resetWebshop_agreement" value="1">
                    <label class="form-check-label" for="WebshopPackage_resetWebshop_agreement"><?php echo trans('i.understood.the.risk.of.resetting.webshop'); ?></label>
                </div>
                <div id="WebshopPackage_resetWebshop_agreement-error" class="fieldError text-danger"><?php if ($agreementMessage) { echo trans($agreementMessage); } ?></div>
            </div>
            <div class="m-3">
                <button id="WebshopPackage_resetWebshop_submit" type="button" class="btn btn-secondary btn-block" onclick="WebshopResetInterface.submit();"><?php echo trans('reset.webshop'); ?></button>
            </div>
        </form>
    </div>
</div>

<script>
var WebshopResetInterface = {
    getParameters: function() {
        return {
            'submitResponseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/webshop/resetWidget',
            'submitFormName': 'WebshopPackage_resetWebshop_form'
        };
    },
    submit: function() {
        LoadingHandler.start();
        var params = WebshopResetInterface.getParameters();
        var form = $('#' + params.submitFormName);
        var formData = form.serialize();
        var additionalData = {
            'submitted': true
        };
        console.log(additionalData);
        ajaxData = formData + '&' + $.param(additionalData);
        $.ajax({
            'type' : 'POST',
            'url' : params.submitResponseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                $('#widgetContainer-mainContent').html(response.view);
                LoadingHandler.stop();
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
                LoadingHandler.stop();
            },
        });
    }
};
</script>