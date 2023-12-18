
<div id="cookieBox-summarizer-container">
    <!-- <div class="cookieBox-summarizer-title">
    <?php echo trans('cookies.refused'); ?>
    </div> -->
    <div class="cookieBox-summarizer-acceptedCounter DefaultFontBold">
    <?php echo $acceptedCount; ?>
    </div>
    <div class="cookieBox-summarizer-refusedCounter DefaultFontBold">
    <?php echo $refusedCount; ?>
    </div>
    <!-- <img style="width: 50px;" src="/image/cookie.png"> -->
</div>

<style>
#cookieBox-summarizer-container {
    position: fixed;
    bottom: 24px;
    left: 10px;
    width: 50px; 
    height: 50px; 
    cursor: pointer;
    /* background-color: #fff; */
    background-image: url('/image/cookie.png');
    background-repeat: no-repeat;
    background-size: 50px 50px;
    z-index: 10000000;
    box-shadow: rgba(0, 0, 0, 0.25) 0px 14px 28px, rgba(0, 0, 0, 0.22) 0px 10px 10px;
    text-align: center;
    border-radius: 50%;
    /* margin-top: auto;
    margin-bottom: auto; */
}
.cookieBox-summarizer-title {
    font-size: 10px;
}
.cookieBox-summarizer-acceptedCounter {
    /* color: #8c160d; */
    font-size: 18px;
    color: #3de461;
    text-shadow: 0px 0px 20px #fff, 0px 0px 20px #fff;
    /* background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkOAMAANIAzr59FiYAAAAASUVORK5CYII=') !important; */
    width: auto;
}
.cookieBox-summarizer-refusedCounter {
    /* color: #8c160d; */
    font-size: 18px;
    color: #fe779b;
    text-shadow: 0px 0px 20px #fff, 0px 0px 20px #fff;
    /* background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkOAMAANIAzr59FiYAAAAASUVORK5CYII=') !important; */
}
</style>