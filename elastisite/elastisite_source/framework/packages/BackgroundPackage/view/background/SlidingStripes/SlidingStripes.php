<style>
.background-parallax-wrapper {
    top: 0px;
    /* z-index: -300; */
    background-repeat: no-repeat;
    position: absolute;
    width: 100%;
    backface-visibility: hidden;
    touch-action: none;
    background-attachment: fixed;
}

#backgroundWrapper {
    top: 0px;
    z-index: -300;
    /* background-color: #e4e4e4; */
    background-repeat: no-repeat;
    /* background-attachment: scroll !important; */
    position: absolute;
    /* height: 100%; */
    width: 100%;
}

/* div#backgroundWrapper:after {
	position: absolute;
	top: 0px;
	left: 0px;
	right: 0px;
	bottom: 0px;
	z-index: -1;
	content: "";
	transform: translateZ(-1px) scale(2);
	-webkit-transform: translateZ(-1px) scale(2);
} */

<?php

// dump($backgroundImages);exit;
$width = $backgroundImages[0]->getWidth();
$height = $backgroundImages[0]->getHeight();

for ($i = 0; $i < count($backgroundImages); $i++) {
?>
    <?php echo '.bgStripe'.($i + 1); ?> {
        position: absolute;
        top: <?php echo ($i * (10 + $height)) ?>px;
        height: <?php echo $height; ?>px;
        background-color: #000;
        background-image: url('/image/background/big/SlidingStripes/<?php echo $backgroundImages[$i]->getFileName(); ?>');
        background-repeat: no-repeat;
        box-shadow: 0 0px 8px #000;
        max-width: 98%;
    }
<?php
}
?>

@media only screen and (min-width: 1800px) {
<?php
for ($i = 0; $i < count($backgroundImages); $i++) {
    $loopWidthPercent = 101 - (($i + 1) * 4);
    $loopWidth = $width - ($i * 70);
?>
    <?php echo '.bgStripe'.($i + 1); ?> {
        left: -<?php echo $loopWidthPercent; ?>%;
        width: <?php echo $loopWidthPercent; ?>%;
        max-width: <?php echo $loopWidth; ?>px;
    }

<?php
}
?>
}

@media only screen and (max-width: 1799px) {
<?php
for ($i = 0; $i < count($backgroundImages); $i++) {
    $loopWidthPercent = 101 - (($i + 1) * 4);
    $loopWidth = $width - ($i * 70);
?>
    <?php echo '.bgStripe'.($i + 1); ?> {
        left: -<?php echo $loopWidthPercent; ?>%;
        width: <?php echo $loopWidthPercent; ?>%;
        max-width: <?php echo $loopWidth; ?>px;
    }

<?php
}
?>
}


<?php 

$backgroundWrapperHeight = count($backgroundImages) * 170;

?>
/* #backgroundWrapper {
    border: 2px #000;
} */

</style>
<!-- <div id="backgroundWrapper" class="my-paroller" data-paroller-factor="0.3" data-paroller-type="foreground" data-paroller-direction="vertical"> -->
<div class="background-parallax-wrapper preventTouchScroll" style="height: <?php echo $backgroundWrapperHeight; ?>px;">
    <div id="backgroundWrapper" class="background-parallax preventTouchScroll" style="width: 100%; height: 100%; touch-action: none;">
<!-- <div id="backgroundWrapper"> -->
<?php
for ($i = 0; $i < count($backgroundImages); $i++) {
?>
            <div id="bgStripe<?php echo ($i + 1); ?>" class="preventTouchScroll background-parallax-off bgStripe bgStripe<?php echo ($i + 1); ?> middleOpacity" data-rellax-speed="-16" style="">
            </div>
<?php
}
?>

    </div>
</div>

<script>

if (typeof isOdd !== "function") {
    function isOdd(num) { return num % 2; }
}

