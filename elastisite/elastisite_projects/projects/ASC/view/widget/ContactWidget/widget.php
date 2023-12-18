
<?php 
// dump(App::getContainer()->getRequest()->get('viewState'));
// dump(App::getContainer()->getSession()->get('site_adminViewState'));
// dump(App::getContainer()->getUrl());//exit;
?>
<?php 
// dump(dirname(__FILE__));
$dir = dirname(__FILE__);
$targetDir = str_replace('ContactWidget', 'HomepageWidget', $dir);
// $targetDir = str_replace('widget.php', 'vista.php', $targetDir);
// include($targetDir.'/'.'vista.php');
?>


<?php 
if (App::getContainer()->isGranted('viewSystemAdminContent')):
?>
<?php 
endif;
?>

<div class="widgetWrapper lessObscure" style="position: relative; z-index: 3; padding: 10px;">
    <!-- <div class="textBox-dark textBox-dark textBox-roundedCorners textBox-black-boxShadow" style="margin-left: 0; margin-right: auto;">
        Ha bármilyen kérdés felmerült Önben, amire nem kapott választ a webhelyen, lehetősége van üzenetet küldeni.<br>
    </div> -->
    <div class="widgetWrapper-info" style="margin-left: 0; margin-right: auto;">
        Ha bármilyen kérdés felmerült Önben, amire nem kapott választ a webhelyen, lehetősége van üzenetet küldeni.<br>
    </div>

    <div id="ElastiSite_sendUsMessage_form-container">
        <?php 
        include('sendUsMessage.php'); 
        ?>
    </div>
</div>

<script>
    var SendUsMessage = {
        loadForm: function(submitted) {
            var ajaxData = {};
            if (submitted === true) {
                var form = $('#ElastiSite_sendUsMessage_form');
                ajaxData = form.serialize();
            }
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo App::getContainer()->getUrl()->getHttpDomain(); ?>/ajax/sendUsMessageForm',
                'data': ajaxData,
                'async': true,
                'success': function(response) {
                    ElastiTools.checkResponse(response);
                    $('#ElastiSite_sendUsMessage_form-container').html(response.view);
                    LoadingHandler.stop();
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                    LoadingHandler.stop();
                },
            });
            // LoadingHandler.stop();
        },
    };
</script>