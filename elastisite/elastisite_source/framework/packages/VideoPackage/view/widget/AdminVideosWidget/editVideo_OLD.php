<div id="videoSourceBase" style="display: none;"><?php echo $container->getUrl()->getHttpDomain(); ?>/videoPlayer/play/</div>
<?php
dump($form->getEntity());//exit;
include('framework/packages/ToolPackage/view/upload/js.php');

$formView = $viewTools->create('form')->setForm($form);
$formView->setResponseBodySelector('#editorModalBody');
$formView->setResponseLabelSelector('#editorModalLabel');
$formView->add('text')->setPropertyReference('title')->setLabel(trans('title'));
$formView->add('textarea')->setPropertyReference('description')->setLabel(trans('description'));
$formView->add('select')->setPropertyReference('status')->setLabel(trans('status'))
    ->addOption('1', 'active')
    ->addOption('0', 'disabled')
;
$formView->add('hidden')->setPropertyReference('code')->setLabel(trans('code'));
$formView->add('hidden')->setPropertyReference('extension')->setLabel(trans('extension'));
$formView->add('file')->setPropertyReference('file')->setLabel(trans('video'));
$displayStr = (!$form->getEntity()->getCode() && !$form->getEntity()->getExtension()) ? 'style="display: none;"' : '';
// $customView = '
// <div id="VideoPackage_editVideo_videoFileName-container"'.$displayStr.'>
//     <img id="VideoPackage_editVideo_videoFileName" style="width: 200px;" src="'.$form->getEntity()->getCode().'.'.$form->getEntity()->getExtension().'">
// </div>
// ';

$customView = '
<div class="row" id="VideoPackage_editVideo_videoPreview-container"'.$displayStr.'>
    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
        <div class="form-group formLabel">
            <label for="VideoPackage_editVideo_description">
                <b>'.trans('file').'</b>
            </label>
        </div>
    </div>
    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
        <div class="form-group formLabel" style="float: left;">
            <div style="text-align: left;">
                <span>
                <a class="triggerModal" href="" onclick="EditVideo.removeFile(event, \''.$form->getEntity()->getCode().'\', \''.$form->getEntity()->getExtension().'\');">'.trans('delete.file').'</a>
                <!--&nbsp; - &nbsp;<span id="VideoPackage_editVideo_videoFileName">'.($form->getEntity()->getCode() && $form->getEntity()->getExtension() ? $form->getEntity()->getCode().'.'.$form->getEntity()->getExtension() : '').'</span>-->
                </span>
            </div>
            <div>
                <video id="videoPlayer" width="320" controls>
                    <source id="VideoPackage_editVideo_videoSource" src="'.$container->getUrl()->getHttpDomain().'/videoPlayer/play/'.$form->getEntity()->getCode().'.'.$form->getEntity()->getExtension().'" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
        </div>
    </div>
</div>
';
// <source src="'.$container->getUrl()->getHttpDomain().'/videoPlayer/play/'.$form->getEntity()->getCode().'.'.$form->getEntity()->getExtension().'" type="video/mp4">

$formView->add('custom')->setPropertyReference(null)->setLabel(trans('image'))->addCustomData('view', $customView);
if ($form->getEntity()->getCode() && $form->getEntity()->getExtension()) {

}
$formView->add('submit')->setPropertyReference('submit')->setValue(trans('save'));
// $formView->add('text')->setPropertyReference('postalAddress')->setLabel(trans('postal.address'));
// $formView->add('submit')->setPropertyReference('submit')->setValue(trans('send'));
$formView->setFormMethodPath('admin/video/edit');
$formView->displayForm()->displayScripts();

?>

    <!-- <div class="row">
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
            <div class="form-group formLabel">
                <label for="VideoPackage_editVideo_file">
                    <b><?php echo trans('video'); ?></b>
                </label>
            </div>
        </div>
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <div class="form-group">
                <div class="custom-file mt-3 mb-3">
                    <input type="file" class="custom-file-input" id="VideoPackage_editVideo_file" name="VideoPackage_editVideo_file">
                    <label class="custom-file-label" for="customFile"><?php echo trans('upload.video'); ?></label>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <div class="form-group">
                <button name="VideoPackage_editVideo_submit" id="VideoPackage_editVideo_submit" type="button" class="btn btn-secondary btn-block" style="width: 200px;" onclick="VideoPackageEditVideoForm.call();" value="">Mentés</button>
            </div>
        </div>
    </div> -->

