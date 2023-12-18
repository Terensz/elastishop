<div>
    <div class="row tabs">
        <div href="" data-tabid="editView" onclick="EditSurveyModal.switchTab(event, 'editView');" class="col-lg-6 editSurveyTab editSurveyTab-editView tab-active doNotTriggerHref">
            <a class="doNotTriggerHref" href=""><?php echo trans('edit.survey'); ?></a>
        </div>
        <?php if ($form->getEntity()->getId()): ?>
        <div href="" data-tabid="answersView" onclick="EditSurveyModal.switchTab(event, 'answersView');" class="col-lg-6 editSurveyTab editSurveyTab-answersView tab-inactive doNotTriggerHref">
            <a class="doNotTriggerHref" href=""><?php echo trans('view.survey.answers'); ?></a>
        </div>
        <?php else: ?>
            <div href="" data-tabid="answersView" onclick="" class="col-lg-6 editSurveyTab editSurveyTab-answersView tab-inactive doNotTriggerHref">
            <?php echo trans('view.survey.answers'); ?>
        </div>
        <?php endif; ?>
    </div>

    <div id="survey-editView-container">
        <div id="SurveyPackage_editSurvey_editFlexibleContent">
<?php 
include('editFlexibleContent.php') 
?>
        </div>
    </div>
    <div id="survey-answersView-container"></div>
</div>

<style>
.productTabs {
    padding-bottom: 20px;
    /* border-top: 1px solid #c0c0c0; */
}
.SurveyCreator_questionContainer {
    padding: 20px;
    background-color: #fafafa;
    margin-bottom: 20px;
    border: 1px solid #c0c0c0;
}
.SurveyCreator_optionContainer {
    padding: 20px;
    background-color: #e8e8e8;
    margin-bottom: 20px;
    border: 1px solid #c0c0c0;
}
</style>

