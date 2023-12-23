function dump(stuff) {
    console.log(stuff);
}

var ExitIntent = {
    addEvent: function (obj, evt, fn) {
        if (obj.addEventListener) {
            obj.addEventListener(evt, fn, false);
        }
        else if (obj.attachEvent) {
            obj.attachEvent("on" + evt, fn);
        }
    },
    startPopup: function () {
        console.log('ExitIntent startPopup');
    }
};
var WordExplanation = {
    getContent: function (keyText) {
        console.log(keyText);
        var content = '';
        $.ajax({
            'type': 'POST',
            'url': '/ajax/wordExplanation',
            'data': {
                'keyText': keyText
            },
            'async': false,
            'success': function (response) {
                content = response.view;
            },
            'error': function (request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
            }
        });
        return content;
    }
};
var FormValidator = {
    displayErrors: function (formSelector, messages) {
        console.log('FormValidator');
        var inputs = $(formSelector).find('.inputField');
        console.log(inputs);
        for (var i = 0; i < inputs.length; i++) {
            var id = $(inputs[i]).attr('id');
            var errorDivId = id + '-error';
            if (messages[id] != null) {
                if ($('#' + errorDivId).attr('id') != undefined) {
                    $('#' + errorDivId).remove();
                }
                var message = '<div id="' + errorDivId + '" class="fieldError text-danger">' + messages[id] + '</div>';
                $(inputs[i]).parent('.form-group').append(message);
            } else {
                $('#' + errorDivId).remove();
            }
        }
    }
};
var Favicon = {
    show: function () {
        $('#favicon').attr('href', '/accessory/favicon/' + Math.random());
    },
    reload: function () {
        Favicon.show();
    }
};
var Background = {
    call: function (backgroundEngine, backgroundTheme) {
        var url = '/background/' + backgroundEngine + '/' + backgroundTheme;
        $.ajax({
            'type': 'POST',
            'url': url,
            'data': {},
            'async': true,
            'success': function (response) {
                ElastiTools.checkResponse(response);
                $('#documentBackground').html(response.view);
            },
            'error': function (request, error) {
                ElastiTools.checkResponse(request.responseText);
                // console.log(request);
                console.log(" Can't do because: " + error);
            },
        });
    },
    onStructureChange: function (oldEngine, newEngine, oldTheme, newTheme) {
        $('body').data('backgroundEngine', newEngine);
        $('body').data('backgroundTheme', newTheme);
        Background.call(newEngine, newTheme);
    }
};
var Structure = {
    changed: false,
    loadCPScripts: function () {
        $.ajax({
            'type': 'POST',
            'url': '/cp/loadScripts',
            'data': {},
            'async': true,
            'success': function (response) {
                ElastiTools.checkResponse(response);
                $('#cp-scriptContainer').html(response.view);
                CP.load();
            },
            'error': function (request, error) {
                ElastiTools.checkResponse(request.responseText);
            },
        });
    },
    preventDefault: function (e) {
        e.preventDefault();
    },
    setBackgroundTheme: function (backgroundTheme) {
        if (backgroundTheme !== null && backgroundTheme !== undefined) {
            $('body').data('backgroundTheme', backgroundTheme);
        } else {
            $('body').data('backgroundTheme', 'general');
        }
        if (typeof (BackgroundEngine) == 'object') {
            BackgroundEngine.set($('body').data('backgroundTheme'));
        }
    },
    removeBackgroundTheme: function () {
        if (typeof (BackgroundEngine) == 'object') {
            BackgroundEngine.remove($('body').data('backgroundTheme'));
        }
    },
    triggerSoftWidgetChangeTransitions: function () {
        $('.softWidgetChangeTransition').each(function () {
            // console.log($(this).attr('id'));
            let id = $(this).attr('id');
            let width = $('#' + id).width();
            let height = $('#' + id).height();
            $('#' + id).html('<div style="width: ' + width + 'px; height: ' + height + 'px;"></div>');
        });
    },
    call: function (url, forceReload, pushUrlToHistory, resetEditorModal) {
        if (typeof forceReload == 'undefined') {
            forceReload = false;
        }
        if (typeof pushUrlToHistory == 'undefined') {
            pushUrlToHistory = true;
        }
        if (typeof resetEditorModal == 'undefined') {
            resetEditorModal = true;
        }
        // console.log('resetEditorModal: ', resetEditorModal);
        // pushUrlToHistory = (typeof pushUrlToHistory === 'undefined') ? true : pushUrlToHistory;
        if (resetEditorModal) {
            $("#editorModal").unbind("hidden.bs.modal");
            $('#editorModalLabel').html('');
            $('#editorModalBody').html('');
            $('.daterangepicker').remove();
        }

        LoadingHandler.start();
        url = (typeof url !== 'undefined') ? url : window.location;

        // console.log('url: ', url);

        if (pushUrlToHistory) {
            window.history.pushState("object or string", "Title", url);
        }

        forceReload = (typeof forceReload !== 'undefined') ? forceReload : false;
        Structure.removeBackgroundTheme();

        $.ajax({
            'type': 'POST',
            'url': url,
            'data': {},
            'async': true,
            'success': function (response) {
                // console.log(response.data);
                ElastiTools.checkResponse(response);
                document.title = response.data['title'];
                Structure.setBackgroundTheme(response.data['backgroundTheme']);
                Structure.setPageSwitchBehavior(response.data['pageSwitchBehavior']);
                Structure.setWidgetChanges(response.data['widgetChanges']);

                if ($('body').data('structureName') == response.data['structureName']) {
                    Structure.changed = false;
                } else {
                    Structure.changed = true;
                }

                // console.log('Structure.changed: ', Structure.changed);

                if (Structure.changed == false && response.data['structureName'] == 'basic2Panel') {
                    // $('#leftPanel-container').hide();
                    // $('#contentPanel-container').hide();
                    // console.log('widgetchanges: ', response.data['widgetChanges']);
                    let leftBarItemFound = false;
                    for (const [key, value] of Object.entries(response.data['widgetChanges'])) {
                        // console.log(`${key}: ${value}`);
                        if (key.slice(0, 4) == 'left') {
                            leftBarItemFound = true;
                        }
                    }

                    if (leftBarItemFound) {
                        if ($('#leftPanel-container').is(':hidden')) {
                            Structure.triggerSoftWidgetChangeTransitions();
                        }
                        $('#contentPanel-container').addClass('col-xl-9');
                        $('#contentPanel-container').addClass('col-lg-8');
                        $('#contentPanel-container').addClass('widgetRail-last');
                        $('#contentPanel-container').removeClass('col-xl-12');
                        $('#contentPanel-container').removeClass('col-lg-12');
                        $('#contentPanel-container').removeClass('widgetRail-noPadding');
                        $('#leftPanel-container').show();
                    } else {
                        if ($('#leftPanel-container').is(':visible')) {
                            Structure.triggerSoftWidgetChangeTransitions();
                        }
                        $('#leftPanel-container').hide();
                        // $('#contentPanel-container').hide();
                        $('#contentPanel-container').removeClass('col-xl-9');
                        $('#contentPanel-container').removeClass('col-lg-8');
                        $('#contentPanel-container').removeClass('widgetRail-last');
                        $('#contentPanel-container').addClass('col-xl-12');
                        $('#contentPanel-container').addClass('col-lg-12');
                        $('#contentPanel-container').addClass('widgetRail-noPadding');
                    }
                }

                $('#contentPanel-container').show();

                if (response.data['widgetChanges'] !== false && Structure.changed == false) {
                    // Structure.update(response.view);
                    $('#structureScripts').html(response.data['widgetScripts']);
                } else {
                    if ($('body').data('structureName') == response.data['structureName'] || forceReload == true) {
                        if ($('#structure').html() == '' || forceReload == true) {
                            $('#structure').html(response.view);
                        } else {
                            // Structure.update(response.view);
                        }
                        $('#structureScripts').html(response.data['widgetScripts']);
                    } else {
                        $('#structure').fadeOut(0, function () {
                            $('#structure').html(response.view);
                            $('#structure').fadeIn(0);
                            $('#structureScripts').html(response.data['widgetScripts']);
                        });
                    }
                }

                $('body').css('background-color', response.data['backgroundColor']);
                if ($('body').data('structureName') != response.data['structureName']
                    || $('body').data('skinName') != response.data['skinName']) {
                        Structure.loadSkin(response.data['skinName']);
                        // let skinCssContainerContent = '\
                        // <link href="/public_folder/skin/' + response.data['skinName'] + '/css/skin.css?v=<?php echo time(); ?>" rel="stylesheet">\
                        // ';
                        // $('#skinCssContainer').html(skinCssContainerContent);
                }
                $('body').data('structureName', response.data['structureName']);
                $('body').data('skinName', response.data['skinName']);

                Background.onStructureChange(
                    $('body').data('backgroundEngine'),
                    response.data['backgroundEngine'],
                    $('body').data('backgroundTheme'),
                    response.data['backgroundTheme']
                );

                // let sheetWidthPercent = response.data['sheetWidthPercent'] + '%';
                // let sheetMaxWidth = response.data['sheetMaxWidth'] == '' ? '' : (response.data['sheetMaxWidth'] + 'px');
                // $('.sheetWidth').css('width', sheetWidthPercent);
                // $('.sheetWidth').css('max-width', sheetMaxWidth);

                // console.log('sheetWidth: ' + sheetWidthPercent);
                // console.log('max-width: ' + sheetMaxWidth);

                Structure.stopLoadingHandlerWhenAjaxFinished();
                CookieInterface.call(false);

                // if (Structure.changed) {
                // 	CP.load();
                // }
                CP.load();
            },
            'error': function (request, error) {
                ElastiTools.checkResponse(request.responseText);
            },
        });
    },
    loadSkin: function(skinName) {
        // Structure.loadSkin(skinName);
        let skinCssContainerContent = '\
        <link href="/public_folder/skin/' + skinName + '/css/skin.css?v=<?php echo time(); ?>" rel="stylesheet">\
        ';
        $('#skinCssContainer').html(skinCssContainerContent);
    },
    pageSwitchBehavior: {},
    setPageSwitchBehavior: function (rawPageSwitchBehavior) {
        Structure.pageSwitchBehavior = rawPageSwitchBehavior;
    },
    getPageSwitchBehavior: function (widgetName) {
        for (let [key, value] of Object.entries(Structure.pageSwitchBehavior)) {
            if (key == widgetName) {
                return value;
            }
        }
        return 'refresh';
    },
    widgetChangesChecker: [],
    widgetChanges: {},
    setWidgetChanges: function (rawWidgetChanges) {
        // console.log('rawWidgetChanges:', rawWidgetChanges);
        for (let [key, value] of Object.entries(rawWidgetChanges)) {
            var checker = Structure.widgetChangesChecker;
            checker.push(key);
            var valueParts = value.split('/');
            var thisWidgetName = valueParts[valueParts.length - 1];
            Structure.widgetChanges[key] = thisWidgetName;
        }
    },
    getWidgetName: function (widgetContainerIdentifier) {
        for (let [key, value] of Object.entries(Structure.widgetChanges)) {
            if (key == widgetContainerIdentifier) {
                return value;
            }
        }
        return widgetContainerIdentifier;
    },
    getWidgetContainerIdentifier: function (widgetName) {
        for (let [key, value] of Object.entries(Structure.widgetChanges)) {
            if (value == widgetName) {
                return key;
            }
        }
        return widgetName;
    },
    update: function (structureView) {
        // return true;
        // structureView = typeof structureView === 'undefined' ? null : structureView;
        // if (structureView !== null) {
        // 	$('#structureScripts').html($(structureView).filter('#structureScripts'));
        // 	return true;
        // }
        //
        // var widgetContainers = $('#structure').find('.widgetContainer');
        // for (var i = 0; i < widgetContainers.length; i++) {
        // 	var id = $(widgetContainers[i]).attr('id');
        // 	var widgetContainerIdentifier = id.replace('widgetContainer-', '');
        // 	var widgetName = Structure.getWidgetName(widgetContainerIdentifier);
        // 	eval(widgetName + ".call()");
        // }
    },
    handlePageSwitchBehavior: function () {
        var widgetContainers = $('.widgetContainer');
        for (var i = 0; i < widgetContainers.length; i++) {
            var id = $(widgetContainers[i]).attr('id');
            var widgetContainerIdentifier = id.replace('widgetContainer-', '');
            var widgetName = Structure.getWidgetName(widgetContainerIdentifier);
            var pageSwitchBehavior = Structure.getPageSwitchBehavior(widgetName);
            if (pageSwitchBehavior == 'restore') {
                var dataId = 'widgetContent-' + widgetName;
                var saved = Structure.valsToValues(widgetName);
                if (saved === true) {
                    // Structure.throwSystemToast({title: 'system.message', body: 'page.data.stored'});
                }
                $('body').data(dataId, $('#' + id).html());
            }
        }
    },
    loadWidget: function (widgetName) {
        // console.log('LoadWidget: ' + widgetName);
        LoadingHandler.start();
        var pageSwitchBehavior = Structure.getPageSwitchBehavior(widgetName);
        var widgetContainerIdentifier = Structure.getWidgetContainerIdentifier(widgetName);
        var dataId = 'widgetContent-' + widgetName;
        if (pageSwitchBehavior == 'keep' && Structure.changed === true) {
            pageSwitchBehavior = 'refresh';
        }
        if (pageSwitchBehavior == 'refresh') {
            eval(widgetName + ".call()");
            Structure.stopLoadingHandlerWhenAjaxFinished();
            return true;
        }
        if (pageSwitchBehavior == 'restore') {
            if (typeof ($('body').data(dataId)) != 'undefined' && $('body').data(dataId) !== null) {
                $('#widgetContainer-' + widgetContainerIdentifier).html($('body').data(dataId));
                $('body').data(dataId, null);
            } else {
                eval(widgetName + ".call()");
            }
        }
    },
    loadWidget_OLD: function (widgetName) {
        LoadingHandler.start();
        var pageSwitchBehavior = Structure.getPageSwitchBehavior(widgetName);
        var widgetContainerIdentifier = Structure.getWidgetContainerIdentifier(widgetName);
        var dataId = 'widgetContent-' + widgetName;
        if (pageSwitchBehavior == 'keep' && Structure.changed === true) {
            pageSwitchBehavior = 'refresh';
        }
        if (pageSwitchBehavior == 'refresh') {
            eval(widgetName + ".call()");
            Structure.stopLoadingHandlerWhenAjaxFinished();
            return true;
        }
        if (pageSwitchBehavior == 'restore') {
            if (typeof ($('body').data(dataId)) != 'undefined' && $('body').data(dataId) !== null) {
                $('#widgetContainer-' + widgetContainerIdentifier).html($('body').data(dataId));
                $('body').data(dataId, null);
            } else {
                eval(widgetName + ".call()");
            }
        }
    },
    stopLoadingHandlerWhenAjaxFinished: function () {
        $(document).ajaxStop(function () {
            $(this).unbind("ajaxStop");
            LoadingHandler.stop();
        });
    },
    getSystemTranslation: function (key) {
        var keyArray = key.split('.');
        key = keyArray.join('_');
        return $('#systemTranslation-' + key).html();
    },
    throwSystemToast: function (obj) {
        $('#toast-title').html(Structure.getSystemTranslation(obj['title']));
        $('#toast-body').html(Structure.getSystemTranslation(obj['body']));
        $('#toast').toast({ delay: 3000 });
        $('#toast').toast('show');
    },
    throwToast: function (title, body) {
        $('#toast-body').removeClass('toast-error');
        $('#toast-body').addClass('toast-success');
        $('#toast-title').html(title);
        $('#toast-body').html(body);
        $('#toast').toast({ delay: 3000 });
        $('#toast').toast('show');
    },
    throwErrorToast: function (title, body) {
        $('#toast-body').addClass('toast-error');
        $('#toast-body').removeClass('toast-success');
        $('#toast-title').html(title);
        $('#toast-body').html(body);
        $('#toast').toast({ delay: 3000 });
        $('#toast').toast('show');
    },
    valsToValues: function (widgetName) {
        var saved = false;
        var widgetContainerIdentifier = Structure.getWidgetContainerIdentifier(widgetName);
        var widgetContainerId = 'widgetContainer-' + widgetContainerIdentifier;
        var inputs = $('#' + widgetContainerId + ' :input');
        for (var i = 0; i < inputs.length; i++) {
            if ($(inputs[i]).val() != $(inputs[i]).attr('value')) {
                saved = true
            }
            $(inputs[i]).attr('value', $(inputs[i]).val());
        }
        return saved;
    },
};

