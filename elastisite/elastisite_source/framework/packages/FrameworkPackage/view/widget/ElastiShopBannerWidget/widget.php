<style>
.banner-bar {
    float: left;
    background-image: url('/image/bg.png');
    /* background-color: #f7de89; */
    width: 100%;
    height: 100px;
    /* height: 100%; */
    /* background-color: #fff; */
}
.transition-container {
    width: 60px;
    margin-left: 540px;
    height: 100px;

    /* float: right; */
}
.transition {
    background-image: url('/image/bgTransition.png');
    width: 60px;
    height: 100px;
    display: block;
}
.banner-container {
    float: left;
    /* border: 1px solid #000; */
    /* background-color: #f7de89; */
    /* background-image: url('/elastisite/image/logo/es_logo_bg2.png'); */
    /* width: 600px; */
}
.banner-inner-container {
    float: left;
    border: 0px;
    padding: 10px;
    height: 100px;
    width: 600px;
}
.logo-container {
    float: left;
}
.banner-text-container {
    font-family: Neuropol-Regular;
    float: left;
    margin-left: 10px;
    overflow: clip;
    /* border: 1px solid #000; */
}
.banner-text-main {
    font-size: 40px;
    /* font-weight: bold; */
    font-weight: 900 !important;
    /* text-transform: uppercase; */
    padding-bottom: 10px;
    padding-right: 10px;
}
.banner-text-sub {
    font-size: 36px;
    color: #fff8b7;
    font-weight: normal;
    /* text-transform: uppercase; */
}
.logo-image {
    max-width: 400px;
    width: auto;
    height: 100px;
    /* max-width: 100%;
    height: auto; */
    /* background-color: #fff; */
    /* background-image: url('../image/FFFFFF-0.95.png'); */
}

/* .titleImageContainer {
    position: relative;
    z-index: 0;
    background-color: #fff;
    background-position: center top;
    background-size: cover;
    border: 0px;
    height: 46px;
    box-shadow:0 6px 10px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19) !important;
} */

/* .titleImageVeil {
    position: relative;
    top: 0px;
    margin-left: auto;
    margin-right: auto;
    width: 100%;
    height: 100%;
} */

/* .bannerPart2-container {
    margin-left: 600px; 
    height: 220px;
} */

.banner-pageTitle-frame {
    height: 100%; 
    width: 100%; 
    margin-left:600px;
    /* margin-left: -20%;  */
    padding-top: 20px; 
    text-align: center;
    border: 0px solid #c0c0c0;
    background-color: #77d1dd;
}

.banner-pageTitle {
    /* position: relative; */
    /* top: 40%; */
    /* z-index: 1; */
    /* text-align: center; */
    /* left: -14%; */
    /* font-family: 'Muli'; */
    /* font-family: Neuropol-Regular; */
    margin-left: -600px;
    /* margin-left: 20%; */
    font-size: 30px;
    /* color: #000; */
    color: #f3f9f9;
    font-style: italic;
    /* text-align: center; */
    padding: 4px;
    overflow: hidden;
    display: inline-block;
}

.banner-bar-bottom {
    position: absolute;
    top: 64px;
    width: 100%;
    /* height: 40px; */
    /* background-color: #69bec9; */
    background-color: #77d1dd;
    font-size: 20px;
    text-align: center;
    /* color: #000; */
    color: #f3f9f9;
    font-style: italic;
    /* padding-top: 25px; */
    -webkit-box-shadow: 0px -4px 3px rgba(0, 0, 0, 0.25);
    -moz-box-shadow: 0px -4px 3px rgba(0, 0, 0, 0.25);
    box-shadow: 0px -4px 3px rgba(0, 0, 0, 0.25);
    padding: 8px;
    display: none;
}

.banner-block-container {
    font-weight: normal;
    font-size: 24px;
    padding-top: 8px;
}

.banner-escube-cell-white {
    background-color: #fff;
    width: 10px;
    height: 10px;
    border: 3px solid #e3af29;
}

.banner-escube-cell-dark {
    background-color: #254767;
    width: 10px;
    height: 10px;
    border: 3px solid #e3af29;
}

.banner-shop-span {
    color: #208390;
    /* text-shadow: 2px 7px 5px rgba(0,0,0,0.3),
			0px -4px 10px rgba(255,255,255,0.3); */
    /* -webkit-text-stroke: 1px #282828;
    text-shadow: 0px 4px 4px #282828; */
    text-shadow: 3px 4px 7px rgba(81,67,21,0.8);
    /* height: 40px; */
}

@media (max-width: 1400px) {
    /* .logo-container {
        float: none;
    } */
    .banner-pageTitle-container {
        /* height: 60px; */
    	margin-left: 0px; 
    }
    .bannerPart-pageTitle {
        max-width: 600px;
    }
    .banner-pageTitle-frame {
        word-break: break-all;
        /* padding-top: 10px;  */
        display: none;
    }
    .banner-pageTitle {
        display: none;
    }
    .transition {
        display: none;
    }
    /* .transition-container {
        display: none;
    } */
    .banner-bar-bottom {
        margin-left: -600px;
        display: inline-block;
        font-size: 16px;
    }
}
</style>

<div class="banner-bar">
    <div class="banner-container">
        <div class="banner-inner-container">
            <div class="banner-text-container">
                <div>
                    <div style="float: left;">
                        <div class="banner-text-main">

                            <div class="banner-block-container" style="float: left; margin-right: 12px; margin-top: 0px;">
                                <table>
                                    <tbody><tr>
                                        <td class="banner-escube-cell-white"></td>
                                        <td class="banner-escube-cell-white"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class="banner-escube-cell-white"></td>
                                        <td class="banner-escube-cell-white"></td>
                                        <td class="banner-escube-cell-dark"></td>
                                    </tr>
                                    <tr>
                                        <td class="banner-escube-cell-white"></td>
                                        <td class="banner-escube-cell-white"></td>
                                        <td class="banner-escube-cell-white"></td>
                                    </tr>
                                </tbody></table>
                            </div>


                            Elasti<span class="banner-shop-span">Shop</span>
                        </div>
                        <div class="banner-text-main">
                            
                        </div>
                    </div>
                    <div class="logo-container">
                        <!-- <img style="width: 180px;" src="/image/banana_blue_big.png"> -->
                    </div>
                </div>
                <div class="banner-text-sub" style="clear: both;">
                    
                </div>
            </div>
        </div>
        <div class="transition-container">
            <div class="transition">
            &nbsp;
            </div>
        </div>
        <!-- <img class ="logoImage" src="<?php echo $container->getUrl()->getHttpDomain(); ?>/elastisite/image/logo/logo_labeled_dark.svg" onerror="console.log(onerror);"> -->
    </div>
    <div class="banner-pageTitle-frame">
        <div class="banner-pageTitle"></div>
    </div>
    <div class="banner-bar-bottom"></div>
</div>

<script>
var title = '<?php echo trans($container->getRouting()->getPageRoute()->getTitle()); ?>';
// $('.banner-pageTitle').fadeOut(100, function(){
//     $('.banner-pageTitle').html(title);
//     $('.banner-bar-bottom').html(title);
//     // $('.banner-pageTitle').fadeIn(500);
// });
$('.banner-pageTitle').html(title);
$('.banner-bar-bottom').html(title);
</script>