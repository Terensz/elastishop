<?php 
$selectedVideoId = $selectedVideo && $selectedVideo->getVideo() ? $selectedVideo->getVideo()->getId() : null;
?>
<div class="widgetWrapper">
    <div class="article-container">
        <div class="article-head">
            <div class="article-title"><?php echo trans('selecting.visitors.video'); ?></div>
        </div>
        <div class="article-content">
            <i><?php echo trans('visitors.video.info'); ?></i>
        </div>

        <div class="article-content">
            <form class="form" id="selectedVideo_form" name="selectedVideo_form">
                <div class="row">
                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                        <div class="form-group formLabel">
                            <label for="selectedVideo_video">
                                <b><?php echo trans('video'); ?></b>
                            </label>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
                        <div class="form-group">
                        <div class="input-group">
                            <select name="selectedVideo_video" id="selectedVideo_video" class="inputField form-control">
                                <option value="*null*">-- <?php echo trans('no.video.selected'); ?> --</option>
            <?php 
            foreach ($videos as $video): 
                $selectedStr = $selectedVideoId == $video->getId() ? ' selected' : '';
            ?>

                                <option value="<?php echo $video->getId(); ?>"<?php echo $selectedStr; ?>><?php echo $video->getTitle(); ?></option>
            <?php 
            endforeach;
            ?>
                            </select>
                        </div>
                            <div class="validationMessage error" id="selectedVideo_video-validationMessage" style="padding-top:4px;"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                    </div>
                    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
                        <div class="form-group">
                            <button name="selectedVideo_submit" id="selectedVideo_submit" type="button" class="btn btn-secondary btn-block" style="width: 200px;" onclick="HandleVideos.selectVisitorVideo(event);" value="">Mentés</button>
                    </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div id="selectedVideo-message" style="padding-top:4px;"></div>
                    </div>
                </div>

            </form>
        </div>

        <div class="article-content">

        </div>
    </div>
</div>
<script>
    var HandleVideos = {
        selectVisitorVideo: function(e) {
            // console.log('event: ', e);
            e.preventDefault();
            var form = $('#selectedVideo_form');
            ajaxData = form.serialize();
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/handleVideos/selectVisitorVideo',
                'data': ajaxData,
                'async': false,
                'success': function(response) {
                    let color = '#205e0a';
                    if (response.data.success == false) {
                        color = '#ab142d';
                    }
                    let message = '<span style="color: ' + color + '">' + response.data.message + '</span>';
                    $('#selectedVideo-message').html(message);
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                    // LoadingHandler.stop();
                },
            });
        },
    }

    $('document').ready(function() {

    });
</script>