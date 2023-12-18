<?php 
include('framework/packages/ToolPackage/view/upload/js.php');
?>
<div class="widgetWrapper">
    <div class="widgetWrapper-info">
<?php
echo '<b>'.trans('open.graph').'</b>: '.trans('whatis.open.graph');
?>
    </div>

    <div id="AdminOpenGraphWidget_formContainer"></div>

</div>

<style>
    .thumbnail-frame-outer {
        background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkqAcAAIUAgUW0RjgAAAAASUVORK5CYII=');
        color: #fcfcfc;
        vertical-align: middle;
        border-radius: 6px;
        box-shadow: 0 4px 6px #353535;
        width: 155px;
        height: 100px;
        float: left;
        /* margin-left: 6px;
        margin-right: 6px; */
        margin-top: 10px;
        margin-bottom: 10px;
        margin-left: 6px;
        margin-right: 6px;
    }
    .thumbnail-frame-inner {
        background-color: #9b9b9b;
        height: 100%;
        border-radius: 6px;
        padding: 6px;
        /* margin: auto; */
    }
    /* .thumbnail-image {
        width: 100%;
        border-radius: 1px;
        box-shadow: 1px 1px 1px #515151;
        background-position: center top;
        background-size: cover;
        overflow: none;
        margin-bottom: 0px;
        max-height: 90px;
    } */
</style>
<script>
    var AdminOpenGraph = {
        uploadCallback: function(responseDataJson) {
            AdminOpenGraph.loadForm(false);
        },
        loadForm: function(submitted) {
            console.log('loadForm!', submitted);
            var form = $('#AppearancePackage_openGraph_form');
            var formData = form.serialize();
            var additionalData = {
                // 'id': $('#editVideo_id').html(),
                // 'title': $('#AppearancePackage_openGraph_image').html(),
                // 'description': $('#editVideo_extension').html(),
                'submitted': submitted
            };
            ajaxData = formData + '&' + $.param(additionalData);
            console.log(ajaxData);
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/openGraph/form',
                'data': ajaxData,
                'async': false,
                'success': function(response) {
                    // console.log(response);
                    $('#AdminOpenGraphWidget_formContainer').html(response.view);
                    new nicEditor({buttonList :['fontSize','bold','italic','underline','ol','ul']}).panelInstance('AppearancePackage_openGraph_description', {hasPanel : true, maxHeight: 200});
                    $('.nicEdit-main').on('blur', function() {
                        let content = nicEditors.findEditor("AppearancePackage_openGraph_description").getContent();
                        $('#AppearancePackage_openGraph_description').html(content);
                    });

                    if (response.data.message != null) {
                        let color = '#205e0a';
                        if (response.data.success == false) {
                            color = '#ab142d';
                        }
                        let message = '<span style="color: ' + color + '">' + response.data.message + '</span>';
                        $('#AppearancePackage_openGraph_form-message').html(message);
                    }
                }
            });
        },
        // deleteImage: function(e) {
        //     // console.log('removeImage!');
        //     e.preventDefault();
        //     $.ajax({
        //         'type' : 'POST',
        //         'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/openGraph/deleteImage',
        //         'data': {
        //             // 'id': $('#editVideo_id').html(),
        //             // 'code': $('#editVideo_code').html(),
        //             // 'extension': $('#editVideo_extension').html()
        //         },
        //         'async': false,
        //         'success': function(response) {
        //             if (response.data.success == true) {
        //                 AdminOpenGraph.loadForm(false);
        //                 // AdminOpenGraph.showOrHideSubmit();
        //             } else {
                        
        //             }
        //         },
        //         'error': function(request, error) {
        //             console.log(request);
        //             console.log(" Can't do because: " + error);
        //             // LoadingHandler.stop();
        //         },
        //     });
        // },
    };

    $('document').ready(function() {
        AdminOpenGraph.loadForm(false);

        // $('body').on('change', '#AppearancePackage_openGraph_image', function(e) {
        //     console.log('AppearancePackage_openGraph_image!!');
        //     var url = '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/openGraph/uploadImage';
        //     var file = $(this)[0].files[0];
        //     var upload = new Upload(file);
        //     if (file !== undefined) {
        //         upload.doUpload('AppearancePackage_openGraph_image', url, 'AdminOpenGraph.uploadCallback', null);
        //     }
        //     // AtpmFileContainer.call();
        // });

        // $('body').off('change', '#FrameworkPackage_openGraphImageHandler_image');
        // $('body').on('change', '#FrameworkPackage_openGraphImageHandler_image', function(e) {
        //     console.log('img change!');
        //     OpenGraphImageHandler.upload();
        // });
    });
</script>