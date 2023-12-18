<form name="ElastiSite_newsSubscription_form" id="ElastiSite_newsSubscription_form" action="" method="POST">
<?php 
$formView = $viewTools->create('form')->setForm($newsSubscriptionForm);
$formView->setLabelRate(12);
$formView->setInputRate(12);
$formView->setLabelAdditionalClass(' textAlignLeft');
$formView->add('text')->setPropertyReference('name')->setLabel(trans('name'));
$formView->add('text')->setPropertyReference('email')->setLabel(trans('email'));
$codeInfoText = '<div class="widgetWrapper-info">'.trans('mobile.for.later.inquiry').'</div>';
$formView->add('custom')->setPropertyReference(null)->setLabel(trans('image'))->addCustomData('view', $codeInfoText);
$formView->add('text')->setPropertyReference('mobile')->setLabel(trans('mobile'));

// $formView->setFormMethodPath('admin/background/binding/edit');
$formView->displayForm(false, false);

// dump($container->getRequest()->getAll());
// dump($form);
?>
</form>

<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <div id="newsSubscriptionSubmitContainer" style="display: inline;">
            <div class="form-group">
                <button id="ElastiSite_newsSubscription_submit" style="width: 200px;" type="button" class="btn btn-secondary btn-block">Mentés</button>
            </div>
        </div>
    </div>
</div>

<?php 
// dump($container->getRequest()->getAll());
// dump($newsSubscriptionForm);
?>

<script>
    var NewsSubscription = {
        loadForm: function(submitted) {
            // console.log('newsSubscription', submitted);
            // e.preventDefault();
            var ajaxData = {};
            if (submitted === true) {
                var form = $('#ElastiSite_newsSubscription_form');
                ajaxData = form.serialize();
            }
            console.log(ajaxData);
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/newsSubscriptionForm',
                'data': ajaxData,
                'async': false,
                'success': function(response) {
                    ElastiTools.checkResponse(response);
                    // console.log(response);
                    $('#ElastiSite_newsSubscription_form-container').html(response.view);
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                    // LoadingHandler.stop();
                },
            });
        },
    };

    $('document').ready(function() {
        $('body').off('click', '#ElastiSite_newsSubscription_submit');
        $('body').on('click', '#ElastiSite_newsSubscription_submit', function() {
            NewsSubscription.loadForm(true);
        });
    });
</script>