<?php
namespace framework\packages\WebshopPackage\form;

use framework\component\parent\CustomFormValidator;

/**
 * @var bool ruleValue: Desired return
*/
class EditShipmentCustomValidator extends CustomFormValidator
{
    public function checkOrgHungarianZip($value, bool $ruleValue, $form)
    {
        $country = $form->getValueCollector()->getDisplayed('orgCountryId');
        if ((int)$country == 348 && !empty($value)) {
            if (!is_numeric($value) || strlen($value) != 4) {
                return [
                    'result' => false,
                    'message' => trans('hungarian.zip.must.stand.of.4.numbers')
                ];
            }
        }
        return [
            'result' => true,
            'message' => null
        ];
    }

    public function checkHungarianOrgTaxid($value, bool $ruleValue, $form)
    {
        $country = $form->getValueCollector()->getDisplayed('orgCountryId');
        if ((int)$country == 348 && !empty($value)) {
            $valueParts = explode('-', $value);
            if (count($valueParts) < 3 || !is_numeric($valueParts[0]) || !is_numeric($valueParts[1]) || !is_numeric($valueParts[2]) || strlen($valueParts[0]) != 8 || strlen($valueParts[1]) != 1 || strlen($valueParts[2]) != 2) {
                return [
                    'result' => false,
                    'message' => trans('invalid.hungarian.taxid')
                ];
            }
        }
        return [
            'result' => true,
            'message' => null
        ];
    }

    public function requiredIfOrgExists($value, $ruleValue, $form)
    {
        $orgExists = $form->getValueCollector()->getDisplayed('orgName');
        if ($orgExists) {
            if (ctype_digit($value) || ($value && $value !== '') || $value === false) {
                return [
                    'result' => true,
                    'message' => null
                ];
            }
            else {
                return [
                    'result' => false,
                    'message' => trans('required.field')
                ];
            }
        }
        return [
            'result' => true,
            'message' => null
        ];
    }
}