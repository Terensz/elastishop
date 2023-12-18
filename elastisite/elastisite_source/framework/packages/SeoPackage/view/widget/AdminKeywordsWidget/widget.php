<div class="widgetWrapper-info">
    <b>Kulcsszavak</b>: szavak, amik kifejezik azt, hogy miről szól a webhely. A keresőmotorok (pl. Google, BING) részben ezek alapján osztályozzák a webhelyet keresőszavakhoz találatként. 
    Ezért fontos a precíz és kifejező kulcsszó.
</div>

<div class="widgetWrapper">
    <form class="form" id="SeoPackage_addKeyword_form" name="SeoPackage_addKeyword_form" action="" method="POST">
        <div class="row">
            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                <div class="form-group formLabel">
                    <label>
                        <b><?php echo trans('new.keyword'); ?></b>
                    </label>
                </div>
            </div>
            <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
                <div class="form-group">
                <div class="input-group">
                    <input name="SeoPackage_addKeyword_keyword" id="SeoPackage_addKeyword_keyword" type="text" class="inputField form-control enterSubmits" value="" aria-describedby="" placeholder="">
                </div>
                    <div class="validationMessage error" id="SeoPackage_addKeyword_keyword-validationMessage" style="padding-top:4px;"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
            </div>
            <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
                <div class="form-group">
                    <button name="SeoPackage_addKeyword_submit" id="SeoPackage_addKeyword_submit" type="button" class="btn btn-secondary btn-block" style="width: 200px;" 
                        onclick="Keywords.add(event);" value="">Mentés</button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div id="selectedVideo-message" style="padding-top:4px;"></div>
            </div>
        </div>

    </form>
</div>

<?php  

$keywords = ['alma', 'körte', 'szilva', 'banán', 'sárgabarack'];

?>


<div id="existingKeywords-container">
<?php 
include('existingKeywords.php'); 
?>
</div>
<script>
    var Keywords = {
        list: function() {
            var ajaxData = {};
            console.log(ajaxData);
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/keywords/list',
                'data': ajaxData,
                'async': false,
                'success': function(response) {
                    ElastiTools.checkResponse(response);
                    console.log('Keywords.list');
                    $('#existingKeywords-container').html(response.view);
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                    // LoadingHandler.stop();
                },
            });
        },
        add: function() {
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/keywords/add',
                'data': {
                    'keyword': $('#SeoPackage_addKeyword_keyword').val()
                },
                'async': false,
                'success': function(response) {
                    ElastiTools.checkResponse(response);
                    Keywords.list();
                    LoadingHandler.stop();
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                    // LoadingHandler.stop();
                },
            });
        },
        delete: function(event, id) {
            event.preventDefault();
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/keywords/delete',
                'data': {
                    'id': id
                },
                'async': false,
                'success': function(response) {
                    ElastiTools.checkResponse(response);
                    // console.log(response);
                    Keywords.list();
                    LoadingHandler.stop();
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                    // LoadingHandler.stop();
                },
            });
        }
    };
</script>