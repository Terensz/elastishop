<?php if ($disabled): ?>
<div class="widgetWrapper-info">
    <?php echo trans('survey.not.editable'); ?>
</div>
<?php endif; ?>

<?php foreach ($surveyQuestions as $surveyQuestion): ?>
<?php 
$questionId = $surveyQuestion->getId();
$options = $surveyQuestion->getSurveyOption();
include('questionEditor.php');
?>
<?php endforeach; ?>