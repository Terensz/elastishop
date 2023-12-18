<?php
// dump($frameTexts);
// dump($contentTexts);
?>
<script src="/public_folder/plugin/CKEditor/ckeditor/ckeditor.js"></script>
<?php ?>
<?php if ($frameTexts || $contentTexts): ?>

    <?php if ($frameTexts): ?>

        <div class="row">
            <div class="col-md-12 card-pack-header">
                <h4><?php echo $frameTextsTitle; ?></h4>
            </div>
        </div>

        <div class="card">
            <div class="bg-primary text-white card-header d-flex justify-content-between align-items-center">
                <div class="card-header-textContainer">
                    <h6 class="mb-0 text-white"><?php echo trans('information'); ?></h6>
                </div>
            </div>
            <div class="card-body">
                <span>
                    <?php echo $frameTextsInfo; ?>
                </span>
            </div>
        </div>

        <!-- <div class="widgetWrapper-info"><?php echo $frameTextsInfo; ?></div> -->

        <?php 
        $documentPart = 'frame';
        $textArrayDef = $frameTexts[$defaultLocale];
        $textArrayEn = $frameTexts['en'];
        include('card.php');
        ?>

    <?php endif; ?>
    <?php if ($contentTexts): ?>
        
        <div class="row">
            <div class="col-md-12 card-pack-header">
                <h4><?php echo $contentTextsTitle; ?></h4>
            </div>
        </div>

        <div class="card">
            <div class="bg-primary text-white card-header d-flex justify-content-between align-items-center">
                <div class="card-header-textContainer">
                    <h6 class="mb-0 text-white"><?php echo trans('information'); ?></h6>
                </div>
            </div>
            <div class="card-body">
                <span>
                    <?php echo $contentTextsInfo; ?>
                </span>
            </div>
        </div>

        <?php 
        $documentPart = 'content';
        $textArrayDef = $contentTexts[$defaultLocale];
        $textArrayEn = $contentTexts['en'];
        include('card.php');
        ?>
    <?php endif; ?>
<?php else: ?>
        <div class="card">
            <div class="bg-primary text-white card-header d-flex justify-content-between align-items-center">
                <div class="card-header-textContainer">
                    <h6 class="mb-0 text-white"><?php echo trans('information'); ?></h6>
                </div>
            </div>
            <div class="card-body">
                <span>
                    <?php echo trans('no.content.text.found.in.this.category'); ?>
                </span>
            </div>
        </div>
<?php endif; ?>


<script>
    $('document').ready(function() {
        // $('body').off('click', '.triggerEdit');
        // $('body').on('click', '.triggerEdit', function() {
        //     console.log($(this).attr('id'));
        // });
    });
    var ContentTexts = {
        // show: function(e, id) {
        //     e.preventDefault();
        //     console.log(id);
        // },
        edit: function(e, uniqueId, submitted) {
            if (e != null) {
                e.preventDefault();
            }
            // console.log(uniqueId);
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/contentText/edit',
                'data': {
                    'uniqueId': uniqueId,
                    'submitted': submitted,
                    'phrase': $('#ContentPackage_contentTextEdit_phrase').val()
                },
                'async': true,
                'success': function(response) {
                    ElastiTools.checkResponse(response);
                    ContentTexts.fillAndOpenModal(response.data.modalLabel, response.view);
                    if (submitted == true) {
                        Structure.throwToast('<?php echo trans('system.message'); ?>', '<?php echo trans('content.saved'); ?>');
                        AdminContentTextsWidget.call();
                    }
                    // $('#').html(response.view);
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                },
            });
        },
        fillAndOpenModal: function(label, body) {
            $('#editorModalLabel').html(label);
            $('#editorModalBody').html(body);
            $('#editorModal').modal('show');
        },
        resetRequest: function(e, uniqueId) {
            e.preventDefault();
            console.log('resetRequest: ' + uniqueId);
            $('#confirmModalConfirm').attr('onClick', "ContentTexts.reset(event, '" + uniqueId + "');");
            $('#confirmModalBody').html('<?php echo trans('are.you.sure'); ?>');
            $('#confirmModal').modal('show');
        },
        reset: function(e, uniqueId) {
            e.preventDefault();
            console.log('e: ' + e);
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/contentText/reset',
                'data': {
                    'uniqueId': uniqueId
                },
                'async': true,
                'success': function(response) {
                    $('#confirmModal').modal('hide');
                    ElastiTools.checkResponse(response);
                    Structure.throwToast('<?php echo trans('system.message'); ?>', '<?php echo trans('content.restored'); ?>');
                    // console.log(response);
                    AdminContentTextsWidget.call();
                    // ContentTexts.fillAndOpenModal(response.data.modalLabel, response.view);
                    // if (submitted == true) {
                    //     AdminContentTextsWidget.call();
                    // }
                    // $('#').html(response.view);
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                },
            });
        },
        save: function() {
            $('#ContentPackage_contentTextEdit_phrase').val(CKEDITOR.instances.ContentPackage_contentTextEdit_phrase.getData());
            ContentTexts.edit(null, $('#ContentPackage_contentTextEdit_uniqueId').val(), true);
        },
    };
</script>