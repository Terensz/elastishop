<?php 

// $packageNameStr = $packageName ? $packageName : '';
// $subjectStr = ($packageNameStr == '' ? '' : '_').($subject ? $subject : '');
// $formName = $packageNameStr.$subjectStr != '' ? $packageNameStr.$subjectStr.'_form' : 'undefined_form';
$gridName = ucfirst($gridName);

?>

<script>
var <?php echo $gridName; ?>GridAjaxInterface = {
    getParameters: function() {
        return {
            'searchMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/<?php echo $searchActionParamChain; ?>',
            'editMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/<?php echo $editActionParamChain; ?>',
            'deleteMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/<?php echo $deleteActionParamChain; ?>',
            'searchFormName': '<?php echo $searchFormName; ?>',
        };
    },
    save: function(id) {
        if (id == undefined || id === null || id === false) {
            var id = null;
        }
        <?php echo $onSaveReloadFunction; ?>
    },
    delete: function(id) {
        var params = <?php echo $gridName; ?>GridAjaxInterface.getParameters();
        $.ajax({
            'type' : 'POST',
            'url' : params.deleteMethodPath,
            'data': { 'id': id },
            'async': true,
            'success': function(response) {
                // console.log(params.deleteMethodPath);
                ElastiTools.checkResponse(response);
                <?php echo $deleteResponseScript; ?>
            },
            'error': function(request) {
                ElastiTools.checkResponse(request.responseText);
            },
        });
    },
    search: function(page, orderBy) {
        console.log('orderBy', orderBy);
        if (typeof(orderBy) == 'undefined') {
            if (id === null) {
                orderBy = {
                    'prop': 'id',
                    'direction': 'DESC'
                };
            } else {
                orderBy = null;
            }
        }
        var params = <?php echo $gridName; ?>GridAjaxInterface.getParameters();
        var ajaxData = {};
        var form = $('#' + params.formName);
        // console.log(params.formName);
        var formData = form.serialize();
        var additionalData = {
            'page': page,
            'orderBy': orderBy
        };
        ajaxData = formData + '&' + $.param(additionalData);
        // console.log(ajaxData);
        $.ajax({
            'type' : 'POST',
            'url' : params.searchMethodPath,
            'data': ajaxData,
            //'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                // console.log(params.searchMethodPath)
                // console.log(response);
                $(params.searchFormName).html(response.view);
                $('#editorModal').modal('hide');
            },
            'error': function(request) {
                ElastiTools.checkResponse(request.responseText);
            },
        });
    },
    call: function(id) {
        var params = <?php echo $gridName; ?>GridAjaxInterface.getParameters();
        var ajaxData = {};
        var form = $('#' + params.formName);
        // console.log(params.formName);
        var formData = form.serialize();
        var additionalData = {
            'id': id
        };
        ajaxData = formData + '&' + $.param(additionalData);
        // console.log(ajaxData);
        $.ajax({
            'type' : 'POST',
            'url' : params.editMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                var params = <?php echo $gridName; ?>GridAjaxInterface.getParameters();
                ElastiTools.fillModal(response, null);
                if (response.hasOwnProperty('data')) {
                    FormValidator.displayErrors('#' + params.formName, response.data.messages);
                    if (response.data.formIsValid === true) {
                        <?php echo $callResponseScript; ?>
                        $('#editorModal').modal('hide');
                        // $('body').removeClass('modal-open');
                        // $('.modal-backdrop').remove();
                    }
                }
            },
            'error': function(request) {
                ElastiTools.checkResponse(request.responseText);
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
</script>
