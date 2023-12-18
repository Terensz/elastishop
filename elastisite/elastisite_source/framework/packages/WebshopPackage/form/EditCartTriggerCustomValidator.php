<?php
namespace framework\packages\WebshopPackage\form;

use App;
use framework\component\parent\CustomFormValidator;
use framework\packages\WebshopPackage\entity\CartTrigger;

/**
 * @var bool ruleValue: Desired return
*/
class EditCartTriggerCustomValidator extends CustomFormValidator
{
    public function requiredIfNotAutomatic($value, $ruleValue, $form)
    {
        // dump($form);exit;
        App::getContainer()->wireService('WebshopPackage/entity/CartTrigger');
        if (empty($value) && $form->getValueCollector()->getDisplayed('effectCausingStuff') != CartTrigger::EFFECT_CAUSING_STUFF_AUTOMATIC) {
            return [
                'result' => false,
                'message' => trans('required.field')
            ];
        }
        return [
            'result' => true,
            'message' => null
        ];
    }

    public function mustBeNullIfAutomatic($value, $ruleValue, $form)
    {
        App::getContainer()->wireService('WebshopPackage/entity/CartTrigger');
        if ($value && strlen($value) > 0 && $form->getValueCollector()->getDisplayed('effectCausingStuff') == CartTrigger::EFFECT_CAUSING_STUFF_AUTOMATIC) {
            return [
                'result' => false,
                'message' => trans('must.be.empty.if.automatic')
            ];
        }
        return [
            'result' => true,
            'message' => null
        ];
    }

    public function permittedDirectionOfChange($value, bool $ruleValue, $form)
    {
        App::getContainer()->wireService('WebshopPackage/entity/CartTrigger');
        // dump($value);
        // dump($value);
        if ($value && strlen($value) > 0 && !in_array($value, CartTrigger::PERMITTED_DIRECTIONS_OF_CHANGE)) {
            return [
                'result' => false,
                'message' => trans('direction.of.change.is.not.permitted')
            ];
        }
        return [
            'result' => true,
            'message' => null
        ];
    }

    public function permittedEffectCausingStuff($value, bool $ruleValue, $form)
    {
        App::getContainer()->wireService('WebshopPackage/entity/CartTrigger');
        if ($value && strlen($value) > 0 && !in_array($value, CartTrigger::PERMITTED_EFFECT_CAUSING_STUFFS)) {
            return [
                'result' => false,
                'message' => trans('effect.causing.stuff.is.not.permitted')
            ];
        }
        return [
            'result' => true,
            'message' => null
        ];
    }

    public function permittedEffectOperator($value, $ruleValue, $form)
    {
        App::getContainer()->wireService('WebshopPackage/entity/CartTrigger');
        if ($value && strlen($value) > 0 && !in_array($value, CartTrigger::PERMITTED_EFFECT_OPERATORS)) {
            return [
                'result' => false,
                'message' => trans('effect.operator.is.not.permitted')
            ];
        }
        return [
            'result' => true,
            'message' => null
        ];
    }

    public function zipAndCountryEffectOperatorOnlyEqualsAndNotEquals($value, $ruleValue, $form)
    {
        App::getContainer()->wireService('WebshopPackage/entity/CartTrigger');
        if ($value && strlen($value) > 0 
            && in_array($form->getValueCollector()->getDisplayed('effectCausingStuff'), [CartTrigger::EFFECT_CAUSING_STUFF_COUNTRY_ALPHA2, CartTrigger::EFFECT_CAUSING_STUFF_ZIP_CODE_MASK]) 
            && !in_array($value, [CartTrigger::EFFECT_OPERATOR_EQUALS, CartTrigger::EFFECT_OPERATOR_NOT_EQUALS])) {
            return [
                'result' => false,
                'message' => trans('country.and.zip.effect.operator.can.only.be.equals.and.not.equals')
            ];
        }
        return [
            'result' => true,
            'message' => null
        ];
    }

    public function grossTotalProceEffectOperatorOnlyLessAndMoreThan($value, $ruleValue, $form)
    {
        App::getContainer()->wireService('WebshopPackage/entity/CartTrigger');
        if ($value && strlen($value) > 0 
            && $form->getValueCollector()->getDisplayed('effectCausingStuff') == CartTrigger::EFFECT_CAUSING_STUFF_GROSS_TOTAL_PRICE 
            && !in_array($value, [CartTrigger::EFFECT_OPERATOR_LESS_THAN, CartTrigger::EFFECT_OPERATOR_MORE_THAN])) {
            return [
                'result' => false,
                'message' => trans('price.effect.operator.can.only.be.less.and.more.than')
            ];
        }
        return [
            'result' => true,
            'message' => null
        ];
    }



    public function validCountryAlpha2($value, $ruleValue, $form)
    {
        App::getContainer()->wireService('WebshopPackage/entity/CartTrigger');
        if ($value && strlen($value) > 0 && $form->getValueCollector()->getDisplayed('effectCausingStuff') == CartTrigger::EFFECT_CAUSING_STUFF_COUNTRY_ALPHA2) {
            $result = true;
            if (strlen($value) !== 2) {
                $result = false;
            }
            if (!ctype_alpha($value)) {
                $result = false;
            }

            if (!$result) {
                return [
                    'result' => false,
                    'message' => trans('invalid.alpha2.code')
                ];
            }
        }
        return [
            'result' => true,
            'message' => null
        ];
    }

    public function validZipCodeMask($value, $ruleValue, $form)
    {
        App::getContainer()->wireService('WebshopPackage/entity/CartTrigger');
        if ($value && strlen($value) > 0 && $form->getValueCollector()->getDisplayed('effectCausingStuff') == CartTrigger::EFFECT_CAUSING_STUFF_ZIP_CODE_MASK) {
            if (!(preg_match('/^[0-9*\-]+$/', $value)) || strlen($value) > 4) {
                return [
                    'result' => false,
                    'message' => trans('invalid.zip.code.mask')
                ];
            }
        }
        return [
            'result' => true,
            'message' => null
        ];
    }

    public function validGrossTotalPrice($value, $ruleValue, $form)
    {
        App::getContainer()->wireService('WebshopPackage/entity/CartTrigger');
        if ($value && strlen($value) > 0 && $form->getValueCollector()->getDisplayed('effectCausingStuff') == CartTrigger::EFFECT_CAUSING_STUFF_GROSS_TOTAL_PRICE) {
            $dotPos = strpos($value, '.');
            $ePos = strpos($value, 'e');
            if (!is_numeric($value) || $dotPos !== false || $ePos !== false) {
                return [
                    'result' => false,
                    'message' => trans('invalid.gross.total.price')
                ];
            }
        }
        return [
            'result' => true,
            'message' => null
        ];
    }
}