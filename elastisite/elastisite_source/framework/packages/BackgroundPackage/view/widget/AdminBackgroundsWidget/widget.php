<?php
// include('framework/packages/BackgroundPackage/view/widget/AdminBackgroundsWidget/list.php');
include('framework/packages/ToolPackage/view/upload/js.php');
include('framework/packages/BackgroundPackage/view/widget/AdminBackgroundsWidget/BackgroundEdit_js.php');
?>

<div class="newItem mb-4">
    <button id="AscScaleLister_newScale" onclick="BackgroundEdit.new(event);" class="btn btn-success"><?php echo trans('create.new'); ?></button>
</div>

<?php
if (!isset($backgrounds) || empty($backgrounds)) {
    $backgrounds = [];
    // foreach ($backgrounds as $background):
    //     include('framework/packages/BackgroundPackage/view/widget/AdminBackgroundsWidget/listElement.php');
    // endforeach;
}
// dump($backgrounds);
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="card-header-textContainer">
            <h6 class="mb-0"><?php echo trans('admin.list.backgrounds'); ?></h6>
        </div>
    </div>
    <?php foreach ($backgrounds as $background): ?>
    <div class="card-footer">

        <div class="list-item-container">
            <div class="list-item-thumbnail">
                <img src="<?php echo $container->getUrl()->getHttpDomain(); ?>/image/background/thumbnail/<?php echo $background->getTheme().'.'.$background->getExtension(); ?>">
            </div>
            <div class="row">
                <div class="col-8">
                    <div class="list-item-attribute">
                        <?php echo $background->getEngine() == 'SlidingStripes' ? 'Beúszó csíkok' : 'Egyszerű'; ?>
                    </div>
                    <div class="list-item-attribute">
                        <b>
                            <?php echo $background->getTitle(); ?>
                        </b>
                    </div>
                </div>
                <div class="col-4">
        <?php
            if (in_array($background->getTheme(), $bindedBgs)) {
        ?>
                    <?php echo trans('binded.to.page'); ?>
        <?php
            } else {
        ?>
                    <a href="" class="triggerModal" onclick="BackgroundList.delete(event, '<?php echo $background->getId(); ?>');"><?php echo trans('delete'); ?></a>
        <?php
            }
        ?>
                </div>
            </div>
        </div>



    </div>
    <?php endforeach; ?>
</div>

<script>
var BackgroundImageUploader = {
    uploadCallback: function(responseData) {
        console.log('responseData', responseData);
        BackgroundEdit.call();
        LoadingHandler.stop();
    }
};

var BackgroundList = {
    getParameters: function() {
        return {
            'deleteMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/background/delete'
        };
    },
    delete: function(e, id) {
        // console.log('id:' + id);
        e.preventDefault();
        var params = BackgroundList.getParameters();
        $.ajax({
            'type' : 'POST',
            'url' : params.deleteMethodPath,
            'data': {'FBSBackgroundId': id},
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                AdminBackgroundsWidget.call();
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
            },
        });
    }
};

$(document).ready(function() {
    $('body').on('click', '.triggerModal', function (e) {
        e.preventDefault();
    });
    $('body').off('change', '#BackgroundPackage_createBackground_uploadedRawImage');
    $('body').on('change', '#BackgroundPackage_createBackground_uploadedRawImage', function() {
    // $('#BackgroundPackage_createBackground_uploadedRawImage').change(function() {
        LoadingHandler.start();
        var url = '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/background/rawImage/upload';
        var file = $(this)[0].files[0];
        var upload = new Upload(file);
        if (file !== undefined) {
            console.log('BackgroundPackage_createBackground_uploadedRawImage!');
            upload.doUpload('BackgroundPackage_createBackground_uploadedRawImage', url, 'BackgroundImageUploader.uploadCallback', null);
            // BackgroundEdit.call();
        }
    });
});
</script>
