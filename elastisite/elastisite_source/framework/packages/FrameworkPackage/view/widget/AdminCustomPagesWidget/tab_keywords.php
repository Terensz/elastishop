<div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <?php echo trans('description'); ?>
    </div>
    <div class="card-body">
        <div class="">
            <?php echo trans('description.description'); ?>
        </div>
    </div>
</div>

<!-- <div class="widgetWrapper-info">
</div> -->

<div class="widgetWrapper">
    <form class="form" id="FrameworkPackage_addDescription_form" name="FrameworkPackage_addDescription_form" action="" method="POST">

        <input name="FrameworkPackage_addDescription_originalDescription" id="FrameworkPackage_addDescription_originalDescription" type="hidden" class="inputField form-control" value="<?php echo $description; ?>" aria-describedby="" placeholder="">

        <!-- <div class="row">
            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                <div class="form-group formLabel">
                    <label>
                        <b><?php echo trans('description'); ?></b>
                    </label>
                </div>
            </div>
            <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
                <div class="form-group">
                <div class="input-group">
                    <textarea name="FrameworkPackage_addDescription_description" id="FrameworkPackage_addDescription_description" type="text" class="inputField form-control enterSubmits" value="" aria-describedby="" placeholder=""><?php echo $description; ?></textarea>
                </div>
                    <div class="validationMessage error" id="FrameworkPackage_addDescription_description-validationMessage" style="padding-top:4px;"></div>
                </div>
            </div>
        </div> -->

        <div class="mb-3">
            <label for="FrameworkPackage_addDescription_description" class="form-label"><?php echo trans('description'); ?></label>
            <div class="input-group has-validation">
                <input type="text" class="form-control inputField" name="FrameworkPackage_addDescription_description" id="FrameworkPackage_addDescription_description" maxlength="250" placeholder="" value="<?php echo $description; ?>">
                <div class="invalid-feedback validationMessage" id="FrameworkPackage_addDescription_description-validationMessage"></div>
            </div>
        </div>

        <div class="mb-3">
            <div class="input-group">
                <button name="FrameworkPackage_addDescription_submit" id="FrameworkPackage_addDescription_submit" type="button" class="btn btn-secondary btn-block" style="width: 200px;" 
                    onclick="Keywords.saveDescription(event);" value=""><?php echo trans('save'); ?></button>
            </div>
        </div>

        <!-- <div class="row" id="FrameworkPackage_addDescription_submit-container">
            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
            </div>
            <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
                <div class="form-group">
                    <button name="FrameworkPackage_addDescription_submit" id="FrameworkPackage_addDescription_submit" type="button" class="btn btn-primary btn-block" style="width: 200px;" 
                        onclick="Keywords.saveDescription(event);" value="">Mentés</button>
                </div>
            </div>
        </div> -->

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div id="FrameworkPackage_addKeyword-message" style="padding-top:4px;"></div>
            </div>
        </div>

    </form>
</div>

<!-- <div class="widgetWrapper-info">
    <b>Kulcsszavak</b>: szavak, amik kifejezik azt, hogy miről szól ez az oldal. A keresőmotorok (pl. Google, BING) részben ezek alapján osztályozzák a webhelyet keresőszavakhoz találatként. 
    Ezért fontos a precíz és kifejező kulcsszó.
</div> -->

<div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <?php echo trans('keyword'); ?>dsadas
    </div>
    <div class="card-body">
        <div class="">
            <?php echo trans('keyword.description'); ?>
        </div>
    </div>
</div>

<?php 
// dump($pageKeywords);
?>

<form class="form" id="FrameworkPackage_addKeyword_form" name="FrameworkPackage_addKeyword_form" action="" method="POST">
    <div class="mb-3">
        <label for="FrameworkPackage_addKeyword_keyword" class="form-label"><?php echo trans('keyword'); ?></label>
        <div class="input-group has-validation">
            <input type="text" class="form-control inputField" name="FrameworkPackage_addKeyword_keyword" id="FrameworkPackage_addKeyword_keyword" maxlength="250" placeholder="" value="<?php echo $description; ?>">
            <div class="invalid-feedback validationMessage" id="FrameworkPackage_addKeyword_keyword-validationMessage"></div>
        </div>
    </div>

    <div class="mb-3">
        <div class="input-group">
            <button name="FrameworkPackage_addKeyword_submit" id="FrameworkPackage_addKeyword_submit" type="button" class="btn btn-secondary btn-block" style="width: 200px;" 
                onclick="Keywords.addKeyword(event);" value=""><?php echo trans('save'); ?></button>
        </div>
    </div>