var BackgroundEngine = {
    set: function(theme) {
        BackgroundEngine.slideInStripes(theme);
    },
    slideInStripes: function(theme) {
        $('#backgroundWrapper').fadeIn(800, function(){
            $('#backgroundWrapper').fadeIn(400);
            var bgStripeObjects = $('.bgStripe');
            for (var i = 0; i < bgStripeObjects.length; i++) {
                BackgroundEngine.slideInStripe($(bgStripeObjects[i]).attr('id'));
            }
        });
    },
    slideInStripe: function(bgStripeId) {
        let docWidth = document.body.clientWidth;
        let optimalWidth = <?php echo $container->getSkinData('sheetMaxWidth'); ?> + 400;
        let stripeWidth = $('#bgStripe1').width();
        let leftPos = 0;
        // console.log(docWidth, optimalWidth, (stripeWidth + 200), docWidth);
        if (docWidth > optimalWidth && (stripeWidth + 200) < docWidth) {
            leftPos = (docWidth - optimalWidth) / 2;
        } else {
            leftPos = docWidth / 100;
        }
        // let leftPos = 
        var num = parseInt(bgStripeId.slice(-1));
        if (isOdd(num) === 1) {
            $("#" + bgStripeId).animate({left: leftPos}, 800);
        } else {
            // var width = $(document).width();
            $('#' + bgStripeId).css('left', docWidth + 'px');
            $("#" + bgStripeId).animate({left: leftPos}, 800);
        }
    },
    remove: function(theme) {
        var bgStripeObjects = $('.bgStripe');
        for (var i = 0; i < bgStripeObjects.length; i++) {
            BackgroundEngine.slideOutStripe($(bgStripeObjects[i]).attr('id'));
        }
    },
    slideOutStripe: function(bgStripeId) {
        // var newStripe = $('#' + bgStripeId).clone();
        // $(newStripe).attr('id', bgStripeId + '_clone');
        // $('#' + bgStripeId).hide();
        // $('#' + bgStripeId).after(newStripe);
        // $('#' + bgStripeId).remove();

        var newId = $('#' + bgStripeId).attr('id') + '_clone';
        $('#' + newId).remove();

        $('#' + bgStripeId).attr('id', newId);

        // console.log(bgStripeId);
        // console.log($('#' + bgStripeId));
        // console.log(newId);
        // console.log($('#' + newId));

        var width = $(document).width();
        var num = parseInt(bgStripeId.slice(-1));
        if (isOdd(num) === 1) {
            $('#' + bgStripeId + '_clone').animate({left: width}, 300);
        } else {
            $('#' + bgStripeId + '_clone').animate({left: -($('#' + bgStripeId + '_clone').width())}, 300);
        }

        // $('#' + bgStripeId + '_clone').animate({left: width}, 800);
        // $('#' + bgStripeId + '_clone').animate({left: -($('#' + bgStripeId + '_clone').width())}, 800);
    },
    changeTheme: function(theme) {
<?php
for ($i = 0; $i < count($backgroundImages); $i++) {
?>
        // console.log('changeTheme: <?php echo $backgroundImages[$i]->getFileName(); ?>');

        // setInterval(function () {
        //     i++;
        //     if (i == images.length) {
        //         i = 0;
        //     }
        //     $("#dvImage").fadeOut("slow", function () {
        //         $('.bgStripe<?php echo ($i + 1); ?>').css("background-image", "url('<?php echo $container->getUrl()->getHttpDomain(); ?>/image/background/big/SlidingStripes/<?php echo $backgroundImages[$i]->getFileName(); ?>')");
        //         $(this).fadeIn("slow");
        //     });
        // }, 1000);

        // $('.bgStripe<?php echo ($i + 1); ?>').css('background-image', "url('<?php echo $container->getUrl()->getHttpDomain(); ?>/image/background/big/SlidingStripes/<?php echo $backgroundImages[$i]->getFileName(); ?>')");

        // $(".bgStripe<?php echo ($i + 1); ?>").stop().animate({opacity: 0},1000,function(){
        //     $(".bgStripe<?php echo ($i + 1); ?>").css({'background-image': "url('<?php echo $container->getUrl()->getHttpDomain(); ?>/image/background/big/SlidingStripes/<?php echo $backgroundImages[$i]->getFileName(); ?>')"})
        //         .animate({opacity: 1},{duration:1000});
        // });
<?php
}
?>
    }
};

$(document).ready(function() {
    BackgroundEngine.slideInStripes('<?php echo $theme; ?>');

    // if (ElastiTools.mobileAndTabletCheck() == false) {
    //     var scrollar = new Scrollar(".background-parallax", {
    //         wrapper: null,
    //         vertical: true,
    //         speed: 0.8,
    //         distance: null,
    //         callback: null
    //     });
    // }

    // $('#background-parallax').css('width', $(document).width() - 20);

    // var scene = document.getElementById('backgroundWrapper');
    // var parallaxInstance = new Parallax(scene);


    // $('.background-parallax').pagepiling();
	// $('.background-parallax').pagepiling({
	//     menu: null,
    //     direction: 'vertical',
    //     verticalCentered: true,
    //     sectionsColor: [],
    //     anchors: [],
    //     scrollingSpeed: 700,
    //     easing: 'swing',
    //     loopBottom: false,
    //     loopTop: false,
    //     css3: true,
    //     navigation: {
    //         'textColor': '#000',
    //         'bulletsColor': '#000',
    //         'position': 'right',
    //         'tooltips': ['section1', 'section2', 'section3', 'section4']
    //     },
    //    	normalScrollElements: null,
    //     normalScrollElementTouchThreshold: 5,
    //     touchSensitivity: 5,
    //     keyboardScrolling: true,
    //     sectionSelector: '.section',
    //     animateAnchor: false,

	// 	//events
	// 	onLeave: function(index, nextIndex, direction){},
	// 	afterLoad: function(anchorLink, index){},
	// 	afterRender: function(){},
	// });

    if (ElastiTools.mobileAndTabletCheck() == false) { 
        // var rellax = new Rellax('.background-parallax-wrapper', {
        //     speed: -18,
        //     callback: function (e) {
        //     }
        // });
    } 
    // else {
    //     $('.background-parallax').css('position', 'fixed');
    // }
});
</script>

<!-- </body>
</html> -->