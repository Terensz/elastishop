<!-- <video id="videoPlayer" width="320" height="240" controls>
  <source src="<?php echo $container->getUrl()->getHttpDomain(); ?>/videoPlayer/play/dasdasd" type="video/mp4">
  Your browser does not support the video tag.
</video> -->
<div id="videoBox-header" style="height: 32px; background-color: #e8e8e8;">
  <!-- <svg class="bi" width="1em" height="1em">
    <use xlink:href="<?php echo $container->getUrl()->getHttpDomain(); ?>/public_folder/plugin/Bootstrap-icons/x.svg"></use>
  </svg> -->
  <a id="videoBox-close" class="pseudoLink" href="">
    <img class="window-button" src="<?php echo $container->getUrl()->getHttpDomain(); ?>/public_folder/plugin/Bootstrap-icons/x.svg">
  </a>
  <a id="videoBox-min" class="pseudoLink" href="">
    <img class="window-button" src="<?php echo $container->getUrl()->getHttpDomain(); ?>/public_folder/plugin/Bootstrap-icons/chevron-contract.svg">
  </a>
  <a id="videoBox-expand" class="pseudoLink" href="" style="display: none;">
    <img class="window-button" src="<?php echo $container->getUrl()->getHttpDomain(); ?>/public_folder/plugin/Bootstrap-icons/chevron-expand.svg">
  </a>
  <a id="videoBox-pause" class="pseudoLink" href="" style="display: none;">
    <img class="window-button" src="<?php echo $container->getUrl()->getHttpDomain(); ?>/public_folder/plugin/Bootstrap-icons/pause.svg">
  </a>
  <a id="videoBox-pause-active" class="pseudoLink" href="">
    <img class="window-button" src="<?php echo $container->getUrl()->getHttpDomain(); ?>/public_folder/plugin/Bootstrap-icons/pause-fill.svg">
  </a>
  <a id="videoBox-play" class="pseudoLink" href="">
    <img class="window-button" src="<?php echo $container->getUrl()->getHttpDomain(); ?>/public_folder/plugin/Bootstrap-icons/play.svg">
  </a>
  <a id="videoBox-play-active" class="pseudoLink" href="" style="display: none;">
    <img class="window-button" src="<?php echo $container->getUrl()->getHttpDomain(); ?>/public_folder/plugin/Bootstrap-icons/play-fill.svg">
  </a>
</div>
<div id="videoBox-player">
  <!--
  <video
    id="videoPlayer"
    title="Advertisement"
    webkit-playsinline="true"
    playsinline="true"
    style="background-color: rgb(0, 0, 0); position: absolute; width: 320px;"
    src="<?php echo $container->getUrl()->getHttpDomain(); ?>/videoPlayer/play/dasdasd">
  </video> -->
  <video id="videoPlayer" class="video-js" width="320" controls>
  <!-- <video id="videoPlayer" width="320" controls data-setup="{}"> -->
    <source src="<?php echo $container->getUrl()->getHttpDomain(); ?>/videoPlayer/play/<?php echo $video->getCode().'.'.$video->getExtension(); ?>" type="video/mp4">
    Your browser does not support the video tag.
  </video>
</div>
<script>
  // console.log(document.getElementById('videoPlayer'));
    // document.getElementById('videoPlayer').play();
    $('body').on('click', '#videoBox-close', function() {
      document.getElementById('videoPlayer').pause();
      $('#videoBox').remove();
    });

    $('body').on('click', '#videoBox-min', function() {
      VideoBox.minimalize();
    });

    $('body').on('click', '#videoBox-expand', function() {
      VideoBox.expand();
    });

    $('body').on('dblclick', '#videoBox-header', function() {
      // console.log('dblclick!!!!!');
      if ($('#videoBox-player').is(":visible")) {
        VideoBox.minimalize();
      } else {
        VideoBox.expand();
      }
    });

    $('body').on('click', '#videoBox-play', function() {
      document.getElementById('videoPlayer').play();
    });

    $('body').on('click', '#videoBox-pause', function() {
      document.getElementById('videoPlayer').pause();
    });

    document.getElementById('videoPlayer').addEventListener('play', function(e) { // Repeat this for other events
      $('#videoBox-play-active').show();
      $('#videoBox-play').hide();
      $('#videoBox-pause-active').hide();
      $('#videoBox-pause').show();
    });

    document.getElementById('videoPlayer').addEventListener('pause', function(e) { // Repeat this for other events
      $('#videoBox-play-active').hide();
      $('#videoBox-play').show();
      $('#videoBox-pause-active').show();
      $('#videoBox-pause').hide();
    });

    $('body').on('keyup', function(e) {
      let nicEditing = $(e.target).hasClass('nicEdit-main');
      if (e.keyCode == 32 && (!nicEditing && !$("input[type=text]").is(":focus") && !$("textarea").is(":focus"))) {
        // let playing = document.getElementById('videoPlayer').stream.paused;
        var myVideo=document.getElementById("videoPlayer"); 
        if (myVideo.paused) {
          myVideo.play(); 
        }
        else { 
          myVideo.pause(); 
        } 
        // console.log(playing);
      }
    });
</script>

<!-- <script>
  $(document).ready(function() {
  document.getElementById('videoPlayer').play();
});
</script> -->