var Lightbox = {
    hide: function(event) {
        if (event) {
            event.preventDefault();
        }
        $('#defaultLightbox').remove();
    }
};

var Logout = {
    call: function(e) {
        if (e !== null) {
            e.preventDefault();
        }
        $.ajax({
            'type' : 'POST',
            'url' : '/ajax/logout',
            'data': {},
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                // Structure.call(window.location.href, true);
                Structure.call('/', true);
                Structure.loadCPScripts();
            },
            'error': function(request, error) {
                ElastiTools.checkResponse(request.responseText);
            },
        });
    }
};

var CookieInterface = {
    call: function (submitted) {
        $.ajax({
            'type': 'POST',
            'url': '/widget/CookieNoticeWidget',
            'data': {
                // 'cookieNotice_session_submit': submitted
            },
            'async': true,
            'success': function (response) {
                // console.log(response);
                ElastiTools.checkResponse(response);
                if (response.data.removeCookieNotice == true || !response.view) {
                    CookieInterface.removeCookieNotice();
                    CookieInterface.loadCookieBox();
                }
                else {
                    $('#documentBody').append(response.view);
                }
            },
            'error': function (request, error) {
                ElastiTools.checkResponse(request.responseText);
            },
        });
    },
    removeCookieNotice: function () {
        $('#cookieNoticeFrame').remove();
        $('#cookieNoticeVeil').remove();
    },
    loadCookieBox: function (submitted) {
        // console.log('CookieInterface loadCookieBox');
        // var form = $('#cookieNotice_form');
        // ajaxData = {'cookieNotice_session_submit': submitted};
        $.ajax({
            'type': 'POST',
            'url': '/widget/CookieBoxWidget',
            'data': {

            },
            'async': true,
            'success': function (response) {
                $('#cookieBox-container').html(response.view);
            },
            'error': function (request, error) {
                ElastiTools.checkResponse(request.responseText);
            },
        });
    },
    removeAllConsent: function (e) {
        LoadingHandler.start();
        console.log('e', e);
        if (e) {
            e.preventDefault();
        }
        $.ajax({
            'type': 'POST',
            'url': '/widget/CookieConsentWidget_removeAllConsent',
            'data': {
            },
            'async': true,
            'success': function (response) {
                location.reload();
            },
            'error': function (request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
                LoadingHandler.stop();
            },
        });
    },
    // removeRefusedConsent_OLD: function (e, subscriber) {
    //     LoadingHandler.start();
    //     console.log('e', e);
    //     if (e) {
    //         e.preventDefault();
    //     }
    //     $.ajax({
    //         'type': 'POST',
    //         'url': '/widget/CookieConsentWidget_removeRefusedConsent',
    //         'data': {
    //             'subscriber': subscriber
    //         },
    //         'async': true,
    //         'success': function (response) {
    //             console.log(response);
    //             LoadingHandler.stop();
    //             if (response.data.success == true) {
    //                 location.reload();
    //             }
    //             // location.reload();
    //         },
    //         'error': function (request, error) {
    //             console.log(request);
    //             console.log(" Can't do because: " + error);
    //             LoadingHandler.stop();
    //         },
    //     });
    // }
};

