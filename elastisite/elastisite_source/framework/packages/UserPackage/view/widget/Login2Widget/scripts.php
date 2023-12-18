<script>
var Login2Widget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/widget/Login2Widget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('Login2Widget'); ?>'
        };
    },
    call: function(isSubmitted) {
        var params = Login2Widget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#Login2Widget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // console.log(response);
                ElastiTools.checkResponse(response);
                var params = Login2Widget.getParameters();
                $(params.responseSelector).html(response.view);
                console.log(response);
                if (!response) {
                    console.log('!response');
                    return ;
                }
                if (response.data.freshLogin == true) {
                    Structure.call(window.location.href, true);
                    Structure.loadCPScripts();
                }
            },
            'error': function(request, error) {
                ElastiTools.checkResponse(request.responseText);
            },
        });
    },
    submit: function(event) {
        if (event) {
            event.preventDefault();
        }
        console.log('Login2Widget.submit()');
        Login2Widget.call(true);
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
                Structure.call(window.location.href, true);
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
    <?php if ($container->isAjax()) { ?>
    // Login2Widget.call(false);
    // console.log('Login2Widget docready');
    // AdminUsersWidget.call(false);
    Structure.loadWidget('Login2Widget');
    <?php } ?>
});
</script>
