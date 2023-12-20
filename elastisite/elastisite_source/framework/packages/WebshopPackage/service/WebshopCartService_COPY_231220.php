<?php
namespace framework\packages\WebshopPackage\service;

use App;
use framework\component\helper\StringHelper;
use framework\component\parent\Service;
use framework\packages\UserPackage\entity\User;
use framework\packages\UserPackage\repository\UserAccountRepository;
use framework\packages\WebshopPackage\entity\Cart;
use framework\packages\WebshopPackage\entity\CartTrigger;
use framework\packages\WebshopPackage\entity\ProductPriceActive;
use framework\packages\WebshopPackage\repository\CartItemRepository;
use framework\packages\WebshopPackage\repository\CartRepository;
use framework\packages\WebshopPackage\repository\CartTriggerRepository;
use framework\packages\WebshopPackage\repository\ProductPriceActiveRepository;
use framework\packages\WebshopPackage\repository\ProductRepository;

class WebshopCartService extends Service
{
    const PERMITTED_USER_TYPE_GUEST = 'Guest';
    const PERMITTED_USER_TYPE_USER = 'User';
    const PERMITTED_USER_TYPE_BOTH = 'Both';

    public static $cachedCart;

    // public static $cachedCartData;

    public static function getCartActiveProductPriceIds()
    {
        $result = [];
        $cart = self::getCart();
        if ($cart) {
            foreach ($cart->getCartItem() as $cartItem) {
                $activePrice = $cartItem->getProduct()->getProductPriceActive();
                if ($activePrice) {
                    $result[] = $activePrice->getId();
                }
            }
            
        }

        return $result;
    }

    // public static function removeObsoleteCarts()
    // {
    //     // dump('removeObsoleteCarts');exit;
    //     App::getContainer()->wireService('WebshopPackage/repository/CartRepository');

    //     $cartRepo = new CartRepository();
    //     $cartRepo->removeObsolete();
    // }

    public static function removeUnboundCartItems()
    {
        App::getContainer()->wireService('WebshopPackage/repository/CartItemRepository');

        $cartItemRepo = new CartItemRepository();
        $cartItemRepo->removeUnbound();
    }
    
    public static function addToCart($productPriceActiveId, $newQuantity = null, $addedQuantity = null, $skipCheckTriggers = false, $appliedBy = null)
    {
        if (!$skipCheckTriggers) {
            // self::checkAndExecuteTriggers();
        }
        self::$cachedCart = null;
        App::getContainer()->wireService('WebshopPackage/repository/CartRepository');
        App::getContainer()->wireService('WebshopPackage/repository/CartItemRepository');
        App::getContainer()->wireService('WebshopPackage/entity/ProductPriceActive');
        App::getContainer()->wireService('WebshopPackage/repository/ProductPriceActiveRepository');
        App::getContainer()->wireService('UserPackage/repository/UserAccountRepository');

        $accRepo = new UserAccountRepository();

        $productPriceActiveRepo = new ProductPriceActiveRepository();
        $productPriceActive = $productPriceActiveRepo->find($productPriceActiveId);

        // dump($productPriceActiveId);
        // dump($productPriceActive);exit;

        if ($productPriceActive instanceof ProductPriceActive) {
            $cartRepo = new CartRepository();
            // dump($this->getSession()->get('webshop_cartId'));exit;
            $cartId = null;
            if (App::getContainer()->getSession()->get('webshop_cartId')) {
                $cartId = App::getContainer()->getSession()->get('webshop_cartId');
            } 
            
            $cart = !$cartId ? null : $cartRepo->find($cartId);

            if (!$cart) {
                $cart = $cartRepo->createNewEntity();
                $userAccount = $accRepo->find(App::getContainer()->getUser()->getId());
                if ($userAccount) {
                    $cart->setUserAccount($userAccount);
                }
                App::getContainer()->wireService('WebshopPackage/service/WebshopTemporaryAccountService');
                $blankTemporaryAccount = WebshopTemporaryAccountService::createBlankTemporaryAccount();
                $cart->setTemporaryAccount($blankTemporaryAccount);
                $cart->setVisitorCode(App::getContainer()->getSession()->get('visitorCode'));
                $cart->setCreatedAt(App::getContainer()->getCurrentTimestamp());
                $cart = $cartRepo->store($cart);
                $cartId = $cart->getId();
                // dump($cart);exit;
                App::getContainer()->getSession()->set('webshop_cartId', $cartId);
            }

            $cartItemRepo = new CartItemRepository();
            // if (!$productPriceActive->getProductPrice()) {
            //     dump($productPriceActiveId);
            //     dump($productPriceActive);exit;
            // }
            $cartItem = $cartItemRepo->findOneBy(['conditions' => [
                ['key' => 'cart_id', 'value' => $cartId], 
                ['key' => 'product_id', 'value' => $productPriceActive->getProduct()->getId()],
                ['key' => 'product_price_id', 'value' => $productPriceActive->getProductPrice()->getId()]
            ]]);

            if (!$cartItem) {
                $originalQuantity = 0;
                $cartItem = $cartItemRepo->createNewEntity();
                $cartItem->setQuantity($originalQuantity);
                $cartItem->setCart($cart);
            } else {
                $originalQuantity = $cartItem->getQuantity();
            }

            if (!empty($newQuantity) && $newQuantity == $originalQuantity) {
                return null;
            }

            if (is_numeric($addedQuantity) && $newQuantity === null) {
                $quantity = $originalQuantity + $addedQuantity;
            } elseif ($addedQuantity === null && is_numeric($newQuantity)) {
                $quantity = $newQuantity;
            } else {
                throw new \Exception('$addedQuantity and $newQuantity cannot be null at once.');
            }

            if ($originalQuantity > $quantity) {
                return self::removeFromCart($productPriceActiveId, ($originalQuantity - $quantity));
            }

            $cartItem->setQuantity($quantity);

            // dump($addedQuantity);
            // dump($newQuantity);
            // dump($cartItem);exit;

            $cartItem->setProduct($productPriceActive->getProduct());
            $cartItem->setProductPrice($productPriceActive->getProductPrice());
            $cartItem->setAppliedBy($appliedBy);
            $cartItem = $cartItemRepo->store($cartItem);
            // $cart->addCartItem($cartItem);
            return $cartItem;
        } else {
            return false;
        }
    }

