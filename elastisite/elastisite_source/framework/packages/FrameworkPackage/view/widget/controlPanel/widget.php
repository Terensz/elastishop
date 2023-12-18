<?php 
// dump(App::getContainer()->getUrl()->getMainRouteRequest() == 'admin');exit;
?>

<?php if ($grantedViewProjectAdminContent): ?>
    <style>
        .svg-holder {
            padding: 4px;
            cursor: pointer;
        }
    </style>
    <div id="cp-viewState" style="display: none;"><?php echo $projectAdminView ? 'true' : 'false' ?></div>
    <div id="cp-frame" style="position: fixed; top: 10px; right: 10px; z-index: 21000; height: 120px; background-color: #fff; box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;">
        <div id="cp-content" style="float: left; width: 500px; height: 100%; display: none;">
            <a class="ajaxCallerLink" href="/"><?php echo trans('homepage'); ?></a>
            <a class="ajaxCallerLink" href="/admin"><?php echo trans('admin'); ?></a>
        </div>
        <div id="cp-quickMenu" style="z-index: 10000;
            float: left;
             width: 40px;
             box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px; ">
            <!-- <img style="height: 50px; width: 32px;" class="logo-image" src="/public_folder/plugin/Bootstrap-icons/chevron-double-right.svg"> -->
            <a class="ajaxCallerLink" href="/" title="<?php echo trans('load.homepage'); ?>">
                <div class="svg-holder">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="<?php echo App::getContainer()->getUrl()->getPageRoute()->getName() == 'homepage' ? '#3561e8' : '#9b9b9b'; ?>" class="bi bi-house" viewBox="0 0 16 16">
                        <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5ZM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5 5 5Z"/>
                    </svg>
                </div>
            </a>
            <a class="ajaxCallerLink" href="/admin" title="A Webhely-üzemeltetői terület betöltése">
                <div class="svg-holder">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="<?php echo App::getContainer()->getUrl()->getMainRouteRequest() == 'admin' ? '#3561e8' : '#9b9b9b'; ?>" class="bi bi-gear-fill" viewBox="0 0 16 16">
                        <path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z"/>
                    </svg>
                </div>
            </a>
            <?php if (App::getContainer()->getUrl()->getMainRouteRequest() != 'admin'): ?>
            <a id="cp-eye" title="A szerkesztőnézet ki- és bekapcsolása">
                <div class="svg-holder">
                    <!-- <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="<?php echo $projectAdminView ? '#e12a4f' : '#9b9b9b' ?>" class="bi bi-eye" viewBox="0 0 16 16">
                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                    </svg> -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="<?php echo $projectAdminView ? '#3561e8' : '#9b9b9b' ?>" class="bi bi-eye-fill" viewBox="0 0 16 16">
                        <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                        <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                    </svg>
                </div>
            </a>
            <?php else: ?>
                <div class="svg-holder">
                    <!-- <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="<?php echo $projectAdminView ? '#e12a4f' : '#9b9b9b' ?>" class="bi bi-eye" viewBox="0 0 16 16">
                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                    </svg> -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="<?php echo '#9b9b9b'; ?>" class="bi bi-eye-fill" viewBox="0 0 16 16">
                        <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                        <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                    </svg>
                </div>
            <?php endif; ?>
            <!-- <a id="cp-hider">
                <div class="svg-holder">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#9b9b9b" class="bi bi-chevron-double-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M3.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L9.293 8 3.646 2.354a.5.5 0 0 1 0-.708z"/>
                        <path fill-rule="evenodd" d="M7.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L13.293 8 7.646 2.354a.5.5 0 0 1 0-.708z"/>
                    </svg>
                </div>
            </a> -->
        </div>
    </div>

<script>
    $('document').ready(function() {
        $('#cp-hider').off('click');
        $('#cp-hider').on('click', function(e) {
            e.preventDefault();
            if ($('#cp-content').is(':hidden')) {
                $('#cp-content').show();
            } else {
                $('#cp-content').hide();
            }
        });

        $('#cp-eye').off('click');
        $('#cp-eye').on('click', function(e) {
            e.preventDefault();
            if ($('#cp-viewState').html() == 'false') {
                $('#cp-viewState').html('true');
            } else {
                $('#cp-viewState').html('false');
            }
            CP.viewStateChange = true;
            CP.load();
        });
    });
</script>
<?php else: ?>

<?php endif; ?>