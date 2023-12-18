<style>
.facebookCoverImageSize {
    width: 938px;
    height: 348px;
}
.banner-bar {
    width: 100%;
    background-color: #fff;
}
.logo-container {
    border: 0px;
    padding: 10px;
    width: 938px;
    height: 348px;
    /* background-color: #de7845; */
    background-color: #86c2ee;
    float: left;
}
.logo-image {
    width: 900px;
    height: 348px;
}

</style>
    <!-- <div class="logoBar">
        <a style="cursor:pointer;" class="ajaxCallerLink" href="http://localhost/elastisite/webroot">
            <div class="logoContainer">
                <img class ="logoImage" src="http://localhost/elastisite/webroot/elastisite/image/logo/logo_labeled_light_secondary.svg" onerror="console.log(onerror);">
            </div>
        </a>
    </div> -->

    <!-- <img class="logo-image" src="/public_folder/ESlogo/logo_256_light.svg" onerror="console.log(onerror);"> -->

    <div class="banner-bar">
        <div class="logo-container">
            <img class="logo-image" src="/public_folder/ESlogo/facebook_profile_img.svg" onerror="console.log(onerror);">
            <!-- <img class ="logoImage" src="http://localhost/elastisite/webroot/elastisite/image/logo/logo_labeled_dark.svg" onerror="console.log(onerror);"> -->
        </div>
    </div>

<script>
var title = 'Webáruház';
$('.bannerPart2-pageTitle').fadeOut(100, function(){
    $('.bannerPart2-pageTitle').html(title);
    $('.bannerPart2-pageTitle').fadeIn(500);
});
</script>