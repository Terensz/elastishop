<?php
namespace framework\packages\BasicPackage\form;

use framework\component\parent\CustomFormValidator;

class BasicCustomValidator extends CustomFormValidator
{
    public function dateFormat($value, bool $ruleValue, $form)
    {
        $dateFormat = '/^\d{4}-\d{2}-\d{2}( \d{2}:\d{2}:\d{2})?$/';

        if (empty($value) || preg_match($dateFormat, $value)) {
            $valueParts = explode('-', $value);
            if ((int)$valueParts[0] < 2000) {
                return [
                    'result' => false,
                    'message' => trans('invalid.year.should.be.2000.plus')
                ];
            }
            return [
                'result' => true,
                'message' => null
            ];
        } else {
            return [
                'result' => false,
                'message' => trans('invalid.date.format')
            ];
        }
    }
}