var LoginHandler = {
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
    callAjax: function(calledBy, ajaxUrl, additionalData) {
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
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
            },
        });
    },
    initLogin: function(event) {
        if (event) {
            event.preventDefault();
        }
        // 'widget/LoginWidget'
        LoginHandler.callAjax('initLogin', 'login/ModalLoginWidget');
    },
    initLoginCallback: function(response) {
        // 'widget/LoginWidget'
        // console.log('initLogin');
        $('#editorModal').modal('show');
        $('#editorModalBody').html(response.view);
    },
    loginSubmit: function() {
        // 'widget/LoginWidget'
        console.log('loginSubmit!!');
        var form = $('#LoginGuideWidget_loginForm');
        formData = form.serializeArray();
        var ajaxData = {};
        $.each(formData, function(index, field){
            ajaxData[field.name] = field.value;
        });
        LoginHandler.callAjax('loginSubmit', 'login/ModalLoginWidget', ajaxData);
    },
    loginSubmitCallback: function(response) {
        if (response.data && typeof(response.data.freshLogin) != 'undefined' && response.data.freshLogin == true) {
            $('#editorModal').modal('hide');
            var onSuccessRedirectToLink = null;
            if (response.data && typeof(response.data.onSuccessRedirectToLink) != 'undefined') {
                onSuccessRedirectToLink = response.data.onSuccessRedirectToLink;
                Structure.call(onSuccessRedirectToLink);
            }
            // Structure.call('/asc/dashboard');
        }
        $('#editorModalBody').html(response.view);
    },
    recoverPasswordModalOpen: function(event, label) {
        if (event) {
            event.preventDefault();
        }

        $('#editorModalBody').html('');
        $('#editorModalLabel').html(label);
        var legalText = '';
        $.ajax({
            'type' : 'POST',
            'url' : '/ajax/forgottenPassword',
            'data': {},
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                $('#editorModalBody').html(response.view);
            },
            'error': function(request, error) {
                ElastiTools.checkResponse(request.responseText);
            },
        });
        $('#editorModal').modal('show');
    }
};

