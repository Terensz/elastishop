<!-- <div class="widgetWrapper">
    <div class="widgetWrapper-info" style="margin-bottom: 0px;">
        <?php echo trans('webshop.is.inactive.info'); ?>
    </div>
</div> -->
<div class="pc-container">
	<div class="pcoded-content">

        <div class="card">
            <div class="bg-primary text-white card-header d-flex justify-content-between align-items-center">
                <div class="card-header-textContainer">
                    <h6 class="mb-0 text-white"><?php echo trans('information'); ?></h6>
                </div>
            </div>
            <div class="card-body">
                <span>
                    <?php echo trans('webshop.is.inactive.info'); ?>
                </span>
            </div>
            <?php if (App::getContainer()->isGranted('viewSystemAdminContent')): ?>
            <div class="card-footer">
                <span>
                    Mivel adminisztrátorként van bejelentkezve, adunk néhány további információt:<br>
                    <br>
                    Mielőtt aktiválná a webáruházát, győzödjön meg arról, hogy a termékei fel vannak töltve és be vannak árazva.<br>
                    <br>
                    A webáruház kativálásának menete:<br>
                    1. lépésként kattintson a jobb felső sarokban levő fogaskerékre. Ezzel a webhely-üzemeltetői felületre fog kerülni.<br>
                    2. A webhely-üzemeltetői felületen görgessen le a bal oldalon található menü "WEBÁRUHÁZ ADMINISZTRÁCIÓJA" bekezdéséig, és ott kattintson a "Beállítások" linkre<br>
                    3. Nyissa meg a szerkesztőablakot azzal, hogy a táblázatra kattint, majd a "Webáruház aktív" beállítási lehetőséget állítsa át "Igaz"-ra.<br>
                    <?php 
                    // echo trans('webshop.is.inactive.info'); 
                    ?>
                </span>
            </div>
            <?php endif; ?>
        </div>

    </div>
</div>