    public static function removeFromCartIfAppliedByMatches($productPriceActiveId, $quantity, $skipCheckTriggers, $appliedBy)
    {
        self::removeFromCart($productPriceActiveId, $quantity, $skipCheckTriggers, $appliedBy);
    }

    public static function removeFromCart($productPriceActiveId, $quantity, $skipCheckTriggers = false, $removeIfAppliedBy = null)
    {
        if (!$skipCheckTriggers) {
            // self::checkAndExecuteTriggers();
        }
        self::$cachedCart = null;
        App::getContainer()->wireService('WebshopPackage/repository/CartRepository');
        App::getContainer()->wireService('WebshopPackage/repository/CartItemRepository');
        App::getContainer()->wireService('WebshopPackage/entity/ProductPriceActive');
        App::getContainer()->wireService('WebshopPackage/repository/ProductPriceActiveRepository');
        App::getContainer()->wireService('UserPackage/repository/UserAccountRepository');

        // $accRepo = new UserAccountRepository();

        $productPriceActiveRepo = new ProductPriceActiveRepository();
        $productPriceActive = $productPriceActiveRepo->find($productPriceActiveId);

        if ($productPriceActive instanceof ProductPriceActive) {
            $cartRepo = new CartRepository();
            // dump($this->getSession()->get('webshop_cartId'));exit;
            $cartId = null;
            if (App::getContainer()->getSession()->get('webshop_cartId')) {
                $cartId = App::getContainer()->getSession()->get('webshop_cartId');
            }

            $cart = !$cartId ? null : $cartRepo->find($cartId);
            if (!$cart) {
                App::getContainer()->getSession()->set('webshop_cartId', null);
            }

            $cartItemRepo = new CartItemRepository();
            $cartItem = $cartItemRepo->findOneBy(['conditions' => [
                ['key' => 'cart_id', 'value' => $cartId], 
                ['key' => 'product_id', 'value' => $productPriceActive->getProduct()->getId()],
                ['key' => 'product_price_id', 'value' => $productPriceActive->getProductPrice()->getId()]
            ]]);

            if ($cartItem) {
                /**
                 * If we have a cart trigger, which placed a stuff to the cart, but meanwhile the cart trigger was disabled, than 
                 * the trigger handling process tries to remove the item placed by the meanwhile disabled trigger.
                */
                if ($removeIfAppliedBy && $cartItem->getAppliedBy() != $removeIfAppliedBy) {
                    // dump($removeIfAppliedBy);exit;
                    return false;
                }
                $q = $cartItem->getQuantity() - $quantity;
                if ($q <= 0) {
                    // dump($cartItem->getId());exit;
                    $cartItemRepo->removeBy(['id' => $cartItem->getId()]);
                    // dump($cartItem->getId());exit;
                    $cartRepo->find($cartId);
                    if ($cart) {
                        $cartItems = $cartItemRepo->findBy(['conditions' => [['key' => 'cart_id', 'value' => $cart->getId()]]]);
                        if (!$cartItems || ($cartItems && count($cartItems) == 0)) {
                            // dump($cartItems);exit;
                            $cartRepo::removeObsolete(
                                [['refKey' => 'c.id', 'paramKey' => 'cart_id_to_remove', 'operator' => '=', 'value' => $cart->getId()]],
                                false
                            );
                        }
                    }
                } else {
                    $cartItem->setQuantity($q);
                    $cartItem->setProduct($productPriceActive->getProduct());
                    $cartItem->setProductPrice($productPriceActive->getProductPrice());
                    $cartItem = $cartItemRepo->store($cartItem);
                }
            }

            return $cartItem;
        } else {
            return false;
        }
    }

