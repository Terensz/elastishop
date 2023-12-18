<script>
var Login = {
    submit: function() {
        <?php echo $callingWidgetName; ?>.call(true, 'Login');
        console.log('Calling widget: <?php echo $callingWidgetName; ?>');
    },
    callback: function(response) {
        console.log('Login.callback()');
        console.log(response);
        console.log(response.data);
        if (typeof(response.data.freshLogin) == 'boolean') {
            // console.log(response.data.freshLogin);
            if (response.data.freshLogin == true) {
                <?php if ($onSuccessScript): ?>
                    <?php echo $onSuccessScript; ?>
                <?php endif; ?>
            }
        }
    },
    logout: function(e) {
        if (e !== null) {
            e.preventDefault();
        }
        $.ajax({
            'type' : 'POST',
            'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/ajax/logout',
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
    },
    changePasswordModalOpen: function(e) {
        e.preventDefault();
        $('#editorModalBody').html('');
        $('#editorModalLabel').html('<?php echo trans('change.my.password'); ?>');
        var legalText = '';
        $.ajax({
            'type' : 'POST',
            'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/ajax/changePassword',
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
    },
    recoverPasswordModalOpen: function(e) {
        e.preventDefault();
        $('#editorModalBody').html('');
        $('#editorModalLabel').html('<?php echo trans('forgotten.password'); ?>');
        var legalText = '';
        $.ajax({
            'type' : 'POST',
            'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/ajax/forgottenPassword',
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

$(document).ready(function() {

});
</script>
