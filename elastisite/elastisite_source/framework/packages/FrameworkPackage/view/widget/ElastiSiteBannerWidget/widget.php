<style>
.banner-bar {
    width: 100%;
    background-color: #fff;
}
.logo-container {
    border: 0px;
    padding: 10px;
    height: 120px;
    /* background-color: #c0c0c0; */
    background-image: url('/elastisite/image/logo/es_logo_bg2.png');
    width: 600px;
    float: left;
}
.logo-image {
    max-width: 400px;
    width: auto;
    height: 120px;
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

.bannerPart2-container {
    margin-left: 600px; 
    height: 120px;
}

.bannerPart2-pageTitle-frame {
    height: 100%; 
    width: 100%; 
    margin-left: -20%; 
    padding-top: 40px; 
    text-align: center;
    border: 0px solid #c0c0c0;
}

.bannerPart2-pageTitle {
    /* position: relative; */
    /* top: 40%; */
    /* z-index: 1; */
    /* text-align: center; */
    /* left: -14%; */
    /* font-family: 'Muli'; */
    font-family: Neuropol-Regular;
    font-size: 30px;
    color: #000;
    /* text-align: center; */
    padding: 4px;
    overflow: hidden;
}

@media (max-width: 1400px) {
    .logo-container {
        float: none;
    }
    .bannerPart2-container {
        height: 60px;
    	margin-left: 0px; 
    }
    .bannerPart2-pageTitle-frame {
        margin-left: 0%; 
        padding-top: 10px; 
    }
    .bannerPart2-pageTitle {
        font-size: 24px;
    }
}


</style>

<div class="banner-bar">
    <div class="logo-container">
        <img class ="logo-image" src="/public_folder/ESlogo/logo_labeled_light_secondary.svg" onerror="console.log(onerror);">
        <!-- <img class ="logoImage" src="<?php echo App::getContainer()->getUrl()->getHttpDomain(); ?>/elastisite/image/logo/logo_labeled_dark.svg" onerror="console.log(onerror);"> -->
    </div>
    <div class="bannerPart2-container">
        <div class="bannerPart2-pageTitle-frame">
            <div class="bannerPart2-pageTitle"></div>
        </div>
    </div>
</div>

<script>
var title = '<?php echo trans(App::getContainer()->getRouting()->getPageRoute()->getTitle()); ?>';
$('.bannerPart2-pageTitle').fadeOut(100, function(){
    $('.bannerPart2-pageTitle').html(title);
    $('.bannerPart2-pageTitle').fadeIn(500);
});
</script>