<?php
// dump($form);
?>


<div class="widgetWrapper" id="userRegistrationFormContainer">
    <div class="article-title">
        <?php echo trans('webshop.checkout.user.registration.title'); ?>
    </div>

    <div class="widgetWrapper-info">
        <?php echo trans('webshop.checkout.user.registration.info'); ?>
    </div>

    <div id="UserRegistrationWidget_content">
        <?php include('framework/packages/UserPackage/view/widget/UserRegistrationWidget/form.php'); ?>
    </div>
    <div class="row">
        <div class="col-sm-5 col-md-5 col-lg-5">
        </div>
        <div class="col-sm-7 col-md-7 col-lg-7">
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
            'editMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/webshop/checkout/registration',
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
        LoadingHandler.start();
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
                LoadingHandler.stop();
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

$('body').on('click', '.triggerModal', function (e) {
    e.preventDefault();
});

// var FormTester = {
//     start: function() {
//         $('#UserPackage_userRegistration_submit').attr('disabled', false);
//         $('#UserPackage_userRegistration_Person_name').val('TESZT János');
//         $('#UserPackage_userRegistration_Person_username').val('tesztjano' + Math.floor(Math.random() * 1099));
//         $('#UserPackage_userRegistration_Person_password').val('Alma1234');
//         $('#UserPackage_userRegistration_Person_retypedPassword').val('Alma1234');
//         $('#UserPackage_userRegistration_Person_email').val('t.e.rencecleric@gmail.com');
//         $('#UserPackage_userRegistration_Person_mobile').val('+36705150551');
//         $('#UserPackage_userRegistration_NewsletterSubscription_subscribed').val('*true*');
//         $('#UserPackage_userRegistration_Person_Address_0_Country_country').val('348');
//         $('#UserPackage_userRegistration_Person_Address_0_city').val('Almaváros');
//         $('#UserPackage_userRegistration_Person_Address_0_zipCode').val('9999');
//         $('#UserPackage_userRegistration_Person_Address_0_street').val('Alma');
//         $('#UserPackage_userRegistration_Person_Address_0_streetSuffix').val('utca');
//         $('#UserPackage_userRegistration_Person_Address_0_houseNumber').val('2323');
//     }
// };
</script>