var ForgottenPassword = {
    processResponse: function(response, onSuccessCallback) {
        // console.log('ForgottenPassword.processResponse()');
        dump(response);
        if (typeof this[onSuccessCallback] === 'function') {
            this[onSuccessCallback](response);
        }
    },
    callAjax: function(calledBy, ajaxUrl, onSuccessCallback) {
        // let baseData = {};
        // let ajaxData = $.extend({}, baseData, additionalData);
        LoadingHandler.start();
        var ajaxData = {};
        var form = $('#UserPackage_forgottenPassword_form');
        ajaxData = form.serialize();
        $.ajax({
            'type' : 'POST',
            'url' : ajaxUrl,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                ForgottenPassword.processResponse(response, onSuccessCallback);
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
            },
        });
    },
    sendInit: function(event) {
        if (event) {
            event.preventDefault();
        }
        ForgottenPassword.callAjax('sendInit', '/ajax/forgottenPassword/send', 'sendCallback');
    },
    sendCallback: function(response) {
        $('#editorModalBody').html(response.view);
        LoadingHandler.stop();
    },
};

var CustomRegistration = {
    processResponse: function(response, calledBy) {
        if (response.data && typeof(response.data.modalLabel) != 'undefined') {
            $('#editorModalLabel').html(response.data.modalLabel);
        }
        if (response.data && typeof(response.data.closeModal) != 'undefined' && response.data.closeModal == true) {
            $('#editorModal').modal('hide');
        }
        if (calledBy == 'init') {
            return CustomRegistration.initCallback(response);
        }
        if (calledBy == 'submit') {
            return CustomRegistration.submitCallback(response);
        }
    },
    callAjax: function(calledBy, ajaxUrl, additionalData) {
        let baseData = {};
        let ajaxData = $.extend({}, baseData, additionalData);
        LoadingHandler.start();
        $.ajax({
            'type' : 'POST',
            'url' : '/' + ajaxUrl,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // LoadingHandler.stop();
                ElastiTools.checkResponse(response);
                // console.log(response);
                CustomRegistration.processResponse(response, calledBy);
                LoadingHandler.stop();
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
            },
        });
    },
    init: function(event) {
        if (event) {
            event.preventDefault();
        }
        CustomRegistration.callAjax('init', 'widget/CustomUserRegistrationWidget');
    },
    initCallback: function(response) {
        // 'widget/LoginWidget'
        // console.log('initLogin');
        $('#editorModal').modal('show');
        $('#editorModalBody').html(response.view);
    },
    submit: function() {
        // console.log('CustomRegistration.customRegistrationSubmit');
        // LoadingHandler.start();
        // 'widget/LoginWidget'
        var form = $('#UserPackage_userRegistration_form');
        formData = form.serializeArray();
        console.log('formData:');
        console.log(formData);
        var ajaxData = {};
        $.each(formData, function(index, field){
            ajaxData[field.name] = field.value;
        });
        CustomRegistration.callAjax('submit', 'widget/CustomUserRegistrationWidget', ajaxData);
    },
    submitCallback: function(response) {
        // console.log('CustomRegistration.submitCallback');
        ElastiTools.checkResponse(response);
        if (response.data.formIsValid) {
            // Structure.throwToast("<?php echo trans('system.message'); ?>", "<?php echo trans('registration.successful'); ?>");
            $('#editorModalBody').html('');
            $('#editorModalBody').html(response.view);
        } else {
            // console.log('CustomRegistration.submitCallback - form is not valid');
            $('#CustomUserRegistrationWidget_content').html('');
            $('#CustomUserRegistrationWidget_content').html(response.view);
        }
        // FormValidator.displayErrors('#UserPackage_userRegistration_form', response.data.messages);
    }
};

