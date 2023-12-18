<script>
    var EventActualityPager = {
        activeCategory: 'priorized',
        page_priorized: 1,
        page_closed: 1,
        jumpTo: function(event, category, page) {
            if (event) {
                event.preventDefault();
            }
            if (page < 1) {
                page = 1;
            }
            $('.pager-' + category + '-pageItem').removeClass('active');
            $('.pager-' + category + '-tr').hide();
            $('.pager-' + category + '-page-' + page).show();
            $('.pager-' + category + '-pageItem-' + page).addClass('active');
            if (category == 'priorized') {
                EventActualityPager.page_priorized = page;
            }
            if (category == 'closed') {
                EventActualityPager.page_closed = page;
            }
        }
    };
    var EventActualityList = {
        switchTab: function(event, category) {
            if (event) {
                event.preventDefault();
            }
            if (category == 'priorized') {
                EventActualityPager.activeCategory = 'priorized';
                // $('#EventActualityCard-list-closed').hide();
                // $('#EventActualityCard-list-priorized').show();
                EventActualityList.refreshList(null, 'priorized');
                $('.navLink-priorized').addClass('active');
                $('.navLink-closed').removeClass('active');
            }
            if (category == 'closed') {
                EventActualityPager.activeCategory = 'closed';
                // $('#EventActualityCard-list-priorized').hide();
                // $('#EventActualityCard-list-closed').show();
                EventActualityList.refreshList(null, 'closed');
                $('.navLink-priorized').removeClass('active');
                $('.navLink-closed').addClass('active');
            }
        },
        refreshList: function(event, activeCategory) {
            LoadingHandler.start();
            console.log('EventActualityList.refreshList()');
            if (event) {
                event.preventDefault();
            }
            $.ajax({
                'type' : 'POST',
                'url' : '/asc/eventActualityListView',
                'data': {
                    'page_priorized': EventActualityPager.page_priorized,
                    'page_closed': EventActualityPager.page_closed,
                    'activeCategory': activeCategory
                    // 'calendarEventActualityId': calendarEventActualityId
                },
                'async': true,
                'success': function(response) {
                    LoadingHandler.stop();
                    console.log('EventActualityList.refreshList() success');
                    console.log(response);
                    // ElastiTools.checkResponse(response);
                    // LoadingHandler.stop();
                    $('#AscScaleLister_eventActualityList_container').html(response.view);
                },
                'error': function(request, error) {
                    ElastiTools.checkResponse(request.responseText);
                },
            });
        },
        close: function(event, closeResult, calendarEventActualityId, useLoadingHandler) {
            if (event) {
                event.preventDefault();
            }
            if (useLoadingHandler) {
                LoadingHandler.start();
            }
            $.ajax({
                'type' : 'POST',
                'url' : '/asc/closeEventActuality/' + closeResult,
                'data': {
                    'calendarEventActualityId': calendarEventActualityId,
                    'page_priorized': EventActualityPager.page_priorized,
                    'page_closed': EventActualityPager.page_closed
                },
                'async': true,
                'success': function(response) {
                    console.log(response);
                    // ElastiTools.checkResponse(response);
                    LoadingHandler.stop();
                    $('#AscScaleLister_eventActualityList_container').html(response.view);
                },
                'error': function(request, error) {
                    ElastiTools.checkResponse(request.responseText);
                },
            });
        },
        reopen: function(event, calendarEventActualityId, useLoadingHandler) {
            if (event) {
                event.preventDefault();
            }
            if (useLoadingHandler) {
                LoadingHandler.start();
            }
            $.ajax({
                'type' : 'POST',
                'url' : '/asc/reopenEventActuality',
                'data': {
                    'calendarEventActualityId': calendarEventActualityId
                },
                'async': true,
                'success': function(response) {
                    console.log(response);
                    // ElastiTools.checkResponse(response);
                    LoadingHandler.stop();
                    $('#AscScaleLister_eventActualityList_container').html(response.view);
                },
                'error': function(request, error) {
                    ElastiTools.checkResponse(request.responseText);
                },
            });
        },
    };
    var ProjectTeamwork = {
        processResponse: function(response) {
            console.log('ProjectTeamwork.processResponse() - response:');
            console.log(response);
            if (response.data.callback && response.data.callback == 'newProjectTeamCallback') {
                ProjectTeamwork.newProjectTeamCallback(response);
            }
            if (response.data.callback && response.data.callback == 'editProjectTeamCallback') {
                ProjectTeamwork.editProjectTeamCallback(response);
            }
            if (response.data.callback && response.data.callback == 'newProjectTeamUserCallback') {
                ProjectTeamwork.newProjectTeamUserCallback(response);
            }
            if (response.data.callback && response.data.callback == 'editProjectTeamUserCallback') {
                ProjectTeamwork.editProjectTeamUserCallback(response);
            }
            if (response.data.callback && response.data.callback == 'newProjectTeamInviteCallback') {
                ProjectTeamwork.newProjectTeamInviteCallback(response);
            }
            if (response.data.callback && response.data.callback == 'editProjectTeamInviteCallback') {
                ProjectTeamwork.editProjectTeamInviteCallback(response);
            }

            if (response.views && typeof(response.views.ProjectTeamworkView) == 'string' && response.views.ProjectTeamworkView != 'null' && response.views.ProjectTeamworkView != '') {
                // console.log('Here comes the main content');
                $('#AscScaleBuilder_Content_container').html(response.views.ProjectTeamworkView);
                // console.log(response.views.ProjectTeamworkView);
                // $('#AscScaleBuilder_Content_container').html('alma');
            }
            if (response.views && typeof(response.views.EditProjectTeamModalView) == 'string' && response.views.EditProjectTeamModalView != 'null' && response.views.EditProjectTeamModalView != '') {
                $('#editorModalBody').html(response.views.EditProjectTeamModalView);
            }
            if (response.views && typeof(response.views.EditProjectTeamUserModalView) == 'string' && response.views.EditProjectTeamUserModalView != 'null' && response.views.EditProjectTeamUserModalView != '') {
                $('#editorModalBody').html(response.views.EditProjectTeamUserModalView);
            }
            if (response.views && typeof(response.views.EditProjectTeamInviteModalView) == 'string' && response.views.EditProjectTeamInviteModalView != 'null' && response.views.EditProjectTeamInviteModalView != '') {
                $('#editorModalBody').html(response.views.EditProjectTeamInviteModalView);
            }
        },
        callAjax: function(command, additionalData) {
            let baseData = {};
            let ajaxData = $.extend({}, baseData, additionalData);
            LoadingHandler.start();
            console.log('==========================');
            console.log('ProjectTeamwork.callAjax()');
            console.log('command: ' + command);
            console.log('ajaxData: ');
            console.log(ajaxData);
            $.ajax({
                'type' : 'POST',
                'url' : '/projectTeamwork/' + command,
                'data': ajaxData,
                'async': true,
                'success': function(response) {
                    LoadingHandler.stop();
                    ElastiTools.checkResponse(response);
                    console.log('response: ');
                    console.log(response);
                    ProjectTeamwork.processResponse(response);

                    if (response.data && typeof(response.data.error) == 'string' && response.data.error != 'null' && response.data.error != '') {
                        Structure.throwErrorToast('<?php echo trans('system.message'); ?>', response.data.error);
                    }
                    if (response.data && !response.data.error && response.data.submitted) {
                        Structure.throwToast('<?php echo trans('system.message'); ?>', '<?php echo trans('successfully.saved'); ?>');
                    }

                    console.log('==========================');
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                    console.log('==========================');
                },
            });
        },
        newProjectTeam: function(event, submitted) {
            if (event) {
                event.preventDefault();
            }

            // var form = $('#ASC_editProjectTeam_form');
            // var formData = form.serialize();
            // var additionalData = {
            //     'submitted': submitted
            // };
            // var ajaxData = formData + '&' + $.param(additionalData, true);

            var ajaxData = {};
            var additionalData = {};
            if (submitted === true || submitted === 'reloadOnly') {
                var form = $('#ASC_editProjectTeam_form');
                var formData = form.serializeArray();
                $.each(formData, function(index, field){
                    ajaxData[field.name] = field.value;
                });
            }
            if (submitted === 'reloadOnly') {
                submitted = false;
            }
            additionalData = {
                'submitted': submitted
            };
            $.extend(ajaxData, additionalData);
            ProjectTeamwork.callAjax('newProjectTeam', ajaxData)
        },
        // newProjectTeamCallback: function(response) {
        //     if (response.data.savedUnitId) {
        //         ProjectTeamwork.editProjectTeam(null, response.data.savedProjectTeamId);
        //     }
        // },
        newProjectTeamCallback: function(response) {
            if (response.data && typeof(response.data.closeModal) != 'undefined' && response.data.closeModal == true) {
                $('#editorModal').modal('hide');
            } else {
                $('#editorModalBody').html(response.view);
                $('#editorModal').modal('show');
            }
        },
        editProjectTeam: function(event, submitted, projectTeamId) {
            if (event) {
                event.preventDefault();
                // event.preventDefault();
            }

            var ajaxData = {};
            var additionalData = {};
            if (submitted === true || submitted === 'reloadOnly') {
                var form = $('#ASC_editProjectTeam_form');
                var formData = form.serializeArray();
                $.each(formData, function(index, field){
                    ajaxData[field.name] = field.value;
                });
            }
            if (submitted === 'reloadOnly') {
                submitted = false;
            }
            console.log('submitted: ', submitted);
            additionalData = {
                'submitted': submitted,
                'id': projectTeamId
            };
            $.extend(ajaxData, additionalData);
            ProjectTeamwork.callAjax('editProjectTeam', ajaxData);
        },
        editProjectTeamCallback: function(response) {
            // console.log('editProjectTeamCallback');
            if (response.data && typeof(response.data.closeModal) != 'undefined' && response.data.closeModal == true) {
                $('#editorModal').modal('hide');
            } else {
                $('#editorModalBody').html(response.view);
                $('#editorModal').modal('show');
            }
        },

        newProjectTeamUser: function(event, submitted, projectTeamId) {
            if (event) {
                event.preventDefault();
            }

            var ajaxData = {};
            var additionalData = {};
            if (submitted) {
                var form = $('#ASC_editProjectTeamUser_form');
                var formData = form.serializeArray();
                $.each(formData, function(index, field){
                    ajaxData[field.name] = field.value;
                });
            }
            additionalData = {
                'submitted': submitted,
                'projectTeamId': projectTeamId
            };
            $.extend(ajaxData, additionalData);
            ProjectTeamwork.callAjax('newProjectTeamUser', ajaxData);
        },
        newProjectTeamUserCallback: function(response) {
            if (response.data && typeof(response.data.closeModal) != 'undefined' && response.data.closeModal == true) {
                $('#editorModal').modal('hide');
            } else {
                $('#editorModalBody').html(response.view);
                $('#editorModal').modal('show');
            }
        },
        editProjectTeamUser: function(event, submitted, projectTeamUserId, projectTeamId) {
            // console.log('== editProjectTeamInvite ==');
            if (event) {
                event.preventDefault();
                // event.preventDefault();
            }

            var ajaxData = {};
            var additionalData = {};
            if (submitted) {
                var form = $('#ASC_editProjectTeamUser_form');
                var formData = form.serializeArray();
                $.each(formData, function(index, field){
                    ajaxData[field.name] = field.value;
                });
            }
            additionalData = {
                'submitted': submitted,
                'projectTeamId': projectTeamId,
                'id': projectTeamUserId
            };
            $.extend(ajaxData, additionalData);

            // console.log('formData::');
            // console.log(form);
            // console.log(formData);
            // console.log('additionalData:');
            // console.log(additionalData);
            // console.log('ajaxData::');
            // console.log(ajaxData);
            ProjectTeamwork.callAjax('editProjectTeamUser', ajaxData);
        },
        editProjectTeamUserCallback: function(response) {
            // console.log('editProjectTeamCallback');
            if (response.data && typeof(response.data.closeModal) != 'undefined' && response.data.closeModal == true) {
                $('#editorModal').modal('hide');
            } else {
                $('#editorModalBody').html(response.view);
                $('#editorModal').modal('show');
            }
        },

        newProjectTeamInvite: function(event, submitted) {
            if (event) {
                event.preventDefault();
            }

            var ajaxData = {};
            var additionalData = {};
            if (submitted) {
                var form = $('#ASC_editProjectTeamInvite_form');
                var formData = form.serializeArray();
                $.each(formData, function(index, field){
                    ajaxData[field.name] = field.value;
                });
            }
            additionalData = {
                'submitted': submitted
            };
            $.extend(ajaxData, additionalData);
            ProjectTeamwork.callAjax('newProjectTeamInvite', ajaxData);
        },
        newProjectTeamInviteCallback: function(response) {
            if (response.data && typeof(response.data.closeModal) != 'undefined' && response.data.closeModal == true) {
                $('#editorModal').modal('hide');
            } else {
                $('#editorModalBody').html(response.view);
                $('#editorModal').modal('show');
            }
        },
        editProjectTeamInvite: function(event, submitted, projectTeamInviteId) {
            // console.log('== editProjectTeamInvite ==');
            if (event) {
                event.preventDefault();
                // event.preventDefault();
            }

            var ajaxData = {};
            var additionalData = {};
            if (submitted) {
                var form = $('#ASC_editProjectTeamInvite_form');
                var formData = form.serializeArray();
                $.each(formData, function(index, field){
                    ajaxData[field.name] = field.value;
                });
            }
            additionalData = {
                'submitted': submitted,
                'id': projectTeamInviteId
            };
            $.extend(ajaxData, additionalData);

            // console.log('formData::');
            // console.log(form);
            // console.log(formData);
            // console.log('additionalData:');
            // console.log(additionalData);
            // console.log('ajaxData::');
            // console.log(ajaxData);
            ProjectTeamwork.callAjax('editProjectTeamInvite', ajaxData);
        },
        editProjectTeamInviteCallback: function(response) {
            // console.log('editProjectTeamCallback');
            if (response.data && typeof(response.data.closeModal) != 'undefined' && response.data.closeModal == true) {
                $('#editorModal').modal('hide');
            } else {
                $('#editorModalBody').html(response.view);
                $('#editorModal').modal('show');
            }
        },
        // inviteUserModal: function(event, label) {
        //     if (event) {
        //         event.preventDefault();
        //     }
        //     $('#editorModalBody').html('');
        //     $('#editorModalLabel').html(label);
        //     var legalText = '';
        //     $.ajax({
        //         'type' : 'POST',
        //         'url' : '/asc/inviteUser/modal',
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
    var AscScaleBuilder = {
        hideOrShowPrimarySubjectBar: function() {
            var element = $('#AscScaleBuilder_PrimarySubjectBar_container');
            var screenWidth = $(window).width();

            if (screenWidth <= 991) {
                element.removeClass('show');
            } else {
                element.addClass('show');
            }
        },
        processResponse: function(response) {
            // console.log(response);

            if (response.data.callback && response.data.callback == 'addUnitCallback') {
                AscScaleBuilder.addUnitCallback(response);
            }
            if (response.data.callback && response.data.callback == 'editUnitCallback') {
                AscScaleBuilder.editUnitCallback(response);
            }
            if (response.data.callback && response.data.callback == 'addJuxtaposedSubjectCallback') {
                AscScaleBuilder.addJuxtaposedSubjectCallback(response);
            }
            if (response.data.callback && response.data.callback == 'moveUnitCallback') {
                AscScaleBuilder.moveUnitCallback(response);
            }
            if (response.data.callback && response.data.callback == 'deleteUnitCallback') {
                AscScaleBuilder.deleteUnitCallback(response);
            }
            if (response.data.callback && response.data.callback == 'applySettingCallback') {
                AscScaleBuilder.applySettingCallback(response);
            }

            if (response.views && typeof(response.views.ControlPanelView) == 'string' && response.views.ControlPanelView != 'null' && response.views.ControlPanelView != '') {
                $('#AscScaleBuilder_ControlPanel_container').html(response.views.ControlPanelView);
            }
            if (response.views && typeof(response.views.PrimarySubjectBarView) == 'string' && response.views.PrimarySubjectBarView != 'null' && response.views.PrimarySubjectBarView != '') {
                $('#AscScaleBuilder_PrimarySubjectBar_container').html(response.views.PrimarySubjectBarView);
            }
            if (response.views && typeof(response.views.UnitBuilderView) == 'string' && response.views.UnitBuilderView != 'null' && response.views.UnitBuilderView != '') {
                $('#AscScaleBuilder_Content_container').html(response.views.UnitBuilderView);
            }

            if (response.data && typeof(response.data.modalLabel) != 'undefined') {
                $('#editorModalLabel').html(response.data.modalLabel);
            }

            if (response.data && typeof(response.data.closeModal) != 'undefined' && response.data.closeModal == true) {
                $('#editorModal').modal('hide');
            }
            // if (response.views && typeof(response.views.othersScaleListView) == 'string' && response.views.othersScaleListView != 'null' && response.views.othersScaleListView != '') {
            //     $('#AscScaleLister_othersScaleList_container').html(response.views.othersScaleListView);
            // }
            // if (response.views && typeof(response.views.controlPanelView) == 'string' && response.views.controlPanelView != 'null' && response.views.controlPanelView != '') {
            //     $('#AscScaleLister_controlPanel_container').html(response.views.controlPanelView);
            // }
            // if (response.views && typeof(response.views.newView) == 'string' && response.views.newView != 'null' && response.views.newView != '') {
            //     $('#editorModalBody').html(response.views.newView);
            //     $('#editorModal').modal('show');
            // }
            // if (response.views && typeof(response.views.editView) == 'string' && response.views.editView != 'null' && response.views.editView != '') {
            //     $('#editorModalBody').html(response.views.editView);
            //     $('#editorModal').modal('show');
            // }
            // if (response.data.label && typeof(response.data.label) == 'string' && response.data.label != 'null' && response.data.label != '') {
            //     $('#editorModalLabel').html(response.data.label);
            // }
            // if (response.data.closeModal == 'true') {
            //     $('#editorModal').modal('hide');
            // }

            LoadingHandler.stop();
        },
        callAjax: function(command, additionalData) {
            let baseData = {};
            let ajaxData = $.extend({}, baseData, additionalData);
            LoadingHandler.start();
            console.log('==========================');
            console.log('AscScaleBuilder.callAjax()');
            console.log('command: ' + command);
            console.log('ajaxData: ');
            console.log(ajaxData);
            $.ajax({
                'type' : 'POST',
                'url' : '/asc/scaleBuilder/' + command,
                'data': ajaxData,
                'async': true,
                'success': function(response) {
                    LoadingHandler.stop();
                    ElastiTools.checkResponse(response);
                    console.log('response: ');
                    console.log(response);
                    AscScaleBuilder.processResponse(response);

                    if (response.data && typeof(response.data.error) == 'string' && response.data.error != 'null' && response.data.error != '') {
                        Structure.throwErrorToast('<?php echo trans('system.message'); ?>', response.data.error);
                    }
                    if (response.data && !response.data.error && response.data.submitted) {
                        Structure.throwToast('<?php echo trans('system.message'); ?>', '<?php echo trans('successfully.saved'); ?>');
                    }

                    console.log('==========================');
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                    console.log('==========================');
                },
            });
        },
        refreshList: function() {
            console.log('refreshList!');
            var pathname = window.location.pathname;
            console.log('pathname: ' + pathname);
            if (pathname == '/asc/dashboard') {
                EventActualityList.refreshList(null, null);
            } else {
                Structure.call();
            }
            // Structure.call();
        },
        jumpToSubject: function(event, scaleId, subject) {
            if (event) {
                event.preventDefault();
            }
            Structure.call('/asc/scaleBuilder/scale/' + scaleId + '/subject/' + subject);
        },
        listScales: function() {
            AscScaleBuilder.callAjax('listScales', {})
        },
        addUnit: function(event, scaleId, subject, parentId) {
            if (event) {
                event.preventDefault();
            }
            
            // console.log('AscScaleBuilder.addUnit');
            AscScaleBuilder.callAjax('addUnit', {
                'subject': subject,
                'parentId': parentId
            })
        },
        addUnitCallback: function(response) {
            if (response.data.savedUnitId) {
                AscScaleBuilder.editUnit(null, null, response.data.savedUnitId);
            }
        },
        editUnit: function(event, scaleId, unitId) {
            if (event) {
                event.preventDefault();
                // event.preventDefault();
            }
            // return true;
            AscScaleBuilder.callAjax('editUnit', {
                'scale': scaleId,
                'unitId': unitId,
                'submitted': false
            })
        },
        editUnitSubmit: function(unitId) {
            $('#AscScaleBuilder_editUnit_description').val(CKEDITOR.instances.AscScaleBuilder_editUnit_description.getData());

            AscScaleBuilder.callAjax('editUnit', {
                'unitId': unitId,
                'submitted': true,
                'title': $('#AscScaleBuilder_editUnit_title').val(),
                'description': $('#AscScaleBuilder_editUnit_description').val(),
                'responsible': $('#AscScaleBuilder_editUnit_responsible').val(),
                'administrationStance': $('#AscScaleBuilder_editUnit_administrationStance').val(),
                'status': $('#AscScaleBuilder_editUnit_status').val(),

                'removeCalendarEvent': $('.dueEvent-container').is(':hidden') ? true : false,

                'frequencyType': $('#AscScaleBuilder_editUnit_frequencyType').val(),
                'entireDay': $('#AscScaleBuilder_editUnit_entireDay').is(':checked') ? true : false,
                'recurrenceInterval': $('#AscScaleBuilder_editUnit_recurrenceInterval').val(),
                'recurrenceUnit': $('#AscScaleBuilder_editUnit_recurrenceUnit').val(),

                'dueDate': $('#AscScaleBuilder_editUnit_dueDate').val(),
                'dueTimeHours': $('#AscScaleBuilder_editUnit_dueTimeHours').val(),
                'dueTimeMinutes': $('#AscScaleBuilder_editUnit_dueTimeMinutes').val(),

                'recurrenceDayMon': $('#AscScaleBuilder_editUnit_recurrenceDayMon').attr('data-selected'),
                'recurrenceDayTue': $('#AscScaleBuilder_editUnit_recurrenceDayTue').attr('data-selected'),
                'recurrenceDayWed': $('#AscScaleBuilder_editUnit_recurrenceDayWed').attr('data-selected'),
                'recurrenceDayThu': $('#AscScaleBuilder_editUnit_recurrenceDayThu').attr('data-selected'),
                'recurrenceDayFri': $('#AscScaleBuilder_editUnit_recurrenceDayFri').attr('data-selected'),
                'recurrenceDaySat': $('#AscScaleBuilder_editUnit_recurrenceDaySat').attr('data-selected'),
                'recurrenceDaySun': $('#AscScaleBuilder_editUnit_recurrenceDaySun').attr('data-selected')
            });
            AscScaleBuilder.refreshList();
        },
        editUnitCallback: function(response) {
            // console.log('editUnitCallback');
            $('#editorModalBody').html(response.view);
            $('#editorModal').modal('show');
            // $('.UnitBuilder-UnitWrapper-placeholder').hide();
        },
        addJuxtaposedSubject: function() {
            AscScaleBuilder.callAjax('addJuxtaposedSubject', {})
        },
        addJuxtaposedSubjectCallback: function(response) {
            console.log('addJuxtaposedSubjectCallback!');
            $('#editorModal').modal('show');
            $('#editorModalBody').html(response.view);
        },
        // loadEditor: function() {

        // },
        moveUnit: function(movedUnitId, targetSubject, targetParentType, targetParentId, targetUnitId, aheadOrBehind) {
            var ajaxData = {
                'unitId': movedUnitId,
                'subject': targetSubject,
                'parentType': targetParentType,
                'parentId': targetParentId,
                'targetUnitId': targetUnitId,
                'aheadOrBehind': aheadOrBehind
            };
            console.log('moveUnit ajaxData: ');
            console.log(ajaxData);

            AscScaleBuilder.callAjax('moveUnit', ajaxData);
        },
        moveUnitTriggerSortOnlyError: function(response) {
            Structure.throwErrorToast('<?php echo trans('error'); ?>', '<?php echo trans('elements.containing.other.elements.can.only.be.sorted'); ?>');
            Structure.call();
        },
        moveUnitCallback: function(response) {
            if (response.data.errorMessage != null) {
                Structure.throwErrorToast('<?php echo trans('error'); ?>', response.data.errorMessage);
            } else {
                Structure.throwToast('<?php echo trans('system.message'); ?>', '<?php echo trans('move.successful'); ?>');
            }
        },
        initDeleteUnit: function(event, id, title) {
            if (event) {
                event.preventDefault();
            }
            
            if (id == undefined || id === null || id === false) {
                return false;
            }
            let rawText = '<?php echo trans('deleting.element').': <br> <b>[title]</b> <br>'.trans('are.you.sure'); ?>';
            let text = rawText.replace('[title]', title);
            $('#confirmModalBody').html(text);
            $('#confirmModalConfirm').attr('onClick', "AscScaleBuilder.deleteUnitConfirmed(" + id + ");");
            $('#confirmModal').modal('show');
        },
        deleteUnitConfirmed: function(id) {
            AscScaleBuilder.callAjax('deleteUnit', {
                'id': id
            })
            $('#confirmModal').modal('hide');
        },
        deleteUnitCallback: function(response) {
            Structure.call();
        },
        applySetting: function(data) {
            AscScaleBuilder.callAjax('applySetting', data);
        },
        applySettingCallback: function(response) {
            Structure.call();
        },
        triggerFullScreen: function() {
            var container = $('#AdminScaleBuilder-container');
            container.css('z-index', '1000');
            container.css('position', 'fixed');
            container.css('top', '10px');
            container.css('bottom', '10px');
            container.css('left', '10px');
            container.css('right', '10px');
            container.css('height', window.innerHeight - 20 + 'px'); // Képernyő magasságából levonjuk a padding értékét
            container.css('width', window.innerWidth - 20 + 'px'); // Képernyő szélességéből levonjuk a padding értékét
            container.css('overflow', 'auto');

            $('.icon_fullscreen').hide();
            $('.icon_exit_fullscreen').show();

            $(document).on('keydown', function(event) {
                if (event.keyCode === 27) { // Escape billentyu
                    AscScaleBuilder.triggerExitFullScreen();
                }
            });
        },
        triggerExitFullScreen: function() {
            Structure.call();
            LoadingHandler.stop();
        },
    };
</script>