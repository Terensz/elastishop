<script>
var BackgroundEdit = {
    getParameters: function() {
        return {
            'newMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/background/new',
            'resetMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/background/reset',
            'deleteMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/background/delete',
            'saveMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/background/save'
        };
    },
    new: function(e) {
        e.preventDefault();
        $('#editorModalBody').html('');
        $('#editorModalLabel').html('');
        $('#editorModal').modal('show');
        // console.log('BackgroundEdit.new()');
        BackgroundEdit.call(null);
    },
    save: function() {
        // console.log('BackgroundEdit.save() alma');
        LoadingHandler.start();
        BackgroundEdit.call(null);
        // AdminBackgroundsWidget.call();
    },
    reset: function() {
        var params = BackgroundEdit.getParameters();
        $.ajax({
            'type' : 'POST',
            'url' : params.resetMethodPath,
            'data': $.param({}),
            'async': true,
            'success': function(response) {
                console.log('BackgroundEdit.reset()');
                BackgroundEdit.call();
            },
            'error': function(request, error) {
                ElastiTools.checkResponse(request.responseText);
            },
        });
    },
    call: function(id) {
        var params = BackgroundEdit.getParameters();
        var form = $('#BackgroundPackage_newFBSBackground_form');
        ajaxData = form.serialize();
        $.ajax({
            'type' : 'POST',
            'url' : params.newMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                console.log('BackgroundEdit.call()');
                LoadingHandler.stop();
                ElastiTools.checkResponse(response);
                $('#editorModalBody').html(response.view);
                if (response.hasOwnProperty('data')) {
                    console.log(response);
                    FormValidator.displayErrors('#BackgroundPackage_newFBSBackground_form', response.data.messages);
                    if (response.data.formIsValid === true) {
                        $('#editorModal').modal('hide');
                    }
                    if (response.data['refresh'] == true) {
                        console.log('BackgroundEdit.call() - refresh: ', response.data['refresh']);
                        AdminBackgroundsWidget.call();
                    }
                }
            },
            'error': function(request, error) {
                ElastiTools.checkResponse(request.responseText);
            },
        });
    }
};
</script>