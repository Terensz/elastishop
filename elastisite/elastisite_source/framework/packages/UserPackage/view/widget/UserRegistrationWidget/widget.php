<?php

?>

<?php if ($container->packageInstalled('WebshopPackage')): ?>
<?php
$container->setService('WebshopPackage/service/WebshopService');
$webshopService = $container->getService('WebshopService');
?> 
    <?php if ($webshopService::getSetting('WebshopPackage_onlyRegistratedUsersCanCheckout') == false): ?>
    <div class="widgetWrapper">
        <div id="userRegistrationInfoContainer">
            <div class="article-title">
                <?php echo trans('user.registration.info.title'); ?>
            </div>
            <div class="article-teaser"><?php echo trans('user.registration.info.reg.not.necessary'); ?></div>
        </div>
    </div>
    <?php endif; ?>
<?php endif; ?>

<div class="widgetWrapper" id="userRegistrationFormContainer">
    <div id="UserRegistrationWidget_content"><?php include('framework/packages/UserPackage/view/widget/UserRegistrationWidget/form.php'); ?></div>
    <div class="row">
        <div class="col-sm-3 col-md-3 col-lg-3">
        </div>
        <div class="col-sm-9 col-md-9 col-lg-9">
            <div id="userRegistrationSubmitContainer" style="display: inline;">
                <div class="form-group">
                    <button id="UserPackage_userRegistration_submit" title="<?php echo trans('accept.terms.to.activate'); ?>" disabled="disabled" style="width: 200px;"
                        type="button" class="btn btn-secondary btn-block"
                        onclick="UserRegistrationForm.save();"><?php echo trans('save.changes'); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var UserRegistrationForm = {
    getParameters: function() {
        return {
            'editMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/user/registration/widget',
            'responseSelector': '#UserRegistrationWidget_content'
        };
    },
    legalModalOpen: function(e) {
        e.preventDefault();
        $('#editorModalBody').html('');
        $('#editorModalLabel').html('<?php echo trans('terms.and.conditions'); ?>');
        var legalText = '<div id="userRegistrationLegalTextContainer" style="display: none;">\
            <div id="legalText"><?php echo $legalText; ?></div>\
            <div class="legalTextLink">\
                <a href="" onclick="UserRegistrationForm.legalModalClose(event);"><?php echo trans('accept.terms.and.conditions'); ?></a>\
            </div>\
        </div>';
        $('#editorModalBody').html(legalText);
        $('#userRegistrationLegalTextContainer').css('display', 'inline');
        // $('#UserPackage_userRegistration_submit').css('disabled', 'false');
        $('#editorModal').modal('show');
    },
    legalModalClose: function(e) {
        if (typeof(e) != 'undefined') {
            e.preventDefault();
        }
        $('#UserPackage_userRegistration_legalText').val($('#legalText').html());
        // $('#userRegistrationSubmitContainer').css('display', 'inline');
        $('#UserPackage_userRegistration_submit').prop('disabled', false);
        $('#UserPackage_userRegistration_submit').removeAttr('title');
        $('#pleaseAcceptTerms').hide();
        $('#editorModal').modal('hide');
    },
    save: function(id) {
        if (id == undefined || id === null || id === false) {
            var id = null;
        }
        UserRegistrationForm.call(id);
    },
    call: function(id) {
        var params = UserRegistrationForm.getParameters();
        var ajaxData = {};
        var form = $('#UserPackage_userRegistration_form');
        var formData = form.serialize();
        var additionalData = {
            'id': id
        };
        ajaxData = formData + '&' + $.param(additionalData);
        $.ajax({
            'type' : 'POST',
            'url' : params.editMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                $('#UserRegistrationWidget_content').html('');
                ElastiTools.checkResponse(response);
                var params = UserRegistrationForm.getParameters();
                $(params.responseSelector).html(response.view);
                FormValidator.displayErrors('#UserPackage_userRegistration_form', response.data.messages);
                // if (response.data.formIsValid === true) {
                //     Structure.update();
                //     $('#editorModal').modal('hide');
                //     $('body').removeClass('modal-open');
                //     $('.modal-backdrop').remove();
                // }
            },
            'error': function(response, error) {
                // console.log(request);
                ElastiTools.checkResponse(response.responseText);
            },
        });
    },
    saveSuccessful: function() {
        Structure.update();
        $('#editorModal').modal('hide');
    }
};

$('document').ready(function() {
    $('body').on('click', '.triggerModal', function (e) {
        e.preventDefault();
    });
});
</script>
