<style>
/* body {
    background-color: #e4e4e4;
} */
.background-parallax-wrapper {
    top: 0px;
    /* z-index: -300; */
    background-repeat: no-repeat;
    position: absolute;
}

#backgroundWrapper {
    top: 0px;
    z-index: -300;
    /* background-color: #e4e4e4; */
    background-repeat: no-repeat;
    position: absolute;
    height: 100%;
    width: 100%;
    /* border: 10px solid #bbbbbb; */
}

.backgroundImage {
    position: absolute;
    min-height: 96%;
    height: auto;
    width: 100%;
<?php
if ($backgroundImage) {
?>
    background: url('/background/image/Simple/<?php echo $backgroundImage->getFileName(); ?>') no-repeat center center;
<?php
}
?>
    /* background-repeat: repeat;
    background-size: 100% auto; */
    box-shadow: 0 0px 8px #000;
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
}

</style>
<div class="background-parallax-wrapper preventTouchScroll" style="width: 100%; margin: auto; <?php echo $backgroundImage ? 'height: '.$backgroundImage->getHeight().'px; ' : ''; ?>touch-action: none;">
    <div id="backgroundWrapper" class="background-parallax preventTouchScroll" style="width: 100%; height: 100%; touch-action: none;">
<?php
// dump($backgroundImage);exit;
if ($theme != 'empty') {
?>
    <div class="backgroundImage" class="middleOpacity">
    </div>
<?php
}
?>
</div>
<script>
var BackgroundEngine = {
    set: function(theme) {

    },
    remove: function(theme) {

    },
    changeTheme: function(theme) {

    }
};

// var scrollar = new Scrollar(null, {
//     // the parent of scrollar object,
//     wrapper: null,
//     // direction of the scroll (supports only vertical for now)
//     vertical: true, // horizontal: true,
//     // speed of the blocks (data-scrollar tags override this config)
//     // movement value to 1px scroll (e.g. 0.6 : 1 means the element will scroll 0.6px when the window is scrolled 1px)
//     speed: 0.6,
//     // amount of travel until stop (in px)
//     // prevent extra scrolling
//     distance: 1000,
//     // callback when element is moved
//     callback: null
// });

$(document).ready(function() {
    if (ElastiTools.mobileAndTabletCheck() == false) {
        // var rellax = new Rellax('.background-parallax-wrapper', {
        //     speed: -18,
        //     callback: function (e) {
        //     }
        // });
    }
});
</script>
