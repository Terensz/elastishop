<!-- <div class="mb-3">
    <label for="UserPackage_editFBSUser_status" class="form-label">Státusz</label>
    <div class="input-group has-validation">
        <select class="form-select inputField" name="UserPackage_editFBSUser_status" id="UserPackage_editFBSUser_status" aria-describedby="UserPackage_editFBSUser_status-validationMessage" required="">
            <option class="option-1" value="1" selected="">Aktív</option>
            <option class="option-0" value="0">Inaktív</option>
        </select>
        <div class="invalid-feedback validationMessage" id="UserPackage_editFBSUser_status-validationMessage"></div>
    </div>
</div> -->

<?php
$valueArray = [];
$value = '';
// dump($missingAnswers);
?>
<?php foreach ($survey->getSurveyQuestion() as $surveyQuestion): ?>
<?php  
$andswerIsMissing = in_array($surveyQuestion->getId(), $missingAnswers);
$errorMessage = $andswerIsMissing ? ($surveyQuestion->getInputType() == $surveyQuestion::INPUT_TYPE_CHECKER 
                                    ? trans('required.to.check.at.least.one') 
                                    : trans('required.field')) 
                                : '';
$isInvalidClassString = $andswerIsMissing ? ' is-invalid' : '';
?>
            <div class="card-footer">
                <div class="mb-3">
                    <label for="SurveyCreator_answer_<?php echo $surveyQuestion->getId(); ?>">
                        <b><?php echo $surveyQuestion->getDescription(); ?></b>
                    </label>
                </div>

<?php if (!in_array($surveyQuestion->getInputType(), [$surveyQuestion::INPUT_TYPE_TEXT, $surveyQuestion::INPUT_TYPE_TEXTAREA])): ?>
    <?php 
        $valueArray = isset($postedAnswers[$surveyQuestion->getId()]) ? $postedAnswers[$surveyQuestion->getId()] : [];
    ?>
<?php endif; ?>
<?php if ($surveyQuestion->getInputType() == $surveyQuestion::INPUT_TYPE_TEXT): ?>
    <?php 
        $value = isset($postedAnswers[$surveyQuestion->getId()][0]) ? $postedAnswers[$surveyQuestion->getId()][0] : '';
        // dump($value);echo '<br>';

    ?>
                <div class="mb-3">
                    <div class="input-group has-validation">
                        <input name="SurveyCreator_answers[<?php echo $surveyQuestion->getId(); ?>][]" id="SurveyCreator_answer_<?php echo $surveyQuestion->getId(); ?>" type="text" maxlength="250" 
                            class="inputField form-control<?php echo $isInvalidClassString; ?>" value="<?php echo $value; ?>" aria-describedby="" placeholder="">
                    </div>
                </div>
<?php elseif ($surveyQuestion->getInputType() == $surveyQuestion::INPUT_TYPE_SELECT): ?>
                <div class="mb-3">
                    <div class="input-group has-validation">
                        <select name="SurveyCreator_answers[<?php echo $surveyQuestion->getId(); ?>][]" id="SurveyCreator_answer_<?php echo $surveyQuestion->getId(); ?>" class="inputField form-control">
                            <option value="">-- <?php echo trans('please.choose'); ?> --</option>
    <?php foreach ($surveyQuestion->getSurveyOption() as $surveyOption): ?>
                            <option value="<?php echo $surveyOption->getId(); ?>"<?php echo in_array($surveyOption->getId(), $valueArray) ? ' selected' : ''; ?>><?php echo $surveyOption->getDescription(); ?></option>
    <?php endforeach; ?>
                        </select>
<?php elseif ($surveyQuestion->getInputType() == $surveyQuestion::INPUT_TYPE_RADIO): ?>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="SurveyCreator_answers[<?php echo $surveyQuestion->getId(); ?>][]" 
                                        value=""<?php echo empty($valueArray) ? ' checked' : ''; ?>>
                                    <label class="form-check-label">
                                    <?php echo trans('please.choose'); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
    <?php foreach ($surveyQuestion->getSurveyOption() as $surveyOption): ?>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="SurveyCreator_answers[<?php echo $surveyQuestion->getId(); ?>][]"  
                                        value="<?php echo $surveyOption->getId(); ?>"<?php echo in_array($surveyOption->getId(), $valueArray) ? ' checked' : ''; ?>>
                                    <label class="form-check-label">
                                    <?php echo $surveyOption->getDescription(); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
    <?php endforeach; ?>
<?php elseif ($surveyQuestion->getInputType() == $surveyQuestion::INPUT_TYPE_CHECKER): ?>
    <?php foreach ($surveyQuestion->getSurveyOption() as $surveyOption): ?>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="SurveyCreator_answers[<?php echo $surveyQuestion->getId(); ?>][]" 
                                        value="<?php echo $surveyOption->getId(); ?>"<?php echo in_array($surveyOption->getId(), $valueArray) ? ' checked' : ''; ?>>
                                    <label class="form-check-label">
                                    <?php echo $surveyOption->getDescription(); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
    <?php endforeach; ?>
<?php endif; ?>
<?php 
// dump($missingAnswers);
?>
                        <div class="invalid-feedback validationMessage" id="SurveyCreator_answer_<?php echo $surveyQuestion->getId(); ?>-validationMessage"<?php 
                            echo (!empty($errorMessage) ? ' style="display:block;"' : ''); ?>><?php echo $errorMessage; ?></div>
                    </div>
                </div>
            </div>
<?php endforeach; ?>
            <!-- <div id="SurveyCreator_answerForm_submitContainer" class="row">
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <div id="newsSubscriptionSubmitContainer" style="display: inline;">
                        <div class="form-group">
                            <button id="SurveyCreator_answerForm_submit" onclick="SurveyCreatorAnswerForm.submitForm(event);" style="width: 200px;" type="button" class="btn btn-secondary btn-block"><?php echo trans('submit'); ?></button>
                        </div>
                    </div>
                </div>
            </div> -->
<?php  
// dump($surveyFormViewName);
// dump($postedAnswers);
?>