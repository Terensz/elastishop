<div class="grid-container" id="AdminFooterConfig-list" onclick="AdminFooterConfig.edit(event, false);"></div>

<script>
var AdminFooterConfig = {
    list: function() {
        // e.preventDefault();
        // console.log('AdminFooterConfig.edit');
        $.ajax({
            'type': 'POST',
            'url': '/admin/AdminFooterWidget/list',
            'data': {
                // 'prevShipmentId': prevShipmentId
                // 'lastShipmentId': lastShipmentId
            },
            'async': false,
            'success': function(response) {
                // console.log(response);
                $('#AdminFooterConfig-list').html(response.view);
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
            var form = $('#FooterPackage_editConfig_form');
            formData = form.serialize();
        }
        var additionalData = {
            'submitted': (submitted ? 'true' : 'false')
        };
        ajaxData = formData + '&' + $.param(additionalData);
        // console.log('AdminFooterConfig.edit');
        $.ajax({
            'type': 'POST',
            'url': '/admin/AdminFooterWidget/edit',
            'data': ajaxData,
            'async': false,
            'success': function(response) {
                // console.log(response);
                // $('#').html(response.view);
                if (submitted) {
                    $('#editorModal').modal('hide');
                    AdminFooterConfig.list();
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
    AdminFooterConfig.list();
    // $('.multiselect-input').select2({
    //     placeholder: 'Select an option'
    // });

});
</script>