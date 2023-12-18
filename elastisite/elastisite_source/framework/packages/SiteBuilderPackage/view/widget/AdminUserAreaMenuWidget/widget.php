<div class="widgetWrapper">
    <div class="widgetWrapper-info">
    <b>Látogatói tér:</b> a webhely összes olyan lapja, amelyet a potenciális vásárlók meg tudnak látogatni. Vagyis nem tartozik ide semmilyen üzemeltetői felület.
    </div>

    <div id="userAreaMenu-editor-flexibleContent">
        <?php 
        echo $flexibleContent;
        ?>
    </div>
</div>
<style>
.sortable { 
    list-style-type: none; 
}
.sortable li span { 
    position: absolute; 
}
</style>
<script>
    var UserAreaMenuEditor = {
        // editingRouteTitle: null,
        // reload: function() {
        //     $.ajax({
        //         'type' : 'POST',
        //         'url' : '<?php echo App::getContainer()->getUrl()->getHttpDomain(); ?>/admin/AdminUserAreaMenuWidget_flexibleContent',
        //         'data': {
        //             // 'builtPageId': DistantViewEditor.builtPageId
        //         },
        //         'async': false,
        //         'success': function(response) {
        //             ElastiTools.checkResponse(response);
        //             $('#userAreaMenu-editor-flexibleContent').html(response.view);
        //         },
        //         'error': function(request, error) {
        //             console.log(request);
        //             console.log(" Can't do because: " + error);
        //         },
        //     });
        // },
        addToMenu: function(routeName, title, routePath) {
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo App::getContainer()->getUrl()->getHttpDomain(); ?>/admin/AdminUserAreaMenuWidget_addToMenu',
                'data': {
                    'routeName': routeName,
                    'title': title,
                    'routePath': routePath
                },
                'async': false,
                'success': function(response) {
                    ElastiTools.checkResponse(response);
                    $('#userAreaMenu-editor-flexibleContent').html(response.view);
                    Structure.loadWidget('MenuWidget');
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                },
            });
        },
        removeFromMenu: function(routeName, routePath) {
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo App::getContainer()->getUrl()->getHttpDomain(); ?>/admin/AdminUserAreaMenuWidget_removeFromMenu',
                'data': {
                    'routeName': routeName,
                    'routePath': routePath
                    // 'title': title
                },
                'async': false,
                'success': function(response) {
                    ElastiTools.checkResponse(response);
                    $('#userAreaMenu-editor-flexibleContent').html(response.view);
                    Structure.loadWidget('MenuWidget');
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                },
            });
        },
        initEditTitle: function(routeId, title) {
            // UserAreaMenuEditor.editingRouteTitle = routeName;
            $('#UserAreaMenuEditor_titleInput_' + routeId).val(title);
            $('#UserAreaMenuEditor_titleContainer_' + routeId).hide();
            $('#UserAreaMenuEditor_titleInputContainer_' + routeId).show();
        },
        // exitEditTitle: function(routeName) {
        //     // UserAreaMenuEditor.editingRouteTitle = null;
        //     $('#UserAreaMenuEditor_titleInput_' + routeName).val('');
        //     $('#UserAreaMenuEditor_titleContainer_' + routeName).show();
        //     $('#UserAreaMenuEditor_titleInputContainer_' + routeName).hide();
        // },
        saveTitle: function(routeName) {
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo App::getContainer()->getUrl()->getHttpDomain(); ?>/admin/AdminUserAreaMenuWidget_saveTitle',
                'data': {
                    'routeName': routeName,
                    'title': $('#UserAreaMenuEditor_titleInput_' + routeName).val()
                },
                'async': false,
                'success': function(response) {
                    ElastiTools.checkResponse(response);
                    // UserAreaMenuEditor.cancelEditTitle();
                    $('#userAreaMenu-editor-flexibleContent').html(response.view);
                    Structure.loadWidget('MenuWidget');
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                },
            });
        },
        cancelEditTitle: function() {
            $('.UserAreaMenuEditor_titleContainer').each(function() {
                $(this).show();
            });
            $('.UserAreaMenuEditor_titleInputContainer').each(function() {
                $(this).hide();
            });
        },
        sort: function(ui) {
            let routeIds = [];
            $('.UserAreaMenuEditor-sorting-item').each(function() {
                routeIds.push($(this).attr('data-routeid'));
            });
            // console.log(routeIds);
            // return;

            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo App::getContainer()->getUrl()->getHttpDomain(); ?>/admin/AdminUserAreaMenuWidget_sort',
                'data': {
                    'routeIds': routeIds
                },
                'async': true,
                'success': function(response) {
                    ElastiTools.checkResponse(response);
                    $('#userAreaMenu-editor-flexibleContent').html(response.view);
                    Structure.throwToast('<?php echo trans('system.message'); ?>', '<?php echo trans('new.sequence.saved'); ?>');
                    Structure.loadWidget('MenuWidget');
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                },
            });
        }
    };

    $('document').ready(function() {
        // $('body').off('focusout', '.UserAreaMenuEditor_titleInput')
        // $('body').on('focusout', '.UserAreaMenuEditor_titleInput', function() {
        //     $('')
        // });
    });

</script>