<?php
namespace framework\packages\SurveyPackage\repository;

use framework\component\parent\DbRepository;

class SurveyQuestionRepository extends DbRepository
{
    public function remove($id)
    {
        // $surveyQuestion = $this->find($id);
        // dump($surveyQuestion->getSurveyOption());exit;

        $dbm = $this->getDbManager();
        $stm = "DELETE
        FROM survey_option
        WHERE survey_question_id = :question_id
        ";
        $dbm->execute($stm, ['question_id' => $id]);
        
        return parent::remove($id);
    }
}
