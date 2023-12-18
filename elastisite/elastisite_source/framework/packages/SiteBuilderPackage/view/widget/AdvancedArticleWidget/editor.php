<div id="AAW_toolbar_<?php echo $article->getId(); ?>" class="aawToolbar-container">
<?php echo $toolbarContent; ?>
</div>
<script>
    var AAWEditor_<?php echo $article->getId(); ?> = {
        refresh: function(calledBy, response) {
            console.log('response:');
            console.log(response);
            $('#AAW_toolbar_<?php echo $article->getId(); ?>').html(response.view.toolbar);
            $('#AAW_viewer_<?php echo $article->getId(); ?>').html(response.view.viewer);
        },
        callAjax: function(calledBy, additionalData) {
            let baseData = {
                'articleId': <?php echo $article->getId(); ?>
            };
            let ajaxData = $.extend({}, baseData, additionalData);
            $.ajax({
                'type' : 'POST',
                'url' : '/AAWEditor/' + calledBy,
                'data': ajaxData,
                'async': true,
                'success': function(response) {
                    ElastiTools.checkResponse(response);
                    AAWEditor_<?php echo $article->getId(); ?>.refresh(calledBy, response);
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                },
            });
        },
        addArticleParagraph: function() {
            AAWEditor_<?php echo $article->getId(); ?>.callAjax('addArticleParagraph', {});
        },
        addArticleColumn: function(articleParagraphId) {
            AAWEditor_<?php echo $article->getId(); ?>.callAjax('addArticleColumn', {
                'articleParagraphId': articleParagraphId
            });
        },
        addArticleBlock: function(articleColumnId) {
            AAWEditor_<?php echo $article->getId(); ?>.callAjax('addArticleBlock', {
                'articleColumnId': articleColumnId
            });
        },
        addArticleUnit: function(articleBlockId) {
            AAWEditor_<?php echo $article->getId(); ?>.callAjax('addArticleUnit', {
                'articleBlockId': articleBlockId
            });
        },
        addArticleText: function(articleUnitId) {
            AAWEditor_<?php echo $article->getId(); ?>.callAjax('addArticleText', {
                'articleUnitId': articleUnitId
            });
        },
        addArticleImage: function() {
            AAWEditor_<?php echo $article->getId(); ?>.callAjax('addArticleImage', {
                'articleUnitId': articleUnitId
            });
        },
    };
</script>
<style>
    .aawToolbar-container {
        background-color: #eaeaea;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        padding: 10px;
    }
    .AAW-articleParagraph-toolbar {
        background-color: #eaeaea;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        padding: 10px;
    }
</style>