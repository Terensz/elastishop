<?php
namespace framework\packages\SiteBuilderPackage\form;

use framework\component\parent\CustomFormValidator;

/**
 * @var bool ruleValue: Desired return
*/
class EditContentEditorUnitCustomValidator extends CustomFormValidator
{
    public function requiredIfDescriptionIsFilled($value, bool $ruleValue, $form)
    {
        $description = $form->getValueCollector()->getDisplayed('description');
        if ($description && $description != '') {
            if (!$value || $value == '') {
                return [
                    'result' => false,
                    'message' => trans('this.field.is.required.if.description.is.filled')
                ];
            }
        }
        return [
            'result' => true,
            'message' => null
        ];
    }
}