    public static function removeObsoleteCarts($sessionCartIdIsUnremovable = false)
    {
        // self::checkAndExecuteTriggers();
        // dump('removeOldCart');exit;
        App::getContainer()->wireService('WebshopPackage/repository/CartRepository');
        // $cartRepo = new CartRepository();
        CartRepository::removeObsolete(
            [['refKey' => 'c.user_account_id', 'paramKey' => 'c_user_account_id', 'operator' => '=', 'value' => App::getContainer()->getSession()->get('userId')]],
            $sessionCartIdIsUnremovable
        );

        CartRepository::removeObsolete(
            [['refKey' => 'c.visitor_code', 'paramKey' => 'c_visitor_code', 'operator' => '=', 'value' => App::getContainer()->getSession()->get('visitorCode')]],
            $sessionCartIdIsUnremovable
        );

        // $carts = $cartRepo->findBy(['user_account_id' => App::getContainer()->getSession()->get('userId')]);
        // $carts = array_merge($carts, $cartRepo->findBy(['visitor_code' => App::getContainer()->getSession()->get('visitorCode')]));
        // // dump($carts);exit;
        // foreach ($carts as $cart) {
        //     $temporaryAccount = $cart->getTemporaryAccount();
        //     if ($temporaryAccount) {
        //         $temporaryAccount->getRepository()->remove($temporaryAccount->getId());
        //     }
        //     $cart->getRepository()->remove($cart->getId());
        // }
    }

    public static function identifyCart()
    {
        // self::checkAndExecuteTriggers();
        App::getContainer()->wireService('UserPackage/repository/UserAccountRepository');
        $accRepo = new UserAccountRepository();

        App::getContainer()->wireService('WebshopPackage/repository/CartRepository');
        $cartRepo = new CartRepository();

        $carts = $cartRepo->findBy(['conditions' => [['key' => 'visitor_code', 'value' => App::getContainer()->getSession()->get('visitorCode')]]]);
        if (count($carts) == 1) {
            $userAccount = $accRepo->find(App::getContainer()->getSession()->get('userId'));
            $cart = $carts[0];
            // dump($this->getSession()->get('userId'));exit;
            if ($userAccount) {
                // dump($carts[0]);exit;
                $cart->setUserAccount($userAccount);
                $cart = $cartRepo->store($cart);
            }
            App::getContainer()->getSession()->set('webshop_cartId', $cart->getId());
        } elseif (count($carts) > 1) {
            // $cartRepo->removeBy(['visitor_code' => App::getContainer()->getSession()->get('visitorCode')]);
            CartRepository::removeObsolete(
                [['refKey' => 'c.visitor_code', 'paramKey' => 'c_visitor_code', 'operator' => '=', 'value' => App::getContainer()->getSession()->get('visitorCode')]],
                false
            );
        }
    }

    // public static function getExtendedCartData($offerId)
    // {

    // }

    // public static function getCartData()
    // {
    //     self::checkAndExecuteTriggers();

    //     if (self::$cachedCartData) {
    //         return self::$cachedCartData;
    //     }
    //     App::getContainer()->wireService('WebshopPackage/repository/ProductRepository');
    //     $productRepo = new ProductRepository();

    //     // App::getContainer()->wireService('WebshopPackage/service/WebshopRequestService');
    //     // $processedRequestData = WebshopRequestService::getProcessedRequestData();

    //     App::getContainer()->wireService('WebshopPackage/dataProvider/ProductListDataProvider');

    //     $cart = self::getCart();
    //     if ($cart && !$cart->getTemporaryAccount()) {
    //         App::getContainer()->wireService('WebshopPackage/service/WebshopTemporaryAccountService');
    //         $cart->setTemporaryAccount(WebshopTemporaryAccountService::createBlankTemporaryAccount());
    //         $cart->getRepository()->store($cart);
    //     }

    //     $cartItemsData = [];
    //     if ($cart && $cart->getCartItem()) {
    //         foreach ($cart->getCartItem() as $cartItem) {
    //             $product = $cartItem->getProduct() ? : null;
    //             $productId = null;
    //             $productData = null;
    //             if ($product) {
    //                 $productId = $product->getId();
    //                 $rawProductsData = $productRepo->getProductsData(App::getContainer()->getSession()->getLocale(), [
    //                     'productId' => $productId,
    //                 ], []);
    //                 $productsData = ProductListDataProvider::arrangeProductsData($rawProductsData);
    //                 if (isset($productsData[0])) {
    //                     $productData = $productsData[0];
    //                 }
    //             }
    //             $productPriceActive = $product ? $product->getProductPriceActive() : null;
    //             // $productCategory = $product && $product->getProductCategory() ? $product->getProductCategory() : null;
    //             $cartItemKey = $productId ? 'productId-'.$productId : 'cartItemId-'.$cartItem->getId();
    //             $cartItemsData[$cartItemKey] = [
    //                 'id' => $cartItem->getId(),
    //                 'product' => [
    //                     'id' => $product ? $product->getId() : null,
    //                     'offerId' => $productPriceActive ? $productPriceActive->getId() : null,
    //                     'productData' => $productData
    //                 ],
    //                 'quantity' => $cartItem->getQuantity()
    //             ];
    //         }
    //     }

    //     dump($cartItemsData);
    //     dump('============================');
    //     dump(self::assembleCartDataSet($cart));
    //     // dump(self::getCartProductData($cart->getId()));
    //     exit;
    //     // $temporaryAccountData = WebshopTemporaryAccountService::getTemporaryAccountData();

