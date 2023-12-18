<?php
namespace framework\packages\WebshopPackage\form;

use framework\component\helper\MathHelper;
use framework\component\parent\CustomFormValidator;
use framework\packages\WebshopPackage\repository\ProductPriceRepository;

class WebshopCustomValidator extends CustomFormValidator
{
    public function isNumeric($value, $ruleValue, $form)
    {
        if (!is_numeric($value)) {
            // dump(gettype($value));exit;
            return [
                'result' => false,
                'message' => trans('must.be.numeric')
            ];
        }
        return [
            'result' => true,
            'message' => null
        ];
    }

    public function requiredIfCorporateTriggered($value, $ruleValue, $form)
    {
        $triggeredCorporate = $form->getValueCollector()->getValue('triggerCorporate', 'displayed') !== null;
        // dump($triggeredCorporate);exit;
        if ($triggeredCorporate) {
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

    public function wholeNumber($value, $ruleValue, $form)
    {
        if (MathHelper::isWholeNumber($value)) {
            return [
                'result' => true,
                'message' => null
            ];
        } else {
            return [
                'result' => false,
                'message' => trans('must.be.whole.number')
            ];
        }
    }

    public function lessThanGrossListPrice($value, $ruleValue, $form)
    {
        $newPriceType = $this->getRequest()->get('WebshopPackage_newProductPrice_priceType');
        if ($newPriceType == 'list') {
            return [
                'result' => true,
                'message' => null
            ];
        }
        $newGross = $this->getRequest()->get('WebshopPackage_newProductPrice_grossPrice');
        // $newVat = $this->getRequest()->get('WebshopPackage_newProductPrice_vat');
        // $newGross = $newNetPrice * (1 + ($newVat / 100));
        $productId = $this->getRequest()->get('productId');
        if (!$productId) {
            return [
                'result' => false,
                'message' => trans('no.product.selected')
            ];
        }

        $this->wireService('WebshopPackage/repository/ProductPriceRepository');
        $repo = new ProductPriceRepository();
        
        if ($newGross >= $repo->getGrossListPrice($productId)) {
            // dump($repo->getGrossListPrice($productId));exit;
            return [
                'result' => false,
                'message' => trans('discount.price.must.be.less.than.list.price')
            ];
        } else {
            return [
                'result' => true,
                'message' => null
            ];
        }
    }

    // public function lessThanListPrice_NET($value, $ruleValue, $form)
    // {
    //     $newPriceType = $this->getRequest()->get('WebshopPackage_newProductPrice_priceType');
    //     if ($newPriceType == 'list') {
    //         return [
    //             'result' => true,
    //             'message' => null
    //         ];
    //     }
    //     $newNetPrice = $this->getRequest()->get('WebshopPackage_newProductPrice_netPrice');
    //     $newVat = $this->getRequest()->get('WebshopPackage_newProductPrice_vat');
    //     $newGross = $newNetPrice * (1 + ($newVat / 100));
    //     $productId = $this->getRequest()->get('productId');
    //     if (!$productId) {
    //         return [
    //             'result' => false,
    //             'message' => trans('no.product.selected')
    //         ];
    //     }

    //     $this->wireService('WebshopPackage/repository/ProductPriceRepository');
    //     $repo = new ProductPriceRepository();
        
    //     if ($newGross >= $repo->getListPrice($productId)) {
    //         return [
    //             'result' => false,
    //             'message' => trans('discount.price.must.be.less.than.list.price')
    //         ];
    //     } else {
    //         return [
    //             'result' => true,
    //             'message' => null
    //         ];
    //     }
    // }

    public function sameVatAsOtherPricesOfThisProduct($value, $ruleValue, $form)
    {
        $this->wireService('WebshopPackage/repository/ProductPriceRepository');
        $productId = $this->getRequest()->get('productId');
        $newVat = $this->getRequest()->get('WebshopPackage_newProductPrice_vat');
        // $repo = new ProductPriceRepository();
        $rawAssignedVatsOfProduct = ProductPriceRepository::getAssignedVatsOfProduct($productId);
        if (isset($rawAssignedVatsOfProduct[0]['pp_vat'])) {
            $vatPossibleValues = explode(',', $rawAssignedVatsOfProduct[0]['pp_vat']);
            if (in_array($newVat, $vatPossibleValues)) {
                return [
                    'result' => true,
                    'message' => null
                ];
            } else {
                return [
                    'result' => false,
                    'message' => trans('vat.cannot.differ.from.other.prices.of.this.product').' ('.$rawAssignedVatsOfProduct[0]['pp_vat'].')'
                ];
            }
        } else {
            return [
                'result' => true,
                'message' => null
            ];
        }
    }

    // public function sameCurrencyAsOtherPricesOfThisProduct($value, $ruleValue, $form)
    // {
    //     if (in_array($value, ['list', 'discount'])) {
    //         return [
    //             'result' => true,
    //             'message' => null
    //         ];
    //     } else {
    //         return [
    //             'result' => false,
    //             'message' => trans('price.type.not.allowed')
    //         ];
    //     }
    // }

    // public function grossMustBeWholeNumber($value, $ruleValue, $form)
    // {
    //     $newPriceType = $this->getRequest()->get('WebshopPackage_newProductPrice_priceType');
    //     if ($newPriceType == 'list') {
    //         return [
    //             'result' => true,
    //             'message' => null
    //         ];
    //     }
    //     $newNetPrice = $this->getRequest()->get('WebshopPackage_newProductPrice_netPrice');
    //     $newVat = $this->getRequest()->get('WebshopPackage_newProductPrice_vat');
    //     $newGross = $newNetPrice * (1 + ($newVat / 100));

    //     $newGrossIsWholeNumber = MathHelper::isWholeNumber($newGross);

    //     if (!$newGrossIsWholeNumber) {
    //         return [
    //             'result' => false,
    //             'message' => trans('gross.must.be.whole.number')
    //         ];
    //     } else {
    //         return [
    //             'result' => true,
    //             'message' => null
    //         ];
    //     }
    // }

    public function allowedPriceTypes($value, $ruleValue, $form)
    {
        if (in_array($value, ['list', 'discount'])) {
            return [
                'result' => true,
                'message' => null
            ];
        } else {
            return [
                'result' => false,
                'message' => trans('price.type.not.allowed')
            ];
        }
    }

    public function between1And100($value, $ruleValue, $form)
    {
        if (!is_numeric($value)) {
            return [
                'result' => false,
                'message' => trans('must.be.numeric')
            ];
        }
        if ($value < 1 || $value > 100) {
            return [
                'result' => false,
                'message' => trans('must.be.between.1.and.100')
            ];
        }
        return [
            'result' => true,
            'message' => null
        ];
    }
}