<?php 

// dump($container->getSkinData());exit;
$route = $this->getRouting()->getPageRoute();
// dump($route);exit;

?>

<style>
.titleImageContainer {
    position: relative;
    z-index: 0;
    background-color: #fff;
    background-position: center top;
    background-size: cover;
    /* margin-top:4px; */
    /* background-size: auto; */
    border: 0px;
    height: 46px;
    box-shadow:0 6px 10px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19) !important;
}

.titleImageMid {
    /* position: relative; */
    /* top: 40%; */
    /* z-index: 1; */
    /* text-align: center; */
    /* left: -14%; */
    /* font-family: 'Muli'; */
    font-family: Neuropol-Regular;
    font-size: 32px;
    color: #000;
    text-align: center;
    padding: 4px;
}

.titleImageVeil {
    position: relative;
    top: 0px;
    margin-left: auto;
    margin-right: auto;
    width: 100%;
    height: 100%;
}
</style>

<!-- <div class="titleImageContainer">
    <div class="titleImageVeil">
        <div class="titleImageMid"></div>
    </div>
</div>
<script>
var title = '<?php echo trans($container->getRouting()->getPageRoute()->getTitle()); ?>';
$('.titleImageMid').fadeOut(100, function(){
    $('.titleImageMid').html(title);
    $('.titleImageMid').fadeIn(500);
});
</script> -->