    //     $cartData = [
    //         'cart' => [
    //             'id' => $cart ? $cart->getId() : null,
    //             // 'addressId' => $cart && $cart->getAddress() ? $cart->getAddress()->getId() : null,
    //             // 'organizationId' => $cart && $cart->getOrganization() ? $cart->getOrganization()->getId() : null,
    //             // 'recipient' => $cart ? $cart->getRecipient() : null,
    //             // 'customerNote' => $cart ? $cart->getNote() : null
    //         ],
    //         'cartProductData' => $cart ? self::getCartProductData($cart->getId()) : null,
    //         // 'cartItems' => $cartItemsData,
    //         // 'productsData' => $productsData
    //     ];

    //     // dump($cartData);exit;
    //     self::$cachedCartData = $cartData;

    //     return $cartData;
    // }

    public static function getCart() : ? Cart
    {
        if (self::$cachedCart) {
            return self::$cachedCart;
        }
        App::getContainer()->wireService('WebshopPackage/repository/CartRepository');
        $cartRepo = new CartRepository();
        $cartId = App::getContainer()->getSession()->get('webshop_cartId');
        if ($cartId) {
            $foundCart = $cartRepo->find($cartId);
            // dump($foundCart->getEntityAttributes());exit;
            self::$cachedCart = $foundCart;
            return $foundCart;
        }
        // dump($foundCart);

        return null;
    }

    public static function updateTemporaryPersonWithAuthenticatedUserData()
    {
        // return true;
        $user = App::getContainer()->getUser();
        if ($user->getUserAccount()->getPerson()) {
            $name = $user->getUserAccount()->getPerson()->getFullName();
            $email = $user->getUserAccount()->getPerson()->getEmail();
            $mobile = $user->getUserAccount()->getPerson()->getMobile();
            App::getContainer()->wireService('WebshopPackage/service/WebshopTemporaryAccountService');
            $temporaryAccount = WebshopTemporaryAccountService::getTemporaryAccount();
            if ($temporaryAccount && $temporaryAccount->getTemporaryPerson()) {
                $temporaryAccount->getTemporaryPerson()->setName($name);
                $temporaryAccount->getTemporaryPerson()->setRecipientName($name);
                $temporaryAccount->getTemporaryPerson()->setEmail($email);
                $temporaryAccount->getTemporaryPerson()->setMobile($mobile);
                $temporaryAccount->getRepository()->store($temporaryAccount);
            } else {
                // dump(':o');exit;
            }
        }
    }

