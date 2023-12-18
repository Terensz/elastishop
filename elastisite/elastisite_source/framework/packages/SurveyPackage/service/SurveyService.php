<?php
namespace framework\packages\SurveyPackage\service;

use App;
use framework\component\parent\Service;
use framework\kernel\utility\BasicUtils;
use framework\packages\SurveyPackage\entity\Survey;
use framework\packages\SurveyPackage\entity\SurveyCompletion;
use framework\packages\SurveyPackage\entity\SurveyCompletionAnswer;
use framework\packages\SurveyPackage\repository\SurveyOptionRepository;
use framework\packages\SurveyPackage\repository\SurveyQuestionRepository;
use framework\packages\SurveyPackage\repository\SurveyRepository;

class SurveyService extends Service
{
    public static function getSurveyBySlug(string $slug)
    {
        App::getContainer()->wireService('SurveyPackage/repository/SurveyRepository');
        $surveyRepo = new SurveyRepository();
        $survey = $surveyRepo->findOneBy(['conditions' => [['key' => 'slug', 'value' => $slug]]]);

        return $survey ? self::arrangeQuestions($survey) : null;
    }

    public static function getSurvey(int $surveyId)
    {
        App::getContainer()->wireService('SurveyPackage/repository/SurveyRepository');
        $surveyRepo = new SurveyRepository();
        $survey = $surveyRepo->find($surveyId);

        return self::arrangeQuestions($survey);
    }

    public static function arrangeQuestions(Survey $survey) : Survey
    {
        $surveyQuestions = [];
        foreach ($survey->getSurveyQuestion() as $surveyQuestion) {
            $surveyQuestions[$surveyQuestion->getId()] = $surveyQuestion;
        }
        ksort($surveyQuestions);
        $survey->setAllSurveyQuestions($surveyQuestions);

        return $survey;
    }

    public function createUniqueSlug($uncheckedSlug, $id)
    {
        if ($this->isSlug($uncheckedSlug, $id)) {
            $uncheckedSlug = BasicUtils::increaseSequence($uncheckedSlug);
            return $this->createUniqueSlug($uncheckedSlug, $id);
        } else {
            return $uncheckedSlug;
        }
    }

    public function isSlug($uncheckedSlug, $id)
    {
        // App::getContainer()->wireService('SurveyPackage/repository/SurveyRepository');
        // $surveyRepository = new SurveyRepository();
        // $foundSlug = $surveyRepository->findOneBy(['conditions' => [['key' => 'slug', 'value' => $uncheckedSlug]]]);

        $dbm = $this->getDbManager();
        $stm = "SELECT s.id
        FROM survey s
        WHERE s.slug = :slug
        AND s.id <> :id
        ";
        $foundSlug = $dbm->findOne($stm, ['slug' => $uncheckedSlug, 'id' => $id]);

        return $foundSlug ? true : false;
    }

    public static function processSurveyCompletion(Survey $survey)
    {
        if (SurveyService::isSurveyFilled($survey->getId())) {
            return [
                'success' => true,
                'missingAnswers' => [],
                'postedAnswers' => []
            ];
        }
        // dump($survey);
        // App::getContainer()->wireService('SurveyPackage/repository/SurveyQuestionRepository');
        App::getContainer()->wireService('SurveyPackage/repository/SurveyOptionRepository');
        App::getContainer()->wireService('SurveyPackage/entity/SurveyCompletion');
        App::getContainer()->wireService('SurveyPackage/entity/SurveyCompletionAnswer');
        $surveyCompletion = new SurveyCompletion();
        $surveyCompletion->setSurvey($survey);
        // $surveyQuestionRepo = new SurveyQuestionRepository();
        $surveyOptionRepo = new SurveyOptionRepository();
        $missingAnswers = []; // questionIds
        $postedAnswers = [];
        $success = false;

        $requests = App::getContainer()->getRequest()->getAll();

        // dump($requests);return;

        foreach ($survey->getSurveyQuestion() as $surveyQuestion) {
            $questionFound = false;

            if (!$requests) {
                $missingAnswers[] = $surveyQuestion->getId();
            } else {
                foreach ($requests['SurveyCreator_answers'] as $questionId => $request) {
                    // $surveyQuestion = $surveyQuestionRepo->find($questionId);
                    if ($surveyQuestion->getId() == $questionId) {
                        $questionFound = true;
                        foreach ($request as $answerValue) {
                            $surveyCompletionAnswer = new SurveyCompletionAnswer();
                            $surveyCompletionAnswer->setSurveyCompletion($surveyCompletion);
                            $surveyCompletionAnswer->setSurveyQuestion($surveyQuestion);
                            if (empty($answerValue)) {
                                if ($surveyQuestion->getRequired()) {
                                    $missingAnswers[] = $surveyQuestion->getId();
                                }
                            } else {
                                $postedAnswers[$surveyQuestion->getId()][] = $answerValue;
                                if ($surveyQuestion->getInputType() == $surveyQuestion::INPUT_TYPE_TEXT) {
                                    $surveyCompletionAnswer->setAnswerValue($answerValue);
                                } elseif (in_array($surveyQuestion->getInputType(), [$surveyQuestion::INPUT_TYPE_SELECT, $surveyQuestion::INPUT_TYPE_RADIO, $surveyQuestion::INPUT_TYPE_CHECKER])) {
                                    $surveyOption = $surveyOptionRepo->find($answerValue);
                                    $value = $surveyOption->getDescription();
                                    $surveyCompletionAnswer->setAnswerValue($value);
                                }
                                $surveyCompletion->addSurveyCompletionAnswer($surveyCompletionAnswer);
                                // $surveyCompletionAnswer->getRepository()->store($surveyCompletionAnswer);
                            }
                        }
                    }
                }
    
                if ($questionFound == false && $surveyQuestion->getRequired()) {
                    $missingAnswers[] = $surveyQuestion->getId();
                }
            }
        }

        if (empty($missingAnswers)) {
            $surveyCompletion = $surveyCompletion->getRepository()->store($surveyCompletion);
            App::getContainer()->getSession()->set('surveyFilled_'.$survey->getId(), true);
            $success = true;
        }

        return [
            'success' => $success,
            'missingAnswers' => $missingAnswers,
            'postedAnswers' => $postedAnswers
        ];
    }

    public static function isSurveyFilled($surveyId)
    {
        // return false;
        // App::getContainer()->getSession()->set('surveyFilled_'.$surveyId, false);

        return App::getContainer()->getSession()->get('surveyFilled_'.$surveyId) ? true : false;
    }
}
