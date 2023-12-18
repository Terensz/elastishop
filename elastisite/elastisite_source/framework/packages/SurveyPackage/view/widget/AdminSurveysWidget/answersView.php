<?php

use framework\component\helper\StringHelper;

?>
<?php foreach ($survey->getSurveyQuestion() as $surveyQuestion): ?>
<?php
$uniqueAnswerValues = [];
$answerValueCollection = [];
$totalAnswers = 0;
?>


    <?php foreach ($surveyCompletions as $surveyCompletion): ?>
        <?php foreach ($surveyCompletion->getSurveyCompletionAnswer() as $surveyCompletionAnswer): ?>
            <?php if ($surveyCompletionAnswer->getSurveyQuestion()->getId() == $surveyQuestion->getId()): ?>
<?php 
$totalAnswers++;
$answerValue = $surveyCompletionAnswer->getAnswerValue();
// dump($answerValue);
if (!in_array($answerValue, $uniqueAnswerValues)) {
    $uniqueAnswerValues[] = $answerValue;
    $answerValueCollection[$answerValue] = 1;
} else {
    $answerValueCollection[$answerValue]++;
}
?>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endforeach; ?>

<?php 
// dump($uniqueAnswerValues);
// dump($answerValueCollection);
?>
    <div class="form-group formLabel textAlignLeft">
        <label>
            <b><i><h2><?php echo $surveyQuestion->getDescription(); ?></h2></i></b>
        </label>
    </div>

    <div class="doubleParagraph" style="padding-bottom: 6px;">
        Összes válasz: <b><?php echo $totalAnswers; ?></b>
    </div>

<?php 
// $answerVariantIndex = 0;
$chartSeriesArray = [];
$chartLabelsArray = [];
$answerVariantIndex = 0;
?>
    <?php foreach ($answerValueCollection as $answer => $answerCount): ?>
    <?php 
    $answerVariantIndex++;
    $chartSeriesArray[] = $answerCount;
    $chartLabelsArray[] = StringHelper::cutLongString($answer, 120);
    ?>
        <div class="doubleParagraph">
            <b><?php echo $answerCount . ' ' . trans('answer.lc'); ?></b> (<?php echo round($answerCount / $totalAnswers * 100, 1).'%'; ?>): <?php echo $answer; ?>
        </div>
    <?php endforeach; ?>

    <?php if ($totalAnswers > 0 && $answerVariantIndex <= $survey::MAX_ANSWER_VARIANTS_DISPLAYED_ON_CHART): ?>
    <?php 
    $chartSeries = implode(', ', $chartSeriesArray);
    $chartLabels = "'".implode("', '", $chartLabelsArray)."'";
    ?>
    <div class="answerChart-container" style="background-color: #f1f5f6; margin-top: 20px; box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;">
        <div id="answerChart_<?php echo $surveyQuestion->getId(); ?>">

        </div>
    </div>

    <script>
        var AnswerChartOptions_<?php echo $surveyQuestion->getId(); ?> = {
            series: [<?php echo $chartSeries; ?>],
            chart: {
                width: '100%',
                type: 'pie',
            },
            legend: {
                position: 'top',
                floating: false,
                width: '100%',
            },
            labels: [<?php echo $chartLabels; ?>],
            dataLabels: {
                enabled: false,
            }
            // responsive: [{
            //   breakpoint: 480,
            //   options: {
            //     chart: {
            //       width: 200
            //     },
            //     legend: {
                
            //       position: 'right',
            //       floating: true,
            //       width: 300,
            //     }
            //   }
            // }]
        };

        var AnswerChart_<?php echo $surveyQuestion->getId(); ?>  = new ApexCharts(document.querySelector("#answerChart_<?php echo $surveyQuestion->getId(); ?>"), AnswerChartOptions_<?php echo $surveyQuestion->getId(); ?> );
        AnswerChart_<?php echo $surveyQuestion->getId(); ?>.render();        
    </script>
    <?php elseif ($answerVariantIndex > $survey::MAX_ANSWER_VARIANTS_DISPLAYED_ON_CHART): ?>
    <div style="height: 20px;"></div>
    <div class="widgetWrapper-info">
        <?php echo trans('cant.display.chart.max.answer.variants.reached').' ('.$survey::MAX_ANSWER_VARIANTS_DISPLAYED_ON_CHART.')'; ?>
    </div>
    <?php endif; ?>

<?php endforeach; ?>

<style>
    .simpleParagraph {
        padding-left: 20px;
    }
    .doubleParagraph {
        padding-left: 40px;
    }
    .rowSeparator-answers {
        border-top: 1px solid #e4e4e4;
        margin-top: 6px;
        padding-bottom: 10px;
        margin-left: 40px;
    }
</style>