    public static function checkAndExecuteTriggers()
    {
        // dump('checkAndExecuteTriggers');exit;
        $cart = self::getCart();
        if (!$cart) {
            return true;
        }
        App::getContainer()->wireService('WebshopPackage/entity/CartTrigger');
        App::getContainer()->wireService('WebshopPackage/repository/CartTriggerRepository');
        $cartTriggerRepository = new CartTriggerRepository();
        $cartTriggers = $cartTriggerRepository->findBy([
            'conditions' => [
                ['key' => 'website', 'value' => App::getWebsite()],
                // ['key' => 'status', 'value' => CartTrigger::STATUS_ENABLED],
            ],
        ]);

        // dump($cartTriggers);

        /**
         * The reson of this variable: you can make multiple triggers for one product.
         * And we simply collect them, and if another trigger of the same product has the 
         * opposite direction of change, we just simply disable that pre-registered trigger.
        */
        $productTriggerCollection = [];
        $cartTriggerCollection = [];
        $inactiveTriggers = [];
        $cartDataSet = self::assembleCartDataSet($cart);
        foreach ($cartTriggers as $cartTrigger) {
            if ($cartTrigger->getStatus() == CartTrigger::STATUS_DISABLED) {
                $cartTriggerCollection = self::handleCartTriggerCollection($cartTriggerCollection, $cartTrigger, false, true);
                $inactiveTriggers[] = $cartTrigger->getId();
            } elseif ($cartTrigger->getStatus() == CartTrigger::STATUS_ENABLED) {
                // dump($cartTrigger->getId());
                /**
                 * Step 1: Find if the trigger applies or not.
                 * 
                 * Note: all three values below can be null also. 
                 * This check runs in the beginning and at the checkout also, and ZipCode just comes to the database while the checkout process.
                */
                // $productId = $cartTrigger->getProduct()->getId();
                $customerZipCode = $cartDataSet['customer']['address']['zipCode'];
                $customerCountryAlpha2Code = $cartDataSet['customer']['address']['country']['alpha2Code'];
                $sumGrossItemPriceRounded2 =  $cartDataSet['cart']['summary']['sumGrossItemPriceRounded2'];

                /**
                 * CountryAlpha2
                */
                if ($cartTrigger->getEffectCausingStuff() == CartTrigger::EFFECT_CAUSING_STUFF_COUNTRY_ALPHA2) {
                    if ($cartTrigger->getEffectOperator() == CartTrigger::EFFECT_OPERATOR_EQUALS) {
                        // if (strtoupper($cartTrigger->getEffectCausingValue()) == strtoupper($customerCountryAlpha2Code)) {
                        //     $productTriggerCollection = self::handleProductTriggerCollection($productTriggerCollection, $productId, $cartTrigger);
                        // }
                        $triggered = (strtoupper($cartTrigger->getEffectCausingValue()) == strtoupper($customerCountryAlpha2Code)) ? true : false;
                        $cartTriggerCollection = self::handleCartTriggerCollection($cartTriggerCollection, $cartTrigger, $triggered);
                    } elseif ($cartTrigger->getEffectOperator() == CartTrigger::EFFECT_OPERATOR_NOT_EQUALS) {
                        // if (strtoupper($cartTrigger->getEffectCausingValue()) != strtoupper($customerCountryAlpha2Code)) {
                        //     $productTriggerCollection = self::handleProductTriggerCollection($productTriggerCollection, $productId, $cartTrigger);
                        // }
                        $triggered = (strtoupper($cartTrigger->getEffectCausingValue()) != strtoupper($customerCountryAlpha2Code)) ? true : false;
                        $cartTriggerCollection = self::handleCartTriggerCollection($cartTriggerCollection, $cartTrigger, $triggered);
                    }
                } 
                /**
                 * ZipCodeMask
                */
                elseif ($cartTrigger->getEffectCausingStuff() == CartTrigger::EFFECT_CAUSING_STUFF_ZIP_CODE_MASK) {
                    $zipCheck = self::checkZipCodeMask($customerZipCode, $cartTrigger->getEffectCausingValue());
                    if ($cartTrigger->getEffectOperator() == CartTrigger::EFFECT_OPERATOR_EQUALS) {
                        // if ($zipCheck) {
                        //     $productTriggerCollection = self::handleProductTriggerCollection($productTriggerCollection, $productId, $cartTrigger);
                        // }
                        $triggered = (!empty($customerZipCode) && $zipCheck) ? true : false;
                        $cartTriggerCollection = self::handleCartTriggerCollection($cartTriggerCollection, $cartTrigger, $triggered);
                    } elseif ($cartTrigger->getEffectOperator() == CartTrigger::EFFECT_OPERATOR_NOT_EQUALS) {
                        // if (!$zipCheck) {
                        //     $productTriggerCollection = self::handleProductTriggerCollection($productTriggerCollection, $productId, $cartTrigger);
                        // }
                        $triggered = (!empty($customerZipCode) && !$zipCheck) ? true : false;
                        $cartTriggerCollection = self::handleCartTriggerCollection($cartTriggerCollection, $cartTrigger, $triggered);
                    }
                }
                /**
                 * GrossTotalPrice
                */
                elseif ($cartTrigger->getEffectCausingStuff() == CartTrigger::EFFECT_CAUSING_STUFF_GROSS_TOTAL_PRICE) {
                    // $zipCheck = self::checkZipCodeMask($customerZipCode, $cartTrigger->getEffectCausingValue());
                    if ($cartTrigger->getEffectOperator() == CartTrigger::EFFECT_OPERATOR_LESS_THAN) {
                        // if ((int)$sumGrossItemPriceRounded2 < (int)$cartTrigger->getEffectCausingValue()) {
                        //     $productTriggerCollection = self::handleProductTriggerCollection($productTriggerCollection, $productId, $cartTrigger);
                        // }
                        $triggered = ((int)$sumGrossItemPriceRounded2 < (int)$cartTrigger->getEffectCausingValue()) ? true : false;
                        $cartTriggerCollection = self::handleCartTriggerCollection($cartTriggerCollection, $cartTrigger, $triggered);
                    } elseif ($cartTrigger->getEffectOperator() == CartTrigger::EFFECT_OPERATOR_MORE_THAN) {
                        // if ((int)$sumGrossItemPriceRounded2 > (int)$cartTrigger->getEffectCausingValue()) {
                        //     $productTriggerCollection = self::handleProductTriggerCollection($productTriggerCollection, $productId, $cartTrigger);
                        // }
                        $triggered = ((int)$sumGrossItemPriceRounded2 > (int)$cartTrigger->getEffectCausingValue()) ? true : false;
                        $cartTriggerCollection = self::handleCartTriggerCollection($cartTriggerCollection, $cartTrigger, $triggered);
                    }
                }
                /**
                 * Automatic
                */
                elseif ($cartTrigger->getEffectCausingStuff() == CartTrigger::EFFECT_CAUSING_STUFF_AUTOMATIC) {
                    $cartTriggerCollection = self::handleCartTriggerCollection($cartTriggerCollection, $cartTrigger, true);
                }
            } // status enabled
        } // foreach 

        // dump($cartTriggerCollection);

        /**
         * Step 2: Search for the trigger's product in the cart, and if we found one, than we deactivate it.
        */
        // $foundCartItemData = null;
        foreach ($cartDataSet['cart']['cartItems'] as $cartItemData) {
            /**
             * Check cart items if we have a pre-registered trigger for any of those product
            */
            if (isset($cartTriggerCollection[$cartItemData['cartItem']['product']['productId']])) {
                $cartTriggerRow = $cartTriggerCollection[$cartItemData['cartItem']['product']['productId']];
                /**
                 * At this point we ONLY handle the DISCARD request, we will handle the applies later, together with the new items.
                */
                if ($cartTriggerRow['discardRequestedBy']) {
                    if (in_array($cartTriggerRow['discardRequestedBy'], $inactiveTriggers)) {
                        if (!$cartTriggerRow['applyRequestedBy']) {
                            self::removeFromCartIfAppliedByMatches($cartTriggerRow['productPriceActiveId'], 1, true, $cartTriggerRow['applyRequestedBy']);
                            App::getContainer()->getSession()->addMessage('cartUpdated', trans('cart.updated'));
                        }
                    } else {
                        self::removeFromCart($cartTriggerRow['productPriceActiveId'], 1, true);
                        App::getContainer()->getSession()->addMessage('cartUpdated', trans('cart.updated'));
                    }
                    // self::removeFromCart($cartTriggerRow['productPriceActiveId'], 1, true);
                }
            }
        }

        foreach ($cartTriggerCollection as $cartTriggerRow) {
            if ($cartTriggerRow['applyRequestedBy']) {
                $cartItem = self::addToCart($cartTriggerRow['productPriceActiveId'], 1, null, true, $cartTriggerRow['applyRequestedBy']);
                if ($cartItem) {
                    /**
                     * Returns null, if new quantity == original quantity
                    */
                    App::getContainer()->getSession()->addMessage('cartUpdated', trans('cart.updated'));
                }
            }
        }

        // dump($cartTriggerCollection);exit;

        // dump($cartDataSet);exit;
    }