<script>
    var EditSurveyModal = {
        switchTab: function(e, tabId) {
            if (e != null) {
                e.preventDefault();
            }

            $('.editSurveyTab').each(function() {
                let loopTabid = $(this).attr('data-tabid');
                // console.log('loopTabid:', loopTabid);
                if (loopTabid == tabId) {
                    $(this).addClass('tab-active');
                    $(this).removeClass('tab-inactive');
                    $('#survey-' + loopTabid + '-container').show();

                    // Custom mukodes
                    if (loopTabid == 'answersView') {
                        EditSurveyAnswersView.load('<?php echo $form->getEntity()->getId(); ?>');
                    }
                } else {
                    $(this).addClass('tab-inactive');
                    $(this).removeClass('tab-active');
                    $('#survey-' + loopTabid + '-container').hide();
                }
            });
        }
    };

    var EditSurveyAnswersView = {

        load: function(surveyId) {
            LoadingHandler.start();
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $httpDomain; ?>/admin/survey/getAnswersView',
                'data': {
                    'surveyId': surveyId
                },
                'async': true,
                'success': function(response) {
                    // console.log(response.data);
                    LoadingHandler.stop();
                    ElastiTools.checkResponse(response);
                    $('#survey-answersView-container').html(response.view);
                },
                'error': function(response, error) {
                    ElastiTools.checkResponse(response.responseText);
                },
            });
        }
    };

    var SurveyPackageEditSurveyForm = {
        getParameters: function() {
            return {
                'listPath': '<?php echo $httpDomain; ?>/admin/survey/surveys',
                'editPath': '<?php echo $httpDomain; ?>/admin/survey/editSurvey',
                'deletePath': '<?php echo $httpDomain; ?>/admin/survey/deleteSurvey',
                'responseLabelSelector': '#editorModalLabel',
                'responseBodySelector': '#editorModalBody'
            };
        },
        call: function(id) {
            console.log('SurveyPackageEditSurveyForm.call()');
            var ajaxResponse = null;
            if (id == undefined || id === null || id === false) {
                let id = null;
            }
            var params = SurveyPackageEditSurveyForm.getParameters();
            var ajaxData = {};
            var form = $('#SurveyPackage_editSurvey_form');
            var formData = form.serialize();
            var additionalData = {
                'id': id
            };
            ajaxData = formData + '&' + $.param(additionalData);

            $.ajax({
                'type' : 'POST',
                'url' : params.editPath,
                'data': ajaxData,
                'async': true,
                'success': function(response) {
                    // console.log(response.data);
                    LoadingHandler.stop();
                    ElastiTools.checkResponse(response);
                    ajaxResponse = response;
                    var params = SurveyPackageEditSurveyForm.getParameters();
                    $(params.responseBodySelector).html(response.view);

                    if (typeof(response.data.label) == 'string') {
                        $(params.responseLabelSelector).html(response.data.label);
                    }

                    if (response.data.formIsValid == true) {
                        SurveyPackageEditSurveyForm.saveSuccessful(response.data.newSaved, response.data.entityId);
                    }

                    AdminSurveysDataGrid.list(true);
                    return ajaxResponse;
                },
                'error': function(response, error) {
                    ElastiTools.checkResponse(response.responseText);
                },
            });
        },
        saveSuccessful: function(newSaved, entityId) {
            console.log('newSaved: ', newSaved);
            console.log('entityId: ', entityId);
            var params = SurveyPackageEditSurveyForm.getParameters();
            if (newSaved == true) {
                AdminSurveysDataGrid.edit(entityId);
                Structure.call(params.listPath);
            } else {
                Structure.call(params.listPath);
                $('#editorModal').modal('hide');
            }
        }
        // deleteRequest: function() {

        // }
    };

    var SurveyCreator = {
        surveyId: '<?php echo $form->getEntity()->getId(); ?>',
        loadQuestionList: function(surveyId) {
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $httpDomain; ?>/admin/survey/getQuestionList',
                'data': {
                    'surveyId': surveyId
                },
                'async': true,
                'success': function(response) {
                    // console.log(response.data);
                    LoadingHandler.stop();
                    ElastiTools.checkResponse(response);
                    $('#SurveyCreator_questionList').html(response.view);
                },
                'error': function(response, error) {
                    ElastiTools.checkResponse(response.responseText);
                },
            });
        },
        getAjaxData: function(questionId, optionId) {
            var form = $('#SurveyCreator_questionForm_' + questionId);
            formData = form.serialize();
            var additionalData = {
                'surveyId': SurveyCreator.surveyId,
                'questionId': questionId,
                'optionId': optionId
            };
            ajaxData = formData + '&' + $.param(additionalData);

            return ajaxData;
        },
        callAjaxAndRefreshQuestionList: function(command, questionId, optionId) {
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $httpDomain; ?>/admin/survey/' + command,
                'data': SurveyCreator.getAjaxData(questionId, optionId),
                'async': true,
                'success': function(response) {
                    LoadingHandler.stop();
                    ElastiTools.checkResponse(response);
                    SurveyCreator.loadQuestionList(SurveyCreator.surveyId);
                    Structure.throwToast('<?php echo trans('system.message'); ?>', '<?php echo trans('survey.saved'); ?>');
                },
                'error': function(response, error) {
                    ElastiTools.checkResponse(response.responseText);
                },
            });
        },
        addQuestion: function() {
            return SurveyCreator.callAjaxAndRefreshQuestionList('addQuestion', null, null);
        },
        saveQuestion: function(questionId) {
            return SurveyCreator.callAjaxAndRefreshQuestionList('saveQuestion', questionId, null);
        },
        removeQuestion: function(questionId) {
            return SurveyCreator.callAjaxAndRefreshQuestionList('removeQuestion', questionId, null);
        },
        addOption: function(questionId) {
            return SurveyCreator.callAjaxAndRefreshQuestionList('addOption', questionId, null);
        },
        removeOption: function(questionId, optionId) {
            return SurveyCreator.callAjaxAndRefreshQuestionList('removeOption', questionId, optionId);
        }
    }

    $('document').ready(function() {
<?php if ($form->getEntity()->getId()): ?>
        SurveyCreator.loadQuestionList('<?php echo $form->getEntity()->getId(); ?>');
<?php endif; ?>
        $('body').off('change', '.SurveyCreator_questionInputType');
        $('body').on('change', '.SurveyCreator_questionInputType', function() {
            let questionId = $(this).attr('data-questionid');
            if ($(this).val() == 'text') {
                $('#SurveyCreator_optionContainer_' + questionId).hide();
            } else {
                $('#SurveyCreator_optionContainer_' + questionId).show();
            }
            // SurveyCreator.questionInputType(questionId);
        });
    });

</script>