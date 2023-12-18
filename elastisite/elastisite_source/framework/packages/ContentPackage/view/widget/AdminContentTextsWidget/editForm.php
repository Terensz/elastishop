<!-- <style>
    .nicEdit-main {
        border: 0px;
        padding: 0px;
        margin: 0px;
        outline:none;
        user-select: all;
        line-height: normal;
        /* column-count: 2; */
    }
</style>
<script src="/public_folder/asset/TextareaEditor/TextareaEditor.js"></script>
<script src="/public_folder/plugin/nicEdit/nicEdit.js"></script> -->
<!-- <script src="/public_folder/plugin/EditorJs/editor.js"></script>
<script src="/public_folder/plugin/EditorJs/header.js"></script>
<script src="/public_folder/plugin/EditorJs/list.js"></script>
<script src="/public_folder/plugin/EditorJs/paragraph.js"></script> -->
<?php 
/**
 * @todo EditorJS!!!!
 * https://github.com/codex-team/editor.js/issues/1407
 * https://github.com/codex-team/editor.js/search?q=editorjs
 * https://cdn.jsdelivr.net/npm/@editorjs/xxxxxxxx@latest
 * 
*/
?>
<form name="ContentPackage_contentTextEdit_form" id="ContentPackage_contentTextEdit_form" method="POST" action="" enctype="multipart/form-data">
    <input name="ContentPackage_contentTextEdit_uniqueId" id="ContentPackage_contentTextEdit_uniqueId" type="hidden" value="<?php echo $uniqueId;?>">
<?php

// $contentText = $form->getEntity();
// $formView = $viewTools->create('form')->setForm($form);
// $formView->setResponseBodySelector('#editorModalBody');
// $formView->setResponseLabelSelector('#editorModalLabel');
// $formView->add('textarea')->setPropertyReference('phrase')->setLabel(trans('phrase'));
// $formView->setFormMethodPath('admin/contentText/edit');
// $formView->displayForm(false, true)->displayScripts();

?>

    <!-- <div class="row">
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
            <div class="form-group formLabel{{ labelAdditionalClass }}">
                <label for="ContentPackage_contentTextEdit_phrase">
                    <b></b>
                </label>
            </div>
        </div>
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <div class="form-group">
                <div class="input-group">
                    <textarea name="ContentPackage_contentTextEdit_phrase" id="ContentPackage_contentTextEdit_phrase" class="textarea-input inputField form-control"
                        aria-describedby="" placeholder=""><?php echo $form->getValueCollector()->getDisplayed('phrase'); ?></textarea>
                </div>
                <div class="validationMessage error" id="ContentPackage_contentTextEdit_phrase-validationMessage" style="padding-top:4px;"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
        </div>
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <div class="form-group">
                <button name="ContentPackage_contentTextEdit_submit" id="ContentPackage_contentTextEdit_submit" type="button" class="btn btn-secondary btn-block" style="width: 200px;" onclick="ContentTexts.save();" value="">Mentés</button>
            </div>
        </div>
    </div> -->

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="form-group">
                <textarea name="ContentPackage_contentTextEdit_phrase" id="ContentPackage_contentTextEdit_phrase" class="textarea-input inputField form-control"
                        aria-describedby="" placeholder=""><?php echo $form->getValueCollector()->getDisplayed('phrase'); ?></textarea>
                <div class="validationMessage error" id="ContentPackage_contentTextEdit_phrase-validationMessage" style="padding-top:4px;"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="form-group">
                <button name="ContentPackage_contentTextEdit_submit" id="ContentPackage_contentTextEdit_submit" type="button" class="btn btn-secondary btn-block" style="width: 200px;" onclick="ContentTexts.save();" value="">Mentés</button>
            </div>
        </div>
    </div>


</form>

<script>
// if (typeof(editor) == 'undefined') {
//     const editor = new EditorJS({
//         /**
//          * Id of Element that should contain Editor instance
//          */
//         holder: 'ContentPackage_contentTextEdit_phrase',
//         tools: {
//             header: {
//                 class: Header, 
//                 inlineToolbar: ['link'] 
//             }, 
//             list: { 
//                 class: List, 
//                 inlineToolbar: true 
//             } 
//         }
//     });
// }

$(document).ready(function() {
    $('textarea').keypress(function(e) {
        if (e.which == 13) {
            e.stopPropagation();
        }
    });

    // CKEDITOR.editorConfig = function( config ) {
    //     config.toolbarGroups = [
    //         { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
    //         { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
    //         { name: 'links', groups: [ 'links' ] },
    //         { name: 'insert', groups: [ 'insert' ] },
    //         { name: 'forms', groups: [ 'forms' ] },
    //         { name: 'tools', groups: [ 'tools' ] },
    //         { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
    //         { name: 'others', groups: [ 'others' ] },
    //         '/',
    //         { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
    //         { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
    //         { name: 'styles', groups: [ 'styles' ] },
    //         { name: 'colors', groups: [ 'colors' ] },
    //         { name: 'about', groups: [ 'about' ] }
    //     ];

    //     config.removeButtons = 'Subscript,Superscript,Cut,Copy,Paste,PasteText,PasteFromWord,Scayt,Maximize,Styles,About';
    // };


    // var toolbarGroups = [
    //     { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
    //     { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
    //     { name: 'links', groups: [ 'links' ] },
    //     { name: 'insert', groups: [ 'insert' ] },
    //     { name: 'forms', groups: [ 'forms' ] },
    //     { name: 'tools', groups: [ 'tools' ] },
    //     { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
    //     { name: 'others', groups: [ 'others' ] },
    //     '/',
    //     { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
    //     { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
    //     { name: 'styles', groups: [ 'styles' ] },
    //     { name: 'colors', groups: [ 'colors' ] },
    //     { name: 'about', groups: [ 'about' ] }
    // ];

    var initEditor = function() {
        var ckeditor = CKEDITOR.replace('ContentPackage_contentTextEdit_phrase', {
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

    // CKEDITOR.instances.editor1.getData();

    // var editor = CKEDITOR.replace('ContentPackage_contentTextEdit_phrase', {

    // });

    // new nicEditor({buttonList :['xhtml', 'fontSize','bold','italic','underline','ol','ul']}).panelInstance('ContentPackage_contentTextEdit_phrase', {hasPanel : true, maxHeight: 200});
    // $('.nicEdit-main').on('blur', function() {
    //     let content = nicEditors.findEditor("ContentPackage_contentTextEdit_phrase").getContent();
    //     $('#ContentPackage_contentTextEdit_phrase').html(content);
    // });

});
</script>

<style>
/* .textarea-input {
    height: 400px !important;
} */
</style>