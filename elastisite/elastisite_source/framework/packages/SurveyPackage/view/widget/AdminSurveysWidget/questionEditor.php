
    <div class="SurveyCreator_questionContainer" id="SurveyCreator_questionContainer_<?php echo $questionId; ?>">
        <form id="SurveyCreator_questionForm_<?php echo $questionId; ?>" name="SurveyCreator_questionForm_<?php echo $questionId; ?>">
            <div class="tagFrame-col" id="">
                <div class="tag-light">
                    <!-- <table style="width: 100%;">
                        <tbody>
                            <tr>
                                <td id=""><?php echo $questionId; ?></td>
                            </tr>
                            <tr>
                                <td id="">dsads</td>
                            </tr>
                        </tbody>
                    </table> -->

                    <div class="mb-3">
                        <label for="SurveyCreator_questionDescription_<?php echo $questionId; ?>" class="form-label">Kérdés</label>
                        <div class="input-group has-validation">
                            <input <?php echo $disabled ? 'disabled ' : ''; ?>name="SurveyCreator_questionDescription_<?php echo $questionId; ?>" id="SurveyCreator_questionDescription_<?php echo $questionId; ?>" 
                                type="text" class="inputField form-control" aria-describedby="" placeholder="" value="<?php echo $surveyQuestion->getDescription(); ?>">
                            <div class="invalid-feedback validationMessage" id="SurveyCreator_questionDescription_<?php echo $questionId; ?>-validationMessage"></div>
                        </div>
                    </div>

                    <!-- <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group formLabel textAlignLeft">
                                <label for="SurveyCreator_questionDescription_<?php echo $questionId; ?>">
                                    <b>Kérdés</b>
                                </label>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <input <?php echo $disabled ? 'disabled ' : ''; ?>name="SurveyCreator_questionDescription_<?php echo $questionId; ?>" id="SurveyCreator_questionDescription_<?php echo $questionId; ?>" 
                                        type="text" class="inputField form-control" aria-describedby="" placeholder="" value="<?php echo $surveyQuestion->getDescription(); ?>">
                                </div>
                                <div class="validationMessage error" id="SurveyCreator_questionDescription_<?php echo $questionId; ?>-validationMessage" style="padding-top:4px;"></div>
                            </div>
                        </div>
                    </div> -->

                    <div class="mb-3">
                        <label for="SurveyCreator_questionRequired_<?php echo $questionId; ?>" class="form-label">Kötelező megválaszolni</label>
                        <div class="input-group has-validation">
                            <!-- <input type="text" class="form-control" name="SurveyPackage_editSurvey_status" id="SurveyPackage_editSurvey_status" 
                            maxlength="250" placeholder="" value="1"> -->
                            <select <?php echo $disabled ? 'disabled ' : ''; ?>data-questionid="<?php echo $questionId; ?>" 
                                name="SurveyCreator_questionRequired_<?php echo $questionId; ?>" id="SurveyCreator_questionRequired_<?php echo $questionId; ?>" 
                                class="SurveyCreator_questionRequired inputField form-control">
                                <option value="true"<?php echo $surveyQuestion->getRequired() ? ' selected' : ''; ?>>Igen</option>
                                <option value="false"<?php echo !$surveyQuestion->getRequired() ? ' selected' : ''; ?>>Nem</option>
                            </select>
                            <div class="invalid-feedback validationMessage" id="SurveyCreator_questionRequired_<?php echo $questionId; ?>-validationMessage"></div>
                        </div>
                    </div>

                    <!-- <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group formLabel textAlignLeft">
                                <label for="SurveyCreator_questionRequired_<?php echo $questionId; ?>">
                                    <b>Kötelező megválaszolni</b>
                                </label>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <select <?php echo $disabled ? 'disabled ' : ''; ?>data-questionid="<?php echo $questionId; ?>" name="SurveyCreator_questionRequired_<?php echo $questionId; ?>" id="SurveyCreator_questionRequired_<?php echo $questionId; ?>" class="SurveyCreator_questionRequired inputField form-control">
                                        <option value="true"<?php echo $surveyQuestion->getRequired() ? ' selected' : ''; ?>>Igen</option>
                                        <option value="false"<?php echo !$surveyQuestion->getRequired() ? ' selected' : ''; ?>>Nem</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div> -->

                    <?php 
                    $inputType = $surveyQuestion->getInputType();
                    ?>

                    <div class="mb-3">
                        <label for="SurveyCreator_questionInputType_<?php echo $questionId; ?>" class="form-label">Válasz típusa</label>
                        <div class="input-group has-validation">
                            <select <?php echo $disabled ? 'disabled ' : ''; ?>data-questionid="<?php echo $questionId; ?>" 
                                name="SurveyCreator_questionInputType_<?php echo $questionId; ?>" id="SurveyCreator_questionInputType_<?php echo $questionId; ?>" 
                                class="SurveyCreator_questionInputType inputField form-control">
                                <option value="<?php echo $surveyQuestion::INPUT_TYPE_TEXT; ?>"<?php echo $inputType == $surveyQuestion::INPUT_TYPE_TEXT ? ' selected' : ''; ?>>Szöveges</option>
                                <option value="<?php echo $surveyQuestion::INPUT_TYPE_SELECT; ?>"<?php echo $inputType == $surveyQuestion::INPUT_TYPE_SELECT ? ' selected' : ''; ?>>Legördülő választó (a rövid válaszokhoz)</option>
                                <option value="<?php echo $surveyQuestion::INPUT_TYPE_RADIO; ?>"<?php echo $inputType == $surveyQuestion::INPUT_TYPE_RADIO ? ' selected' : ''; ?>>Radio (kerek) gombok (a hosszú válaszokhoz)</option>
                                <option value="<?php echo $surveyQuestion::INPUT_TYPE_CHECKER; ?>"<?php echo $inputType == $surveyQuestion::INPUT_TYPE_CHECKER ? ' selected' : ''; ?>>Checkbox (ha több válasz is megadható)</option>
                            </select>
                            <div class="invalid-feedback validationMessage" id="SurveyCreator_questionInputType_<?php echo $questionId; ?>-validationMessage"></div>
                        </div>
                    </div>

                    <!-- <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group formLabel textAlignLeft">
                                <label for="SurveyCreator_questionInputType_<?php echo $questionId; ?>">
                                    <b>Válasz típusa</b>
                                </label>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <select <?php echo $disabled ? 'disabled ' : ''; ?>data-questionid="<?php echo $questionId; ?>" name="SurveyCreator_questionInputType_<?php echo $questionId; ?>" id="SurveyCreator_questionInputType_<?php echo $questionId; ?>" class="SurveyCreator_questionInputType inputField form-control">
                                        <option value="<?php echo $surveyQuestion::INPUT_TYPE_TEXT; ?>"<?php echo $inputType == $surveyQuestion::INPUT_TYPE_TEXT ? ' selected' : ''; ?>>Szöveges</option>
                                        <option value="<?php echo $surveyQuestion::INPUT_TYPE_SELECT; ?>"<?php echo $inputType == $surveyQuestion::INPUT_TYPE_SELECT ? ' selected' : ''; ?>>Legördülő választó (a rövid válaszokhoz)</option>
                                        <option value="<?php echo $surveyQuestion::INPUT_TYPE_RADIO; ?>"<?php echo $inputType == $surveyQuestion::INPUT_TYPE_RADIO ? ' selected' : ''; ?>>Radio (kerek) gombok (a hosszú válaszokhoz)</option>
                                        <option value="<?php echo $surveyQuestion::INPUT_TYPE_CHECKER; ?>"<?php echo $inputType == $surveyQuestion::INPUT_TYPE_CHECKER ? ' selected' : ''; ?>>Checkbox (ha több válasz is megadható)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div> -->

                    <div id="SurveyCreator_optionContainer_<?php echo $questionId; ?>" class="SurveyCreator_optionContainer"<?php if (!$surveyQuestion->getInputType() || $surveyQuestion->getInputType() == 'text') { echo ' style="display: none;"'; } else { echo ''; } ?>>

                        <div class="mb-3">
                            <label for="SurveyCreator_questionInputType_<?php echo $questionId; ?>" class="form-label">Válaszlehetőségek</label>
                            <?php if (!$disabled): ?>
                            
                            <div class="mb-3">
                                <button type="button" class="btn btn-success btn-block" onclick="SurveyCreator.addOption('<?php echo $questionId; ?>');" value="">
                                    Hozzáadás
                                </button>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group formLabel textAlignLeft">
                                    <label for="SurveyCreator_questionInputType_<?php echo $questionId; ?>">
                                        <b>Válaszlehetőségek</b>
                                    </label>
                                </div>
                                <?php if (!$disabled): ?>
                                <div class="form-group">
                                    <div class="input-group">
                                        <button type="button" class="btn btn-success btn-block" style="width: 200px;" 
                                            onclick="SurveyCreator.addOption('<?php echo $questionId; ?>');" value="">Hozzáadás</button>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div> -->

                        <div>
