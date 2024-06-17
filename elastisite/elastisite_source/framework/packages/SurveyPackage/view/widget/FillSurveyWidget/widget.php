<!-- <div class="" style="padding: 20px; padding-bottom: 0px;">
    <div class="widgetWrapper-noPadding">
        <div class="widgetHeader widgetHeader-color">
            <div class="widgetHeader-titleText"><?php echo trans('survey') . ': ' . $survey->getTitle(); ?></div>
        </div>
        <div class="widgetWrapper-textContainer widgetWrapper-textContainer-bottomMargin">

            <div class="textBox-midDark textBox-roundedCorners textBox-black-boxShadow" style="margin-left: 0; margin-right: auto;">
                <?php echo $survey->getDescription(); ?>
            </div>

            <form name="SurveyCreator_answerForm" id="SurveyCreator_answerForm" action="" method="POST">
    <?php 
    // include($surveyFormViewName.'.php'); 
    ?>
            </form>
        </div>
    </div>
</div> -->

<div class="pc-container">
    <div class="pcoded-content card-container">

        <div class="card">
            <div class="bg-primary text-white card-header d-flex justify-content-between align-items-center">
                <div class="card-header-textContainer">
                    <h6 class="mb-0 text-white"><?php echo $survey->getTitle(); ?></h6>
                </div>
            </div>
            <div class="card-body">
                <span>
                    <?php echo $survey->getDescription(); ?>
                </span>
            </div>
            <form name="SurveyCreator_answerForm" id="SurveyCreator_answerForm" action="" method="POST">
                <?php  
                // dump($surveyFormViewName);
                // surveyFilled_75002
                App::getContainer()->getSession()->unset('surveyFilled_75002');
                // dump(App::getContainer()->getSession()->getAll());
                ?>
                <?php include($surveyFormViewName.'.php'); ?>
            </form>
        </div>

    </div>
</div>

<script>
    var SurveyCreatorAnswerForm = {
        submitForm: function(e) {
            LoadingHandler.start();
            e.preventDefault();
            // console.log('submitForm!!!');

            var ajaxData = {};
            var form = $('#SurveyCreator_answerForm');
            var formData = form.serialize();
            var additionalData = {
                // 'id': id
            };
            ajaxData = formData + '&' + $.param(additionalData);

            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $httpDomain; ?>/survey/answerForm',
                'data': ajaxData,
                'async': true,
                'success': function(response) {
                    // console.log(response.data);
                    LoadingHandler.stop();
                    ElastiTools.checkResponse(response);
                    $('#SurveyCreator_answerForm').html(response.view);
                },
                'error': function(response, error) {
                    ElastiTools.checkResponse(response.responseText);
                },
            });
        },
    };

    $('document').ready(function() {
        // $('body').off('click', '#SurveyCreator_answerForm_submit');
        // $('body').on('click', '#SurveyCreator_answerForm_submit', function() {
        //     console.log('click!!!');
        //     SurveyCreatorAnswerForm.submitForm();
        // });
    });
</script>