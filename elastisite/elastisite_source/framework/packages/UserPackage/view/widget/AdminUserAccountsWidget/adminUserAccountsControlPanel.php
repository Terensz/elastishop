<form name="UserPackage_userAccountSearch_form" id="UserPackage_userAccountSearch_form" method="POST" action="" enctype="multipart/form-data">
    <div class="row formRow">
        <div class="col-lg-6 col-md-6">
            <div class="form-group">
                <label for="UserPackage_userAccountSearch_accountCode"><?php echo trans('account.code'); ?></label>
                <input name="UserPackage_userAccountSearch_accountCode" id="UserPackage_userAccountSearch_accountCode" type="text"
                    class="inputField form-control" value="<?php echo ''; ?>" aria-describedby="" placeholder="">
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="form-group">
                <label for="UserPackage_userAccountSearch_name"><?php echo trans('name'); ?></label>
                <input name="UserPackage_userAccountSearch_name" id="UserPackage_userAccountSearch_name" type="text"
                    class="inputField form-control" value="<?php echo ''; ?>" aria-describedby="" placeholder="">
            </div>
        </div>
    </div>
    <div class="row formRow">
        <div class="col-lg-6 col-md-6">
            <div class="form-group">
                <label for="UserPackage_userAccountSearch_email"><?php echo trans('email'); ?></label>
                <input name="UserPackage_userAccountSearch_email" id="UserPackage_userAccountSearch_email" type="text"
                    class="inputField form-control" value="<?php echo ''; ?>" aria-describedby="" placeholder="">
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="form-group">
                <label for="UserPackage_userAccountSearch_username"><?php echo trans('username'); ?></label>
                <input name="UserPackage_userAccountSearch_username" id="UserPackage_userAccountSearch_username" type="text"
                    class="inputField form-control" value="<?php echo ''; ?>" aria-describedby="" placeholder="">
            </div>
        </div>
    </div>
    <div class="row formRow">
        <div class="col-lg-6 col-md-6">
            <div class="form-group">
                <label for="UserPackage_userAccountSearch_status"><?php echo trans('status'); ?></label>
                <select name="UserPackage_userAccountSearch_status" id="UserPackage_userAccountSearch_status" class="inputField form-control">
                    <option value="all"><?php echo trans('all'); ?></option>
                    <option value="1"><?php echo trans('active'); ?></option>
                    <option value="2"><?php echo trans('proven'); ?></option>
                    <option value="0"><?php echo trans('disabled'); ?></option>
                </select>
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
        </div>
    </div>
    <div class="row formRow">
        <div class="col-lg-6 col-md-6">
            <div class="form-group">
                <button id="UserPackage_userAccountSearch_submit" style="width: 200px;"
                    type="button" class="btn btn-secondary btn-block"
                    onclick="UserAccountSearch.search();"><?php echo trans('search'); ?></button>
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="form-group">
            </div>
        </div>
    </div>
</form>
<script>
var UserAccountSearch = {
    getParameters: function() {
        return {
            'searchMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/user/search',
            'formName': 'UserPackage_userAccountSearch_form'
        };
    },
    search: function(page) {
        var params = UserAccountSearch.getParameters();
        var ajaxData = {};
        var form = $('#' + params.formName);
        // console.log(params.formName);
        var formData = form.serialize();
        var additionalData = {
            'page': page
        };
        ajaxData = formData + '&' + $.param(additionalData);
        // console.log(ajaxData);
        $.ajax({
            'type' : 'POST',
            'url' : params.searchMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                $('#adminUserAccountsGrid').html(response.view);
                $('#editorModal').modal('hide');
                // console.log(response.view);
            },
            'error': function(request) {
                ElastiTools.checkResponse(request.responseText);
            },
        });
    },
};
</script>
