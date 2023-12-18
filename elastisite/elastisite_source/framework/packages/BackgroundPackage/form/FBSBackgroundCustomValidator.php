<?php
namespace framework\packages\BackgroundPackage\form;

use framework\component\parent\CustomFormValidator;

class FBSBackgroundCustomValidator extends CustomFormValidator
{
    public function minThemeLength($value, $ruleValue, $form)
    {
        if (strlen($form->getValueCollector()->getPosted('theme')) < 4) {
            return [
                'result' => false,
                'message' => trans('min.character.length.4')
            ];
        }
        return [
            'result' => true,
            'message' => null
        ];
    }
}
