<form name="ElastiSite_editContentEditorUnitCase_form" id="ElastiSite_editContentEditorUnitCase_form" method="POST" action="" enctype="multipart/form-data">
    <!-- <input name="contentEditorUnitCaseId" id="contentEditorUnitCaseId" type="hidden" value="<?php echo $form->getEntity()->getId(); ?>"> -->
<?php include('modalUnitCaseFlex.php'); ?>
</form>

<script>    // FormScripts
    var ElastiSiteEditContentEditorUnitCaseForm = {
        getParameters: function() {
            return {
                'editPath': '<?php echo $httpDomain; ?>/ContentEditorWidget/editor/editContentEditorUnitCase/form',
                'responseLabelSelector': '#editorModalLabel',
                'responseBodySelector': '#ElastiSite_editContentEditorUnitCase_form'
            };
        },
        call: function(id) {
            // console.log('ElastiSiteEditContentEditorUnitCaseForm.call()');
            var ajaxResponse = null;
            if (id == undefined || id === null || id === false) {
                let id = null;
            }
            var params = ElastiSiteEditContentEditorUnitCaseForm.getParameters();
            var ajaxData = {};
            //console.log('formId: ElastiSite_editContentEditorUnitCase_form');
            var form = $('#ElastiSite_editContentEditorUnitCase_form');
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
                    LoadingHandler.stop();
                    // console.log('ElastiSiteEditContentEditorUnitCaseForm.call - success');
                    // console.log(response);
                    ElastiTools.checkResponse(response);
                    ajaxResponse = response;
                    var params = ElastiSiteEditContentEditorUnitCaseForm.getParameters();
                    $(params.responseBodySelector).html(response.view);
                    if (typeof(response.data.label) == 'string') {
                        $(params.responseLabelSelector).html(response.data.label);
                    }
                    // console.log(response.data);
                    // if (response.data.submitted == true && response.data.formIsValid == true) {
                    if (response.data.formIsValid == true) {
                        ElastiSiteEditContentEditorUnitCaseForm.saveSuccessful();
                    }
                    // console.log('ajaxResponse!!!');
                    // console.log(ajaxResponse);
                    return ajaxResponse;
                },
                'error': function(response, error) {
                    // console.log(request);
                    ElastiTools.checkResponse(response.responseText);
                },
            });
            // console.log('ajaxData:', ajaxData);
        },
        saveSuccessful: function() {
            var params = ElastiSiteEditContentEditorUnitCaseForm.getParameters();
            // Structure.call(params.listPath);
            ContentEditorToolbar_<?php echo $contentEditorId; ?>.reload(false);
            $('#editorModal').modal('hide');
        }
    };
</script>