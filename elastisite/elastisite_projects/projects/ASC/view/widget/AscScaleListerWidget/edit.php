<?php

use framework\packages\FormPackage\entity\Form;
use projects\ASC\entity\AscScale;

$command = !isset($new) || !$new ? 'editScale' : 'newScale';

$defaultLanguage = App::getContainer()->getSession()->getLocale();

// App::getContainer()->wireService('FormPackage/entity/Form');
// $form = new Form();
$formView = $viewTools->create('form')->setForm($form);
$formView->setResponseBodySelector('#editorModalBody');
$formView->setResponseLabelSelector('#editorModalLabel');
$formView->setResponseViewObjectRoute('response.views.'.($command == 'newScale' ? 'newScaleView' : 'editScaleView'));
$formView->setCallbackJSFunction('AscScaleLister.editScaleCallback(response);');
$formView->setFormMethodPath('asc/scaleLister/'.$command);

$initialLanguageSelect = $formView->add('select')->setPropertyReference('initialLanguage')->setLabel(trans('initial.language'));
$initialLanguageSelect->addOption('*null*', 'please.choose');
foreach ($activeLanguages as $activeLanguage) {
    $initialLanguageSelect->addOption($activeLanguage['key'], $activeLanguage['translationReference'], true, null, ($activeLanguage['key'] == $defaultLanguage ? true : false));
}

$situationSelect = $formView->add('select')->setPropertyReference('situation')->setLabel(trans('situation'));
$situationSelect->addOption('*null*', 'please.choose');
foreach ($situations as $situation) {
    $situationSelect->addOption($situation['key'], $situation['translationReference']);
}

$formView->add('text')->setPropertyReference('title')->setLabel(trans('title'));

$formView->add('textarea')->setPropertyReference('description')->setLabel(trans('description'));

$statusSelect = $formView->add('select')->setPropertyReference('status')->setLabel(trans('status'));
// $statusSelect->addOption('*null*', 'please.choose');
$statuses = [
    ['key' => AscScale::STATUS_UNDER_CONSTRUCTION, 'translationReference' => 'under.construction'],
    ['key' => AscScale::STATUS_INACTIVE, 'translationReference' => 'inactive'],
];
foreach ($statuses as $status) {
    $statusSelect->addOption($status['key'], $status['translationReference']);
}

$formView->add('submit')->setPropertyReference('submit')->setValue(trans('save'));

$formView->displayForm()->displayScripts();
// dump($form);
?>



<!-- <form name="WebshopPackage_editProductCategory_form" id="WebshopPackage_editProductCategory_form" method="POST" action="" enctype="multipart/form-data"><div class="row">
    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
        <div class="form-group formLabel">  
            <label for="WebshopPackage_editProductCategory_ProductCategory_name">
                <b>Név</b>
            </label>
        </div>
    </div>
    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
        <div class="form-group">
            <div class="input-group">
                <input name="WebshopPackage_editProductCategory_ProductCategory_name" id="WebshopPackage_editProductCategory_ProductCategory_name" type="text" maxlength="250" class="inputField form-control" value="" aria-describedby="" placeholder="">

            </div>
            <div class="validationMessage error" id="WebshopPackage_editProductCategory_ProductCategory_name-validationMessage" style="padding-top:4px;"></div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
        <div class="form-group formLabel">
            <label for="WebshopPackage_editProductCategory_ProductCategory_productCategoryId">
                <b>Szülő kategória</b>
            </label>
        </div>
    </div>
    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
        <div class="form-group">
            <div class="input-group">
                <select name="WebshopPackage_editProductCategory_ProductCategory_productCategoryId" id="WebshopPackage_editProductCategory_ProductCategory_productCategoryId" class="inputField form-select dropdown form-control">
                        <option class="option-0" value="0" selected="">Főkategória</option>
  
                </select>
            </div>
            <div class="validationMessage error" id="WebshopPackage_editProductCategory_ProductCategory_productCategoryId-validationMessage" style="padding-top:4px;"></div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
        <div class="form-group formLabel">
            <label for="WebshopPackage_editProductCategory_ProductCategory_isIndependent">
                <b>Webáruház-független</b>
            </label>
        </div>
    </div>
    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
        <div class="form-group">
            <div class="input-group">
                <select name="WebshopPackage_editProductCategory_ProductCategory_isIndependent" id="WebshopPackage_editProductCategory_ProductCategory_isIndependent" class="inputField form-select dropdown form-control">
                        <option class="option-0" value="0">Hamis</option>
                        <option class="option-1" value="1">Igaz</option>
  
                </select>
            </div>
            <div class="validationMessage error" id="WebshopPackage_editProductCategory_ProductCategory_isIndependent-validationMessage" style="padding-top:4px;"></div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
        <div class="form-group formLabel">
            <label for="WebshopPackage_editProductCategory_ProductCategory_status">
                <b>Státusz</b>
            </label>
        </div>
    </div>
    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
        <div class="form-group">
            <div class="input-group">
                <select name="WebshopPackage_editProductCategory_ProductCategory_status" id="WebshopPackage_editProductCategory_ProductCategory_status" class="inputField form-select dropdown form-control">
                        <option class="option-1" value="1">Aktív</option>
                        <option class="option-0" value="0" selected="">Inaktív</option>
  
                </select>
            </div>
            <div class="validationMessage error" id="WebshopPackage_editProductCategory_ProductCategory_status-validationMessage" style="padding-top:4px;"></div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
    </div>
    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
        <div class="form-group">
            <button name="" id="" type="button" class="btn btn-secondary btn-block" style="width: 200px;" onclick="WebshopPackageEditProductCategoryForm.call();" value="">Mentés</button>
        </div>
    </div>
</div></form> -->