</form>

<!-- <div class="widgetWrapper">
    <form class="form" id="FrameworkPackage_addKeyword_form" name="FrameworkPackage_addKeyword_form" action="" method="POST">
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
                    <input name="FrameworkPackage_addKeyword_keyword" id="FrameworkPackage_addKeyword_keyword" type="text" class="inputField form-control enterSubmits" value="" aria-describedby="" placeholder="">
                </div>
                    <div class="validationMessage error" id="FrameworkPackage_addKeyword_keyword-validationMessage" style="padding-top:4px;"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
            </div>
            <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
                <div class="form-group">
                    <button name="FrameworkPackage_addKeyword_submit" id="FrameworkPackage_addKeyword_submit" type="button" class="btn btn-secondary btn-block" style="width: 200px;" 
                        onclick="Keywords.addKeyword(event);" value="">Mentés</button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div id="FrameworkPackage_addKeyword-message" style="padding-top:4px;"></div>
            </div>
        </div>

    </form>
</div> -->

<?php  

$keywords = ['alma', 'körte', 'szilva', 'banán', 'sárgabarack'];

?>


<div id="existingKeywords-container">
<?php 
include('tab_keywords_existing.php'); 
?>
</div>
<script>
    var Keywords = {
        listKeywords: function() {
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/customPage/keywords/list',
                'data': {
                    'customPageId': $('#customPageId').html()
                },
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
        addKeyword: function(e) {
            e.preventDefault();
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/customPage/keywords/add',
                'data': {
                    'keyword': $('#FrameworkPackage_addKeyword_keyword').val(),
                    'customPageId': $('#customPageId').html()
                },
                'async': false,
                'success': function(response) {
                    ElastiTools.checkResponse(response);
                    Keywords.listKeywords();
                    Structure.throwToast('<?php echo trans('system.message'); ?>', '<?php echo trans('successfully.saved'); ?>');
                    LoadingHandler.stop();
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                    // LoadingHandler.stop();
                },
            });
        },
        deleteKeyword: function(event, id) {
            event.preventDefault();
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/customPage/keywords/delete',
                'data': {
                    'id': id
                },
                'async': false,
                'success': function(response) {
                    ElastiTools.checkResponse(response);
                    // console.log(response);
                    Keywords.listKeywords();
                    LoadingHandler.stop();
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                    // LoadingHandler.stop();
                },
            });
        },
        saveDescription: function(e) {
            e.preventDefault();
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/customPage/saveDescription',
                'data': {
                    'customPageId': $('#customPageId').html(),
                    'description': $('#FrameworkPackage_addDescription_description').val(),
                },
                'async': false,
                'success': function(response) {
                    ElastiTools.checkResponse(response);
                    Keywords.listKeywords();
                    $('#FrameworkPackage_addDescription_submit-container').hide();
                    Structure.throwToast('<?php echo trans('system.message'); ?>', '<?php echo trans('successfully.saved'); ?>');
                    LoadingHandler.stop();
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                    // LoadingHandler.stop();
                },
            });
        },
        hideOrShowDescriptionSubmitContainer: function() {
            // $('#FrameworkPackage_addDescription_submit-container').hide();
            var originalDescription = $('#FrameworkPackage_addDescription_originalDescription').val();
            var newDescription = $('#FrameworkPackage_addDescription_description').val();
            if (originalDescription != newDescription) {
                $('#FrameworkPackage_addDescription_submit-container').show();
            } else {
                $('#FrameworkPackage_addDescription_submit-container').hide();
            }
        }
    };

    $('document').ready(function() {
        $('#FrameworkPackage_addDescription_submit-container').hide();

        $('body').off('keyup change', '#FrameworkPackage_addDescription_description');
        $('body').on('keyup change', '#FrameworkPackage_addDescription_description', function() {
            console.log('type');
            Keywords.hideOrShowDescriptionSubmitContainer();
        });

        LoadingHandler.stop();
    });
</script>