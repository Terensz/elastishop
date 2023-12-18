<div class="widgetWrapper">
<?php

$formNameBase = 'devForm';
$propertyAlias = 'alma';

// dump($container->getConfig()->getProjectData('allowedHttpDomains'));
// dump($container->getUrl()->getHttpDomain());
// dump($form);

$formView = $viewTools->create('form')->setForm($form);
$formView->setResponseBodySelector('#widgetContainer-mainContent');
$formView->add('text')->setPropertyReference('password')->setLabel(trans('new.password'));
$formView->add('text')->setPropertyReference('retypedPassword')->setLabel(trans('new.retyped.password'));
$formView->add('submit')->setPropertyReference('submit')->setValue(trans('save'));
$formView->setFormMethodPath('/dev/form/widget');
$formView->displayForm()->displayScripts();

?>
</div>

<script>
    var RedeemPasswordRecoveryToken = {
        call: function() {
            var ajaxData = {};
            var form = $('#UserPackage_changePassword_form');
            ajaxData = form.serialize();
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/widget/RedeemPasswordRecoveryTokenWidget',
                'data': ajaxData,
                'async': true,
                'success': function(response) {
                    ElastiTools.checkResponse(response);
                    $('#widgetContainer-mainContent').html(response.view);
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                },
            });
        }
    };
</script>