<style>
@media (min-width: 900px) {
  .modal-lg {
    width: 900px;
  }
}
</style>

<script>
var ArticleEdit = {
    refresh: function() {
        TeaserWidget.call();
    },
    getParameters: function() {
        return {
            'editMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/article/edit',
            'deleteMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/article/delete',
            'moveMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/article/move'
        };
    },
    new: function() {
        $('.article-toolbar').hide();
        ArticleEdit.call(null);
    },
    edit: function(articleId) {
        $('.article-toolbar').hide();
        ArticleEdit.call(articleId);
    },
    moveUp: function(articleId) {
        if (articleId == undefined || articleId === null || articleId === false) {
            return false;
        }
        ArticleEdit.move(articleId, 'up');
        TeaserWidget.call();
    },
    save: function(articleId) {
        if (articleId == undefined || articleId === null || articleId === false) {
            var articleId = null;
        }
        ArticleEdit.call(articleId);
    },
    deleteRequest: function(articleId) {
        if (articleId == undefined || articleId === null || articleId === false) {
            return false;
        }
        $('#confirmModalConfirm').attr('onClick', "ArticleEdit.deleteConfirmed(" + articleId + ");");
        $('#confirmModalBody').html('<?php echo trans('are.you.sure'); ?>');
        $('#confirmModal').modal('show');
    },
    deleteConfirmed: function(articleId) {
        ArticleEdit.delete(articleId);
        $('#confirmModal').modal('hide');
    },
    delete: function(articleId) {
        var params = ArticleEdit.getParameters();
        $.ajax({
            'type' : 'POST',
            'url' : params.deleteMethodPath,
            'data': { 'articleId': articleId },
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                TeaserWidget.call();
            },
            'error': function(request, error) {
                ElastiTools.checkResponse(request.responseText);
            },
        });
    },
    move: function(articleId, direction) {
        var params = ArticleEdit.getParameters();
        $.ajax({
            'type' : 'POST',
            'url' : params.moveMethodPath,
            'data': { 'articleId': articleId, 'direction': direction },
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                TeaserWidget.call();
            },
            'error': function(request, error) {
                ElastiTools.checkResponse(request.responseText);
            },
        });
    },
    displayForm: function(articleId, response) {
        articleId = articleId == null ? 'new' : articleId;
        $('#articleContainer_' + articleId).html(response.view);
    },
    call: function(articleId) {
        var params = ArticleEdit.getParameters();
        var ajaxData = {};
        var form = $('#ArticlePackage_article_form');
        var formData = form.serialize();
        var additionalData = {
            'articleId': articleId
        };
        ajaxData = formData + '&' + $.param(additionalData);
        $.ajax({
            'type' : 'POST',
            'url' : params.editMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                var params = ArticleEdit.getParameters();
                ArticleEdit.displayForm(articleId, response);
                if (response.hasOwnProperty('data')) {
                    FormValidator.displayErrors('#ArticlePackage_article_form', response.data.messages);
                    //console.log(response.data.messages);
                    if (response.data.formIsValid === true) {
                        TeaserWidget.call();
                    }
                }
            },
            'error': function(request, error) {
                ElastiTools.checkResponse(request.responseText);
            },
        });
    },
    saveSuccessful: function() {
        TeaserWidget.call();
    }
};

$('body').on('click', '.triggerModal', function (e) {
    e.preventDefault();
});
</script>

<a href="" class="triggerModal article-toolbar" onClick="ArticleEdit.new();"><?php echo trans('new.article'); ?></a>
<div id="articleContainer_new"></div>
