<form name="ElastiSite_sendUsMessage_form" id="ElastiSite_sendUsMessage_form" action="" method="POST">
<?php 
// dump($sendUsMessageForm);
$formView = $viewTools->create('form')->setForm($sendUsMessageForm);
$formView->setLabelRate(12);
$formView->setInputRate(12);
$formView->setLabelAdditionalClass(' textAlignLeft');
$text = '<div class="widgetWrapper-info">'.trans('send.us.mail.data.handling').'</div>';
$formView->add('custom')->setPropertyReference(null)->setLabel(trans('image'))->addCustomData('view', $text);
$formView->add($sendUsMessageMailSent ? 'inactiveField' : 'text')->setPropertyReference('senderName')->setLabel(trans('sender.name'));
$formView->add($sendUsMessageMailSent ? 'inactiveField' : 'text')->setPropertyReference('senderEmail')->setLabel(trans('sender.email'));
$formView->add($sendUsMessageMailSent ? 'inactiveField' : 'text')->setPropertyReference('subject')->setLabel(trans('subject'));
$formView->add($sendUsMessageMailSent ? 'inactiveField' : 'textarea')->setPropertyReference('body')->setLabel(trans('mail.body'));

// $formView->setFormMethodPath('admin/background/binding/edit');
$formView->displayForm(false, false);

// dump($container->getRequest()->getAll());
// dump($form);
?>
</form>

<?php if (!$sendUsMessageMailSent): ?>
<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <div id="sendUsMessageSubmitContainer" style="display: inline;">
            <div class="form-group">
                <button onclick="LoadingHandler.start(); SendUsMessage.loadForm(true);" id="ElastiSite_sendUsMessage_submit" style="width: 200px;" type="button" class="btn btn-secondary btn-block">Mentés</button>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="widgetWrapper-info">
            <?php echo trans('mail.sent.successfully'); ?>
        </div>
    </div>
</div>
<?php endif; ?>


<?php 
// dump($container->getRequest()->getAll());
// dump($sendUsMessageForm->getValueCollector()->getDisplayed('senderName'));

?>
