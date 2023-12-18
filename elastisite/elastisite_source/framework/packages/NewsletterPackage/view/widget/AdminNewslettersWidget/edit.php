<?php 
// $isEditable = false;
// dump($posts);
// dump($form);
// dump($form->getValueCollector()->getDisplayed('status'));
$formView = $viewTools->create('form')->setForm($form);
$formView->setFormMethodPath('admin/newsletter/edit');
// $formView->add('hidden')->setPropertyReference('customPageId')->setLabel(trans('custom.page'));
$formView->add($isEditable ? 'text' : 'disabledText')->setPropertyReference('subject')->setLabel(trans('subject'));
$formView->add($isEditable ? 'textarea' : 'disabledTextarea')->setPropertyReference('body')->setLabel(trans('body'));

$statusSelect = $formView->add('select')
->setPropertyReference('status')
->setLabel(trans('status'));
foreach ($statuses as $statusIndex => $statusName) {
    $statusSelect->addOption(
        $statusIndex, 
        $statusName, 
        false
    );
}

// $formView->add('submit')->setPropertyReference('submit')->setValue(trans('save'));
// $formView->setFormMethodPath('EgpScalesWidget/edit');
$formView->displayForm(true)->displayScripts();
?>

<div class="row">
    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
    </div>
    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
        <div class="form-group">
            <button name="" id="" type="button" class="btn btn-secondary btn-block" style="width: 200px;" onclick="EditNewsletter.save('<?php echo $id; ?>');" value=""><?php echo trans('save'); ?></button>
        </div>
    </div>
</div>

<script>
var EditNewsletter = {
    save: function(id) {
        let editorData = CKEDITOR.instances.NewsletterPackage_EditNewsletter_body.getData();
        console.log(CKEDITOR.instances.NewsletterPackage_EditNewsletter_body.getData());
        $('#NewsletterPackage_EditNewsletter_body').val(editorData);
        NewsletterPackageEditNewsletterForm.call(id);
        // if (ajaxResponse.data.formIsValid == true) {
        //     AdminNewslettersGrid.list(true);
        //     $('#editorModal').modal('hide');
        // }
    }
};

$(document).ready(function() {
    $('textarea').keypress(function(e) {
        if (e.which == 13) {
            e.stopPropagation();
        }
    });

    var initEditor = function() {
        var ckeditor = CKEDITOR.replace('NewsletterPackage_EditNewsletter_body', {
            toolbar : 'Basic',
            uiColor : '#c0c0c0',
            toolbarGroups: [
                { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
                { name: 'editing', groups: [ 'editing' ] },
                { name: 'links', groups: [ 'links' ] },
                { name: 'insert', groups: [ 'insert' ] },
                { name: 'colors', groups: [ 'colors' ] },
                { name: 'styles', groups: [ 'styles' ] },
                // { name: 'forms', groups: [ 'forms' ] },
                // { name: 'tools', groups: [ 'tools' ] },
                // { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
                { name: 'others', groups: [ 'others' ] },
                '/',
                { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'paragraph' ] },
                // { name: 'about', groups: [ 'about' ] }
            ],
            language: '<?php echo $container->getSession()->getLocale(); ?>',
            removePlugins: 'image',
            // cloudServices_tokenUrl: '/upload/token',
            // cloudServices_uploadUrl: '/upload/alma',
            filebrowserBrowseUrl: '/browser/browse.php',
            filebrowserImageBrowseUrl: '/browser/browse.php?type=Images',
            filebrowserUploadUrl: '/upload/file/?ckeditor',
            filebrowserImageUploadUrl: '/upload/image/?ckeditor',
            removeButtons:'Subscript,Superscript,Save,NewPage,Cut,Copy,Paste,PasteText,Font,PasteFromWord,Smiley,PageBreak,Iframe,Scayt,Maximize,Styles,About'
        });
        // CKEDITOR.addCss(".cke_editable{ cursor:text; font: 18px DefaultFont; color: #2a2a2a; }");
        CKEDITOR.addCss(".cke_editable{ cursor:text; font: 18px Arial; color: #2a2a2a; }");
        return ckeditor;
    }
    initEditor();
});
</script>