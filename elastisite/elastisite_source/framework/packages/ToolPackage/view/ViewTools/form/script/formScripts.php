<?php 
// dump($data);exit;
?>
    // FormScripts
    var {{ scriptId }} = {
        getParameters: function() {
            return {
                'listPath': '<?php echo $container->getUrl()->getFullUrl(); ?>',
                'editPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/{{ formMethodPath }}',
                'responseLabelSelector': '{{ responseLabelSelector }}',
                'responseBodySelector': '{{ responseBodySelector }}'
            };
        },
        call: function(id) {
            // console.log('{{ scriptId }}.call()');
            var ajaxResponse = null;
            if (id == undefined || id === null || id === false) {
                let id = null;
            }
            var params = {{ scriptId }}.getParameters();
            var ajaxData = {};
            // console.log('formId: {{ formId }}');
            var form = $('#{{ formId }}');
            var formData = form.serialize();
<?php if ($idReferenceName): ?>
            var additionalData = {
                '<?php echo $idReferenceName; ?>': id
            };
            ajaxData = formData + '&' + $.param(additionalData);
<?php else: ?>
            ajaxData = formData;
<?php endif; ?>
            $.ajax({
                'type' : 'POST',
                'url' : params.editPath,
                'data': ajaxData,
                'async': true,
                'success': function(response) {
                    LoadingHandler.stop();
                    // console.log('{{ scriptId }}.call - success');
                    // console.log(response);
                    ElastiTools.checkResponse(response);
                    ajaxResponse = response;
                    var params = {{ scriptId }}.getParameters();
                    $(params.responseBodySelector).html(<?php echo $responseViewObjectRoute; ?>);
                    <?php if ($callbackJSFunction): ?>
                    <?php echo $callbackJSFunction; ?>
                    <?php endif; ?>
                    if (typeof(response.data.label) == 'string') {
                        $(params.responseLabelSelector).html(response.data.label);
                    }
                    if (response.data.formIsValid == true) {
                        {{ scriptId }}.saveSuccessful();
                    }
                    // console.log('ajaxResponse!!!');
                    // console.log(ajaxResponse);
                    LoadingHandler.stop();
                    return ajaxResponse;
                },
                'error': function(response, error) {
                    LoadingHandler.stop();
                    // console.log(request);
                    ElastiTools.checkResponse(response.responseText);
                },
            });
            // console.log('ajaxData:', ajaxData);
        },
        saveSuccessful: function() {
            var params = {{ scriptId }}.getParameters();
            Structure.call(params.listPath);
            console.log('Structure.call(' + params.listPath + ');');
            $('#editorModal').modal('hide');
        }
    };
