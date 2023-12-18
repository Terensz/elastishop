<?php
namespace framework\packages\SchedulePackage\form;

use framework\component\parent\CustomFormValidator;

class EventCustomValidator extends CustomFormValidator
{
    public function startDateIsDateTime($value, $ruleValue, $entity)
    {
        $startDateIsDateTime = \DateTime::createFromFormat('Y-m-d H:i', $entity->getStartDate());
        if ($startDateIsDateTime === false) {
            return [
                'result' => false,
                'message' => trans('start.date.also.must.to.be.datetime')
            ];
        }
        else {
            return [
                'result' => true,
                'message' => null
            ];
        }
    }

    public function laterThanStartDate($value, $ruleValue, $entity)
    {
        if ($value > $entity->getStartDate()) {
            return [
                'result' => true,
                'message' => null
            ];
        }
        else {
            return [
                'result' => false,
                'message' => trans('end.date.must.be.later.than.start.date')
            ];
        }
    }
}
