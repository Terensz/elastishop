<script>
var EventAdminForm = {
    new: function() {
        $('#editorModalBody').html('');
        EventEdit.call(null);
        $('#editorModal').modal('show');
    },
    edit: function(eventId) {
        $('#editorModalBody').html('');
        EventEdit.call(eventId);
        $('#editorModal').modal('show');
    },
    delete: function(eventId) {
        if (eventId == undefined || eventId === null || eventId === false) {
            return false;
        }
        $('#confirmModalConfirm').attr('onClick', "EventAdminForm.deleteConfirmed(" + eventId + ");");
        $('#confirmModalBody').html('<?php echo trans('are.you.sure'); ?>');
        $('#confirmModal').modal('show');
    },
    moveUp: function(eventId) {
        if (eventId == undefined || eventId === null || eventId === false) {
            return false;
        }
        EventEdit.move(eventId, 'up');
        Structure.update();
    },
    deleteConfirmed: function(eventId) {
        EventEdit.delete(eventId);
        $('#confirmModal').modal('hide');
    }
};

var EventEdit = {
    getParameters: function() {
        return {
            'editMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/event_edit',
            'deleteMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/event_delete',
            'responseSelector': '#editorModalBody'
        };
    },
    save: function(eventId) {
        if (eventId == undefined || eventId === null || eventId === false) {
            var eventId = null;
        }
        EventEdit.call(eventId);
    },
    delete: function(eventId) {
        var params = EventEdit.getParameters();
        $.ajax({
            'type' : 'POST',
            'url' : params.deleteMethodPath,
            'data': { 'eventId': eventId },
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                Structure.update();
            },
            'error': function(request, error) {
                ElastiTools.checkResponse(request.responseText);
            },
        });
    },
    call: function(eventId) {
        var params = EventEdit.getParameters();
        var ajaxData = {};
        var form = $('#SchedulePackage_eventEdit_form');
        var formData = form.serialize();
        var additionalData = {
            'eventId': eventId
        };
        ajaxData = formData + '&' + $.param(additionalData);
        $.ajax({
            'type' : 'POST',
            'url' : params.editMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                var params = EventEdit.getParameters();
                $(params.responseSelector).html(response.view);
                FormValidator.displayErrors('#SchedulePackage_eventEdit_form', response.data.form);
                if (response.data.form.result === true) {
                    Structure.update();
                    $('#editorModal').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            },
            'error': function(request, error) {
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
<a href="" class="triggerModal" onClick="EventAdminForm.new();"><?php echo trans('new.event'); ?></a>