<script>
var EditVideo = {
    uploadCallback: function(responseDataJson) {
        let responseData = JSON.parse(responseDataJson);
        // console.log('EditVideo.uploadCallback');
        // console.log(responseData);
        if (responseData.uploadResult.success === false) {
            $('#VideoPackage_editVideo_file-validationMessage').html(responseData.uploadResult.errorMessage);
        } else {
            $('#VideoPackage_editVideo_code').val(responseData.uploadResult.data.fileName);
            $('#VideoPackage_editVideo_extension').val(responseData.uploadResult.data.extension);
            $('#VideoPackage_editVideo_file-validationMessage').html('');
            $('#VideoPackage_editVideo_file').parent().parent().parent().parent().hide();
            $('#VideoPackage_editVideo_videoSource').html($('#videoSourceBase').html() + responseData.uploadResult.data.fileName + '.' + responseData.uploadResult.data.extension);
            $('#VideoPackage_editVideo_videoPreview-container').show();
            $('#VideoPackage_editVideo_submit').parent().parent().show();
            // AdminVideosGrid.list(true);
        }
    },
    removeFile: function(e, code, extension) {
        e.preventDefault();
        console.log('code: ' + code);
        console.log('extension: ' + extension);
        $.ajax({
            'type' : 'POST',
            'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/video/removeFile',
            'data': {
                'code': code,
                'extension': extension
            },
            'async': false,
            'success': function(response) {
                if (response.data.success == true) {
                    $('#VideoPackage_editVideo_code').val('');
                    $('#VideoPackage_editVideo_extension').val('');
                    $('#VideoPackage_editVideo_videoSource').html('');
                    $('#VideoPackage_editVideo_videoPreview-container').hide();
                    $('#VideoPackage_editVideo_submit').parent().parent().hide();
                    $('#VideoPackage_editVideo_file-validationMessage').html('');
                    if ($('#VideoPackage_editVideo_title').val() == '') {
                        $('#VideoPackage_editVideo_file').parent().parent().parent().parent().hide();
                    } else {
                        $('#VideoPackage_editVideo_file').parent().parent().parent().parent().show();
                    }
                    AdminVideosGrid.list(true);
                } else {
                    
                }
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
                // LoadingHandler.stop();
            },
        });
    },
    showOrHideFileName: function() {
        console.log('VideoPackage_editVideo_videoSource val: "' + $('#VideoPackage_editVideo_videoSource').html());
        if ($('#VideoPackage_editVideo_videoSource').html() == '') {
            if ($('#VideoPackage_editVideo_title').val() == '') {
                console.log('VideoPackage_editVideo_title val: ' + $('#VideoPackage_editVideo_title').val());
                $('#VideoPackage_editVideo_file').parent().parent().parent().parent().hide();
            } else {
                console.log('VideoPackage_editVideo_title val: ' + $('#VideoPackage_editVideo_title').val());
                $('#VideoPackage_editVideo_file').parent().parent().parent().parent().show();       
            }
        }
    }
};

$(document).ready(function() {
    $('#VideoPackage_editVideo_file').parent().parent().parent().parent().hide();
<?php if (!$form->getEntity()->getCode() && !$form->getEntity()->getExtension()): ?>
    $('#VideoPackage_editVideo_submit').parent().parent().hide();
<?php endif; ?>
    new nicEditor({buttonList :['fontSize','bold','italic','underline','ol','ul']}).panelInstance('VideoPackage_editVideo_description', {hasPanel : true});

    // $('body').on('blur', '.nicEdit-main', function() {
    $('.nicEdit-main').on('blur', function() {
        let content = nicEditors.findEditor("VideoPackage_editVideo_description").getContent();
        $('#VideoPackage_editVideo_description').html(content);
    });

    $('textarea').keypress(function(e) {
        if (e.which == 13) {
            e.stopPropagation();
        }
    });

    EditVideo.showOrHideFileName();
    $('#VideoPackage_editVideo_title').on('change blur keyup', function(e) {
        EditVideo.showOrHideFileName();
    });

    $('#VideoPackage_editVideo_file').change(function(e) {
        console.log('VideoPackage_editVideo_file!!');
        var url = '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/video/upload';
        var file = $(this)[0].files[0];
        var upload = new Upload(file);
        if (file !== undefined) {
            upload.doUpload('VideoPackage_editVideo_file', url, 'EditVideo.uploadCallback', null);
        }
        // AtpmFileContainer.call();
    });
});
</script>