    public static function handleCartTriggerCollection($cartTriggerCollection, CartTrigger $cartTrigger, $triggered, $calledByInactive = false)
    {
        if (!$cartTrigger->getProduct() || !$cartTrigger->getProduct()->getProductPriceActive()) {
            /**
             * If no price set to the product, we don't want to punist the customer.
            */
            App::sendDevelopersMessage('This trigger has problems: '.$cartTrigger->getId());
            return $cartTriggerCollection;
        }
        $productPriceActiveId = $cartTrigger->getProduct()->getProductPriceActive()->getId();

        $pattern = [
            'productId' => null,
            'productPriceActiveId' => null,
            'name' => null,
            'applyRequestedBy' => null,
            'discardRequestedBy' => null
        ];

        $productId = $cartTrigger->getProduct()->getId();
        $name = $cartTrigger->getName();
        $cartTriggerCollectionRow = null;

        if (isset($cartTriggerCollection[$productId])) {
            $cartTriggerCollectionRow = $cartTriggerCollection[$productId];
            $applyRequestedBy = $cartTriggerCollectionRow['applyRequestedBy'];
            $discardRequestedBy = $cartTriggerCollectionRow['discardRequestedBy'];
        } else {
            $applyRequestedBy = null;
            $discardRequestedBy = null;
            $cartTriggerCollectionRow = $pattern;
        }

        if ($cartTrigger->getDirectionOfChange() == CartTrigger::DIRECTION_OF_CHANGE_APPLY) {
            if ($triggered) {
                /**
                 * If it's triggered, we should put the product into the cart.
                */
                $applyRequestedBy = $cartTrigger->getId();
            } else {
                /**
                 * If it's NOT triggered, we should remove the product from the cart.
                */
                $discardRequestedBy = $cartTrigger->getId();
            }
        }
        if ($cartTrigger->getDirectionOfChange() == CartTrigger::DIRECTION_OF_CHANGE_DISCARD) {
            if ($triggered) {
                /**
                 * It's a discard request, so we just remove the product from the cart, if triggered.
                */
                $discardRequestedBy = $cartTrigger->getId();
            }
            /**
             * In this case we only can remove this product from the cart, the untiggering should not place this inside that.
            */
        }
        
        $cartTriggerCollectionRow = [
            'productId' => $productId,
            'productPriceActiveId' => $productPriceActiveId,
            'name' => $name,
            'applyRequestedBy' => $applyRequestedBy,
            'discardRequestedBy' => $discardRequestedBy
        ];

        $cartTriggerCollection[$productId] = $cartTriggerCollectionRow;

        // $cartTriggerCollection[$productId]['applyRequestedBy'] = $cartTrigger->getId();
        // $cartTriggerCollection[$productId]['discardRequestedBy'] = $cartTrigger->getId();
        // dump($productTriggerCollection);
        // $productTriggerCollection
        return $cartTriggerCollection;
    }

    // public static function handleProductTriggerCollection($productTriggerCollection, $productId, CartTrigger $cartTrigger)
    // {
    //     if (isset($productTriggerCollection[$productId])) {
    //         /**
    //          * I separated that if, because I just want the else branch for the existing product id.
    //          * Here I do not make an else, so those triggers which would trigger the same product to the same direction will go to the soup.
    //         */
    //         if ($productTriggerCollection[$productId]['directionOfChange'] != $cartTrigger->getDirectionOfChange()) {
    //             $productTriggerCollection[$productId]['deactivated'] = true;
    //         }
    //     } else {
    //         $productTriggerCollection[$productId] = [
    //             'directionOfChange' => $cartTrigger->getDirectionOfChange(),
    //             'deactivated' => false,
    //             'product' => $cartTrigger->getProduct()
    //         ];
    //     }

    //     return $productTriggerCollection;
    // }

    public static function checkZipCodeMask($zipCode, $zipCodeMask)
    {
        $zipCode = (string)$zipCode;
        $zipCodeMask = (string)$zipCodeMask;
        if (strlen($zipCode) != strlen($zipCodeMask)) {
            return false;
        }
    
        for ($i = 0; $i < strlen($zipCodeMask); $i++) {
            if ($zipCodeMask[$i] !== '*' && $zipCodeMask[$i] !== $zipCode[$i]) {
                return false;
            }
        }
    
        return true;
    }