function stickyMenuListener() {
    var headerPos = $('#stickyMenuStart').position();
    var stickyHeight = $('.stickyMenuDiv').outerHeight();
    var stickyMenuWedge = '<div id="stickyMenuWedge" style="height: ' + stickyHeight + 'px;"></div>';
    if (typeof (headerPos) != 'undefined') {
        var headerFromTop = headerPos['top'];
        var sheetTopPadding = '<?php echo App::getContainer()->getSkinData("sheetTopPadding"); ?>';
        if (isNaN(sheetTopPadding) === false) {
            headerFromTop = headerFromTop + parseInt(sheetTopPadding);
        }
        if (window.pageYOffset > headerFromTop) {
            $('#stickyMenuWedge').remove();
            $('.stickyMenuDiv').before($(stickyMenuWedge));
            // $('.stickyMenuDiv').addClass('sheetWidth');
            $('.stickyMenuDiv').css('width', $('.menuContainer').width());
            $('.stickyMenuDiv').addClass('sticky-menu');
            $('.stickyMenuDiv').css('z-index', 200);
        } else {
            $('#stickyMenuWedge').remove();
            // $('.stickyMenuDiv').removeClass('sheetWidth');
            $('.stickyMenuDiv').css('width', '');
            $('.stickyMenuDiv').removeClass('sticky-menu');
            $('.stickyMenuDiv').css('z-index', 220);
        }
    }
}