<?php foreach ($options as $option): ?>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="input-group mb-3">
                                        <input <?php echo $disabled ? 'disabled ' : ''; ?>name="SurveyCreator_optionDescription_<?php echo $questionId; ?>[<?php echo $option->getId(); ?>]" id="SurveyCreator_optionDescription_<?php echo $questionId; ?>_<?php echo $option->getId(); ?>" 
                                            type="text" maxlength="250" class="inputField form-control" value="<?php echo $option->getDescription(); ?>">
                                        <?php if (!$disabled): ?>
                                        <div class="input-group-append">
                                            <div onclick="SurveyCreator.removeOption('<?php echo $questionId; ?>', '<?php echo $option->getId(); ?>')" class="input-group-text input-trash-button" style="cursor: pointer;">Törlés</div>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
<?php endforeach; ?>
                        </div>

                    </div>
<?php if (!$disabled): ?>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="mb-3">
                                <button name="SurveyCreator_saveQuestion_<?php echo $questionId; ?>" id="SurveyCreator_saveQuestion_<?php echo $questionId; ?>" type="button" class="SurveyCreator_saveQuestion btn btn-secondary btn-block" style="width: 200px;" 
                                    onclick="SurveyCreator.saveQuestion('<?php echo $questionId; ?>');" value="">Kérdés mentése</button>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="mb-3">
                                <button name="SurveyCreator_removeQuestion_<?php echo $questionId; ?>" id="SurveyCreator_removeQuestion_<?php echo $questionId; ?>" type="button" class="SurveyCreator_removeQuestion btn btn-danger btn-block" style="width: 200px;" 
                                    onclick="SurveyCreator.removeQuestion('<?php echo $questionId; ?>');" value="">Kérdés eldobása</button>
                            </div>
                        </div>
                    </div>
<?php endif; ?>
                </div>
            </div>
        </form>
    </div>
<?php if ($disabled): ?>
<script>
    $('document').ready(function() {
        $('#SurveyCreator_addQuestionButtonFrame').remove();
    });
</script>
<?php endif; ?>