    // public static function getCartTriggers()
    // {

    // }

    // public static function checkTriggerConditions(CartTrigger $cartTrigger)
    // {
    //     App::getContainer()->wireService('WebshopPackage/entity/CartTrigger');

    //     if ($cartTrigger->getEffectCausingStuff() == CartTrigger::EFFECT_CAUSING_STUFF_COUNTRY_ALPHA2) {
    //         /**
    //          * We are checking the country
    //         */

    //     }
    //     if ($cartTrigger->getEffectCausingStuff() == CartTrigger::EFFECT_CAUSING_STUFF_ZIP_CODE_MASK) {
    //         /**
    //          * We are checking the zip code
    //         */
            
    //     }

    //     $zipCode = $this->getZipCodeFromCity($city); // Implementáld a város alapján az irányítószám kinyerését
    
    //     // Az EFFECT_OPERATOR_NOT_EQUALS esetén az ellenőrzés inverze
    //     if ($cartTrigger->getEffectOperator() === $cartTrigger::EFFECT_OPERATOR_NOT_EQUALS) {
    //         if (self::checkZipCodeMask($zipCode, $this->effectCausingValue)) {
    //             return false; // Nem egyezik meg, triggerelődik
    //         }
    //     } else {
    //         // Az egyéb esetek
    //         if (!self::checkZipCodeMask($zipCode, $this->effectCausingValue)) {
    //             return false;
    //         }
    //     }
    
    //     return true;
    // }

    // public static function checkTriggerConditions(CartTrigger $cartTrigger, $grossTotal, $country, $city)
    // {
    //     App::getContainer()->wireService('WebshopPackage/entity/CartTrigger');
    //     // Egyéb feltételek ellenőrzése
    
    //     // Az irányítószám-maszk ellenőrzése
    //     $zipCode = $this->getZipCodeFromCity($city); // Implementáld a város alapján az irányítószám kinyerését
    
    //     // Az EFFECT_OPERATOR_NOT_EQUALS esetén az ellenőrzés inverze
    //     if ($cartTrigger->getEffectOperator() === $cartTrigger::EFFECT_OPERATOR_NOT_EQUALS) {
    //         if (self::checkZipCodeMask($zipCode, $this->effectCausingValue)) {
    //             return false; // Nem egyezik meg, triggerelődik
    //         }
    //     } else {
    //         // Az egyéb esetek
    //         if (!self::checkZipCodeMask($zipCode, $this->effectCausingValue)) {
    //             return false;
    //         }
    //     }
    
    //     return true;
    // }  

    

    public static function getCartProductData(int $cartId, $debug = false)
    {
        if (empty($cartId)) {
            return null;
        }
        App::getContainer()->wireService('WebshopPackage/repository/CartRepository');
        App::getContainer()->wireService('WebshopPackage/dataProvider/ProductListDataProvider');
        $rawCartProductData = CartRepository::getCartProductData(App::getContainer()->getSession()->getLocale(), false, [$cartId], $debug);
        // dump($rawCartProductData);//exit;
        $arrangedCartProductData = ProductListDataProvider::arrangeProductsData($rawCartProductData);
        // dump($arrangedCartProductData);exit;
        return $arrangedCartProductData;
    }

