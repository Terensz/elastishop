<form name="ElastiSite_editContentEditorUnit_form" id="ElastiSite_editContentEditorUnit_form" method="POST" action="" enctype="multipart/form-data">
    <!-- <input name="contentEditorUnitId" id="contentEditorUnitId" type="hidden" value="<?php echo $form->getEntity()->getId(); ?>"> -->
<?php include('modalUnitFlex.php'); ?>
</form>

<script>    // FormScripts
    var ElastiSiteEditContentEditorUnitForm = {
        getParameters: function() {
            return {
                'editPath': '<?php echo $httpDomain; ?>/ContentEditorWidget/editor/editContentEditorUnit/form',
                'responseLabelSelector': '#editorModalLabel',
                'responseBodySelector': '#ElastiSite_editContentEditorUnit_form'
            };
        },
        call: function(id) {
            // console.log('ElastiSiteEditContentEditorUnitForm.call()');
            var ajaxResponse = null;
            if (id == undefined || id === null || id === false) {
                let id = null;
            }
            var params = ElastiSiteEditContentEditorUnitForm.getParameters();
            var ajaxData = {};
            //console.log('formId: ElastiSite_editContentEditorUnit_form');
            var form = $('#ElastiSite_editContentEditorUnit_form');
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
                    // console.log('ElastiSiteEditContentEditorUnitForm.call - success');
                    // console.log(response);
                    ElastiTools.checkResponse(response);
                    ajaxResponse = response;
                    var params = ElastiSiteEditContentEditorUnitForm.getParameters();
                    $(params.responseBodySelector).html(response.view);
                    if (typeof(response.data.label) == 'string') {
                        $(params.responseLabelSelector).html(response.data.label);
                    }
                    // console.log(response.data);
                    // if (response.data.submitted == true && response.data.formIsValid == true) {
                    if (response.data.formIsValid == true) {
                        ElastiSiteEditContentEditorUnitForm.saveSuccessful();
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
            var params = ElastiSiteEditContentEditorUnitForm.getParameters();
            // Structure.call(params.listPath);
            ContentEditorToolbar_<?php echo $contentEditorId; ?>.reload(false);
            $('#editorModal').modal('hide');
        }
    };
</script>