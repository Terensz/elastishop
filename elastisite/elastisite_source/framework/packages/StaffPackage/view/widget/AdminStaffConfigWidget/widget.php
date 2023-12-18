<div class="widgetWrapper">
    <div class="grid-container" id="AdminStaffConfig-list" onclick="AdminStaffConfig.edit(event, false);"></div>
</div>

<script>
var AdminStaffConfig = {
    list: function() {
        $.ajax({
            'type' : 'POST',
            'url' : '/admin/staff/config/list',
            'data': {
                // 'prevShipmentId': prevShipmentId
                // 'lastShipmentId': lastShipmentId
            },
            'async': false,
            'success': function(response) {
                console.log(response);
                $('#AdminStaffConfig-list').html(response.view);
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
            },
        });
    },
    edit: function(e, submitted) {
        e.preventDefault();
        var formData = {};
        if (submitted === true) {
            var form = $('#StaffPackage_editConfig_form');
            formData = form.serialize();
        }
        var additionalData = {
            'submitted': (submitted ? 'true' : 'false')
        };
        ajaxData = formData + '&' + $.param(additionalData);
        $.ajax({
            'type' : 'POST',
            'url' : '/admin/staff/config/edit',
            'data': ajaxData,
            'async': false,
            'success': function(response) {
                // console.log(response);
                // $('#').html(response.view);
                if (submitted) {
                    $('#editorModal').modal('hide');
                    AdminStaffConfig.list();
                } else {
                    ElastiTools.checkResponse(response);
                    ElastiTools.fillModal(response, null);
                    $('#editorModal').modal('show');
                }
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
            },
        });
    }
};
    // almaDocReady
$(document).ready(function() {
    AdminStaffConfig.list();
});
</script>