    public static function assembleCartDataSet(Cart $cart = null) : ? array
    {
        if (!$cart) {
            $cart = self::getCart();
        }
        if (!$cart) {
            return null;
        }
        App::getContainer()->wireService('WebshopPackage/repository/CartRepository');
        App::getContainer()->wireService('WebshopPackage/service/WebshopInvoiceService');

        $shipmentProductData = self::getCartProductData($cart->getId());

        $shipmentItemPattern = [
            'cartItem' => [
                'id' => null,
                'product' => [],
                'quantity' => null
            ]
        ];
        $shipmentPattern = [
            'customer' => [
                'name' => null,
                'type' => null,
                'note' => null,
                'email' => null,
                'address' => WebshopInvoiceService::getRawAddressPattern()
            ],
            'cart' => [
                'id' => null,
                'permittedUserType' => null,
                'permittedForCurrentUser' => null,
                'publicStatusText' => null,
                'adminStatusText' => null,
                'cartItems' => [],
                'payments' => [
                    'active' => null,
                    'successful' => null,
                    'failedForever' => []
                ],
                'currencyCode' => null,
                'confirmationSentAt' => null,
                'summary' => [
                    'sumGrossItemPriceRounded2' => null,
                    'sumGrossItemPriceFormatted' => null
                ]
            ]
        ];

        $currencyCode = null;
        $shipmentData = $shipmentPattern;
        $shipmentData['cart']['id'] = $cart->getId();

        if ($cart->getTemporaryAccount() && $cart->getTemporaryAccount()->getTemporaryPerson()) {
            $customerName = $cart->getTemporaryAccount()->getTemporaryPerson()->getName();
            $recipientName = $cart->getTemporaryAccount()->getTemporaryPerson()->getRecipientName();
            $customerType = $cart->getTemporaryAccount()->getTemporaryPerson()->getCustomerType();
            $customerNote = $cart->getTemporaryAccount()->getTemporaryPerson()->getCustomerNote();
            $customerEmail = $cart->getTemporaryAccount()->getTemporaryPerson()->getEmail();
            $shipmentData['customer']['name'] = $recipientName ? : $customerName;
            $shipmentData['customer']['type'] = $customerType;
            $shipmentData['customer']['note'] = $customerNote;
            $shipmentData['customer']['email'] = $customerEmail;
            if ($cart->getTemporaryAccount()->getTemporaryPerson()->getAddress()) {
                if ($cart->getTemporaryAccount()->getTemporaryPerson()->getAddress()->getCountry()) {
                    $shipmentData['customer']['address']['country']['alpha2Code'] = $cart->getTemporaryAccount()->getTemporaryPerson()->getAddress()->getCountry()->getAlphaTwo();
                    $shipmentData['customer']['address']['country']['translatedName'] = trans($cart->getTemporaryAccount()->getTemporaryPerson()->getAddress()->getCountry()->getTranslationReference());
                }
                $shipmentData['customer']['address']['zipCode'] = $cart->getTemporaryAccount()->getTemporaryPerson()->getAddress()->getZipCode();
                $shipmentData['customer']['address']['city'] = $cart->getTemporaryAccount()->getTemporaryPerson()->getAddress()->getCity();
                $shipmentData['customer']['address']['street'] = $cart->getTemporaryAccount()->getTemporaryPerson()->getAddress()->getStreet();
                $shipmentData['customer']['address']['streetSuffix'] = $cart->getTemporaryAccount()->getTemporaryPerson()->getAddress()->getStreetSuffix();
                $shipmentData['customer']['address']['houseNumber'] = $cart->getTemporaryAccount()->getTemporaryPerson()->getAddress()->getHouseNumber();
                $shipmentData['customer']['address']['staircase'] = $cart->getTemporaryAccount()->getTemporaryPerson()->getAddress()->getStaircase();
                $shipmentData['customer']['address']['floor'] = $cart->getTemporaryAccount()->getTemporaryPerson()->getAddress()->getFloor();
                $shipmentData['customer']['address']['door'] = $cart->getTemporaryAccount()->getTemporaryPerson()->getAddress()->getDoor();
            }
        }

        $currentUserType = App::getContainer()->getUser()->getType();
        $permittedUserType = $cart->getUserAccount() ? self::PERMITTED_USER_TYPE_USER : self::PERMITTED_USER_TYPE_GUEST;
        $shipmentData['cart']['permittedUserType'] = $permittedUserType;
        $shipmentData['cart']['permittedForCurrentUser'] = $permittedUserType == self::PERMITTED_USER_TYPE_BOTH 
            || (($permittedUserType == self::PERMITTED_USER_TYPE_GUEST && $currentUserType == User::TYPE_GUEST) 
                || ($permittedUserType == self::PERMITTED_USER_TYPE_USER && $currentUserType == User::TYPE_USER));

        $sumGrossItemPriceRounded2 = 0;

        $orderedCartItems = [];
        foreach ($cart->getCartItem() as $cartItem) {
            if (!$cartItem->getProduct()->getSpecialPurpose()) {
                $orderedCartItems[] = $cartItem;
            }
        }
        foreach ($cart->getCartItem() as $cartItem) {
            if ($cartItem->getProduct()->getSpecialPurpose()) {
                $orderedCartItems[] = $cartItem;
            }
        }

        foreach ($orderedCartItems as $shipmentItem) {
            $shipmentItemData = $shipmentItemPattern;
            $shipmentItemData['cartItem']['id'] = $shipmentItem->getId();
            $shipmentItemData['cartItem']['product'] = isset($shipmentProductData[$shipmentItem->getId()]) ? $shipmentProductData[$shipmentItem->getId()] : null;
            if (!isset($shipmentItemData['cartItem']['product']['activeProductPrice']['quantity'])) {
                // dump(self::getCartProductData($cart->getId(), true));
                // self::getCartProductData($cart->getId(), true);
                // dump($shipmentItemData);exit;
            }
            $shipmentItemData['cartItem']['quantity'] = $shipmentItemData['cartItem']['product']['activeProductPrice']['quantity'];
            $shipmentData['cart']['cartItems']['productId-'.$shipmentItemData['cartItem']['product']['productId']] = $shipmentItemData;
            // if (!isset($shipmentItemData['cartItem']['product']['activeProductPrice']['currencyCode'])) {
            //     dump($shipmentProductData);
            //     dump($shipmentItemData);
            // }
            $currencyCode = $shipmentItemData['cartItem']['product']['activeProductPrice']['currencyCode'];
            $sumGrossItemPriceRounded2 += $shipmentItemData['cartItem']['product']['activeProductPrice']['grossItemPriceRounded2'];
        }

        $shipmentData['cart']['summary']['sumGrossItemPriceRounded2'] = $sumGrossItemPriceRounded2;
        $shipmentData['cart']['summary']['sumGrossItemPriceFormatted'] = StringHelper::formatNumber($sumGrossItemPriceRounded2, 2, ',', '.');
        $shipmentData['cart']['currencyCode'] = $currencyCode;

        return $shipmentData;
    }
}
