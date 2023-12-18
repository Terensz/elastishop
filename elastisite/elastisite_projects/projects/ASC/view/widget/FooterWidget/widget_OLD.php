<?php 

?>

<style>
.footer-wrapper {
    padding: 10px;
}

.footer-container {
    color: #969696;
}

.elastiSite {
    margin: 46px auto;
	text-align: center;
	text-shadow: -1px -1px 0px rgba(255,255,255,0.3), 1px 1px 0px rgba(0,0,0,0.8);
	color: #f1f1f1;
	/* opacity: 0.8; */
	font: 400 16px Neuropol-Regular;
    /* font-family: Neuropol-Regular; */
}

.footer-block-container {
    font-weight: normal;
    font-size: 16px;
    padding-top: 6px;
    /* border: 1px solid #fff; */
}

.footer-block-container ul {
    /* background-color: #c0c0c0; */
    padding: 0px;
    margin: 0px;
    margin-left: 20px;
    padding-top: 2px;
    padding-bottom: 6px;
}

.footer-block-title {
    font-weight: bold;
    color: #f1f1f1;
}

.escube-cell-white {
    background-color: #fff;
    width: 7px;
    height: 7px;
    border: 2px solid #494949;
    /* margin: 10px;
    padding: 10px; */
}

.escube-cell-dark {
    background-color: #254767;
    width: 7px;
    height: 7px;
    border: 2px solid #494949;
}


.footer-container-block {
    font: 34px Comfortaa-Regular;
}

.footer-container-block-logo-padding {
    padding-left: 14px;
    padding-right: 14px;
}
.footer-text-shadow-dark {
    -webkit-text-stroke: 1px #282828;
    text-shadow: 0px 4px 4px #282828;
    /* box-shadow: inset 10px 0 5px -2px #888; */
    /* box-shadow: inset -25px 0 25px -25px rgba(0,0,0,0.8); */
    /* box-shadow: inset rgba(0, 0, 0, 0.07) 0px 1px 2px, inset rgba(0, 0, 0, 0.07) 0px 2px 4px, inset rgba(0, 0, 0, 0.07) 0px 4px 8px, inset rgba(0, 0, 0, 0.07) 0px 8px 16px, inset rgba(0, 0, 0, 0.07) 0px 16px 32px, inset rgba(0, 0, 0, 0.07) 0px 32px 64px; */
}
.footer-text-shadow-orange {
    color: #fcb31e;
    -webkit-text-stroke: 1px #b86525;
    text-shadow: 0px 4px 4px #181818;
    /* box-shadow: inset 25px 0 25px -25px rgba(0,0,0,0.8); */
    /* box-shadow: inset rgba(252, 179, 30, 0.07) 0px 1px 2px; */
}
</style>

<div class="footer-wrapper" style="position: relative; z-index: 3; padding: 10px;">
    <div class="footer-container">

        <div class="row">
            <div class="col-xl-12 col-lg-12">

                <div class="footer-container-block footer-text-shadow-dark" style="float: left;">
                Admin Scale Creator
                </div>

                <div style="clear: both;"></div>
            </div>
        </div>

        <div class="row">
            <!-- <div class="col-xl-12 col-lg-12" style="min-width: 390px;"> -->

            <div class="col-xl-12 col-lg-12" style="border: 2px solid #494949;">
                <div class="row footer-block-container">
                    <div class="col-lg-4">
                        <div class="footer-block-title"><?php echo trans('contact'); ?></div>
                        <ul>
                            <li>
                                <a class="ajaxCallerLink" href="/contact"><?php echo trans('contact'); ?></a>
                            </li>
                        </ul>    
                    </div>
                    <div class="col-lg-4">
                        <div class="footer-block-title"><?php echo trans('handling.personal.data'); ?></div>
                        <ul>
                            <li>
                                <a class="ajaxCallerLink" href="/documents/cookie-info"><?php echo trans('cookie.handling.information'); ?></a>
                            </li>
                            <li>
                                <a class="ajaxCallerLink" href="/documents/privacy-statement"><?php echo trans('privacy.statement'); ?></a>
                            </li>
                        </ul>    
                    </div>
                    <div class="col-lg-4">
                        <div class="footer-block-title"><?php echo trans('special.thanks'); ?></div>
                        <ul>
                            <!-- <li>
                                <a href="https://www.mixcloud.com/fitnessdjofficial/" target="_blank" title="Fitness DJ">Fitness DJ</a>
                            </li> -->
                            <li>
                                <a href="https://www.flaticon.com/free-icons/cookie" target="_blank" title="cookie icons">Cookie icons created by Nikita Golubev - Flaticon</a>
                            </li>
                        </ul>    
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
<div id="stickyFooterStart"></div>
<div class="stickyFooterDiv"></div>