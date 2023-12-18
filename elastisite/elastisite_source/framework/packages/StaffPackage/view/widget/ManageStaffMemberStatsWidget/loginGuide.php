<!-- <div class="row sheetLevel">
    <div id="contentPanel-container" class="col-md-12 col-sm-12 widgetRail col-xl-12 col-lg-12 widgetRail-noPadding">
        <div class="widgetWrapper">
            LoginGuideWidget!
        </div>
    </div>
</div> -->

<div id="LoginGuide-container" class="row sheetLevel">
    <div id="contentPanel-container" class="col-md-12 col-sm-12 widgetRail col-xl-12 col-lg-12 widgetRail-noPadding">
        <div class="widgetWrapper-off" style="padding: 10px;">

        <?php if (!App::getContainer()->getUser()->getId()): ?>
        <!-- LoginGuideWidget! -->
            <div class="contentViewer-fancyTextbox-container">
                <div onclick="LoginHandler.initLogin();" class="contentViewer-fancyTextbox">
                    <?php echo trans('i.have.my.credentials'); ?>
                </div>
            </div>
        <?php  else: ?>
            <?php echo trans('already.logged.in.as.an.administrator'); ?>
        <?php  endif; ?>
            <?php  
            // dump(App::getContainer()->getUser());
            ?>
            <!-- <div class="widgetContainer softWidgetChangeTransition" id="widgetContainer-mainContent">
                <div class="widgetWrapper" style="margin-top: 10px; margin-left: 10px; margin-right: 10px; margin-bottom: 10px;">
                    <div id="AscSampleScales_controlPanel_container">
                        LoginGuideWidget!
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</div>
<style>
    .contentViewer-fancyTextbox {
        background-color: #f8f8f8;
        width: auto !important;
        font-size: 24px;
        margin: 20px;
        padding: 20px;
        box-shadow: rgba(6, 24, 44, 0.4) 0px 0px 0px 2px, rgba(6, 24, 44, 0.65) 0px 4px 6px -1px, rgba(255, 255, 255, 0.08) 0px 1px 0px inset;
        border-radius: 12px;
        cursor: pointer;
    }
    .contentViewer-fancyTextbox:hover {
        background-color: #6b6b6b;
        color: #ffffff;
    }
    .contentViewer-fancyTextbox-container {
        max-width: 800px; /* Válaszd a megfelelő szélességet */
        margin: 0 auto;
    }
</style>

<script>
    var LoginGuide = {
        processResponse: function(response, calledBy) {
            if (response.data && typeof(response.data.modalLabel) != 'undefined') {
                $('#editorModalLabel').html(response.data.modalLabel);
            }
            if (response.data && typeof(response.data.closeModal) != 'undefined' && response.data.closeModal == true) {
                $('#editorModal').modal('hide');
            }

            if (calledBy == 'initLogin') {
                return LoginHandler.initLoginCallback(response);
            }
            if (calledBy == 'loginSubmit') {
                return LoginHandler.loginSubmitCallback(response);
            }
        },
        callAjax: function(calledBy, ajaxUrl, additionalData, onSuccessCallback) {
            let baseData = {};
            let ajaxData = $.extend({}, baseData, additionalData);
            // LoadingHandler.start();
            $.ajax({
                'type' : 'POST',
                'url' : '/' + ajaxUrl,
                'data': ajaxData,
                'async': true,
                'success': function(response) {
                    // LoadingHandler.stop();
                    ElastiTools.checkResponse(response);
                    // console.log(response);
                    LoginHandler.processResponse(response, calledBy);
                    LoadingHandler.stop();
                    if (onSuccessCallback) {
                        // window[onSuccessCallback](response);
                        eval(onSuccessCallback + "('" + JSON.stringify(response) + "')");
                    }
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                },
            });
        },
        initLogin: function() {
            // 'widget/LoginWidget'
            LoginHandler.callAjax('initLogin', 'staffMemberLogin/ModalLoginWidget', {}, null);
        },
        initLoginCallback: function(response) {
            // 'widget/LoginWidget'
            // console.log('initLogin');
            $('#editorModal').modal('show');
            $('#editorModalBody').html(response.view);
        },
        loginSubmit: function() {
            // 'widget/LoginWidget'
            var form = $('#LoginGuideWidget_loginForm');
            formData = form.serializeArray();
            var ajaxData = {};
            $.each(formData, function(index, field){
                ajaxData[field.name] = field.value;
            });
            LoginHandler.callAjax('loginSubmit', 'staffMemberLogin/ModalLoginWidget', ajaxData, null);
        },
        loginSubmitCallback: function(response) {
            if (response.data && typeof(response.data.freshLogin) != 'undefined' && response.data.freshLogin == true) {
                $('#editorModal').modal('hide');
                Structure.call('');
            }
            $('#editorModalBody').html(response.view);
        },
        // initLogout: function(e) {
        //     e.preventDefault();
        //     LoginHandler.callAjax('initLogout', 'ajax/logout', {}, 'LoginHandler.logoutCallback');
        // },
        // logoutCallback: function() {
        //     console.log('logoutCallback!!!');
        //     Structure.call('');
        // }
        // recoverPasswordModalOpen: function() {
        //     $('#editorModalBody').html('');
        //     $('#editorModalLabel').html('<?php echo trans('forgotten.password'); ?>');
        //     var legalText = '';
        //     $.ajax({
        //         'type' : 'POST',
        //         'url' : '<?php echo App::getContainer()->getUrl()->getHttpDomain(); ?>/ajax/forgottenPassword',
        //         'data': {},
        //         'async': true,
        //         'success': function(response) {
        //             ElastiTools.checkResponse(response);
        //             $('#editorModalBody').html(response.view);
        //         },
        //         'error': function(request, error) {
        //             ElastiTools.checkResponse(request.responseText);
        //         },
        //     });
        //     $('#editorModal').modal('show');
        // }
    };

    $('document').ready(function() {
        // $('#LoginGuide-container').off('click', '.contentViewer-fancyTextbox');
        // $('#LoginGuide-container').on('click', '.contentViewer-fancyTextbox', function() {
        //     console.log('click contentViewer-fancyTextbox!');
        // });
    });
</script>