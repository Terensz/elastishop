<div id="customPageEdit_background_formContainer">
</div>

<script>
    var CustomPageBackground = {
        // submitForm: function() {
        //     CustomPageOpenGraph.loadForm(true, true);
        // },
        showForm: function() {
            $('#FrameworkPackage_customPageBackground_saveBackgroundColor_buttons').hide();
            // console.log('id: ', $('#customPageId').html());
            // var form = $('#FrameworkPackage_customPageOpenGraph_form');
            // var formData = form.serialize();
            // var additionalData = {
            //     'customPageId': $('#customPageId').html(),
            //     'submitted': submitted
            // };
            // ajaxData = formData + '&' + $.param(additionalData);
            // console.log(ajaxData);
            var fbsBackgroundTheme = null;

            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/customPage/background/editForm',
                'data': {
                    'customPageId': $('#customPageId').html(),
                    'backgroundColor': $('#FrameworkPackage_customPageBackground_backgroundColor').val(),
                    'fbsBackgroundTheme': fbsBackgroundTheme,
                },
                'async': false,
                'success': function(response) {
                    LoadingHandler.stop();
                    $('#customPageEdit_background_formContainer').html(response.view);
                }
            });
        },
        removeBackground: function(e) {
            e.preventDefault();
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/customPage/background/removeBackground',
                'data': {
                    'customPageId': $('#customPageId').html(),
                },
                'async': false,
                'success': function(response) {
                    LoadingHandler.stop();
                    CustomPageBackground.showForm();
                }
            });
        },
        selectBackground: function(backgroundId) {
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/customPage/background/selectBackground',
                'data': {
                    'customPageId': $('#customPageId').html(),
                    'backgroundId': backgroundId
                },
                'async': false,
                'success': function(response) {
                    LoadingHandler.stop();
                    CustomPageBackground.showForm();
                }
            });
        },
        promptChangedBackgroundColor: function() {
            var originalBackgroundColor = $('#FrameworkPackage_customPageBackground_originalBackgroundColor').val();
            var backgroundColor = $('#FrameworkPackage_customPageBackground_backgroundColor').val();
            if (originalBackgroundColor != backgroundColor) {
                $('#FrameworkPackage_customPageBackground_saveBackgroundColor_buttons').show();
            }
        },
        saveBackgroundColor: function() {
            // var originalBackgroundColor = $('#FrameworkPackage_customPageBackground_originalBackgroundColor').val();
            var backgroundColor = $('#FrameworkPackage_customPageBackground_backgroundColor').val();
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/customPage/background/saveBackgroundColor',
                'data': {
                    'customPageId': $('#customPageId').html(),
                    // 'originalBackgroundColor': originalBackgroundColor,
                    'backgroundColor': backgroundColor
                },
                'async': false,
                'success': function(response) {
                    LoadingHandler.stop();
                    CustomPageBackground.showForm();
                }
            });
        }
    };

    $('document').ready(function() {
        CustomPageBackground.showForm();

        $('#FrameworkPackage_customPageBackground_saveBackgroundColor_buttons').hide();

        $('body').off('click', '.customPageBackground_input');
        $('body').on('click', '.customPageBackground_input', function() {
        // $('.customPageOpenGraph_input').on('click', function() {
            var background = $(this);
            var backgroundId = background.attr('data-backgroundid');
            // console.log(openGraph.attr('data-opengraphid'));
            CustomPageBackground.selectBackground(backgroundId);
        });

        $('body').off('change', '#FrameworkPackage_customPageBackground_backgroundColor');
        $('body').on('change', '#FrameworkPackage_customPageBackground_backgroundColor', function() {
            // console.log('change FrameworkPackage_customPageBackground_backgroundColor');
            CustomPageBackground.promptChangedBackgroundColor();
        });
        LoadingHandler.stop();
    });
</script>