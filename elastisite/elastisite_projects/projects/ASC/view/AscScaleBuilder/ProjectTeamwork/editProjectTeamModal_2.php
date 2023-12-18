<?php

use framework\packages\FormPackage\entity\Form;
// use projects\ASC\entity\AscScale;
// dump($form->getValueCollector());
// dump($form->getValueCollector()->getDisplayed('childrenIncluded'));
dump($form->getMessage('ASC_editProjectTeam_name'));
dump($form->getMessage('ASC_editProjectTeam_childrenIncluded'));
?>
<form name="ASC_editProjectTeam_form" id="ASC_editProjectTeam_form" method="POST" action="" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="ASC_editProjectTeam_name" class="form-label"><?php echo trans('name'); ?></label>
        <div class="input-group has-validation">
            <input type="text" class="form-control inputField" name="ASC_editProjectTeam_name" id="ASC_editProjectTeam_name" maxlength="250" placeholder="" value="<?php echo $form->getValueCollector()->getDisplayed('name'); ?>">
            <div class="invalid-feedback validationMessage" id="ASC_editProjectTeam_name-validationMessage"><?php echo $form->getMessage('ASC_editProjectTeam_name'); ?></div>
        </div>
    </div>
    <div class="mb-3">
        <label for="ASC_editProjectTeam_childrenIncluded" class="form-label"><?php echo trans('children.included'); ?></label>
        <div class="input-group has-validation">
            <!-- <input type="text" class="form-control" name="ASC_editProjectTeam_childrenIncluded" id="ASC_editProjectTeam_childrenIncluded" 
            maxlength="250" placeholder="" value="0"> -->
            <select class="form-select inputField" name="ASC_editProjectTeam_childrenIncluded" id="ASC_editProjectTeam_childrenIncluded" aria-describedby="ASC_editProjectTeam_childrenIncluded-validationMessage" required="">
                <?php 
                $selectedStr = $form->getValueCollector()->getDisplayed('childrenIncluded') === null ? ' selected' : '';
                ?>
                <option class="option-*null*" value="*null*" selected=""><?php echo trans('please.choose'); ?></option>
                <?php
                $childrenIncludedOptions = [
                    '1' => 'yes',
                    '0' => 'no',
                ];
                ?>
                <?php foreach ($childrenIncludedOptions as $childrenIncludedOptionKey => $childrenIncludedOptionValue): ?>
                <?php 
                $selectedStr = $form->getValueCollector()->getDisplayed('childrenIncluded') === $childrenIncludedOptionKey ? ' selected' : '';
                ?>
                <option class="option-1" value="<?php echo $childrenIncludedOptionKey; ?>"<?php  echo $selectedStr; ?>><?php echo trans($childrenIncludedOptionValue); ?></option>
                <?php endforeach; ?>
            </select>
            <div class="invalid-feedback validationMessage" id="ASC_editProjectTeam_childrenIncluded-validationMessage"><?php echo $form->getMessage('ASC_editProjectTeam_childrenIncluded'); ?></div>
        </div>
    </div>
    <div class="mb-3">
        <label for="ASC_editProjectTeam_AscUnit_ascUnitId" class="form-label"><?php echo trans('ascUnitId'); ?></label>
        <div class="input-group has-validation">
            <input type="text" class="form-control inputField" name="ASC_editProjectTeam_AscUnit_ascUnitId" id="ASC_editProjectTeam_AscUnit_ascUnitId" maxlength="250" placeholder="" value="<?php echo $form->getValueCollector()->getDisplayed('ascUnitId'); ?>">
            <div class="invalid-feedback validationMessage" id="ASC_editProjectTeam_AscUnit_ascUnitId-validationMessage"></div>
        </div>
    </div>
</form>

<div class="mb-3">
    <!-- <button class="btn btn-primary" name="" id="" type="button" onclick="ProjectTeamwork.<?php echo $modalActionType; ?>ProjectTeam(event, true);" value="">Mentés</button> -->
    <?php if ($modalActionType == 'new'): ?>
    <button class="btn btn-primary" name="" id="" type="button" onclick="ProjectTeamwork.<?php echo $modalActionType; ?>ProjectTeam(event, true);" value="">Mentés</button>
    <?php endif; ?>
    <?php if ($modalActionType == 'edit'): ?>
    <button class="btn btn-primary" name="" id="" type="button" onclick="ProjectTeamwork.<?php echo $modalActionType; ?>ProjectTeam(event, true, '<?php echo $form->getEntity()->getId(); ?>');" value="">Mentés</button>
    <?php endif; ?>
</div>