<?php
namespace framework\packages\WebshopPackage\service;

use App;
use framework\component\helper\StringHelper;
use framework\component\parent\Service;
use framework\packages\UserPackage\entity\Address;
use framework\packages\UserPackage\entity\TemporaryAccount;
use framework\packages\UserPackage\entity\TemporaryPerson;
use framework\packages\WebshopPackage\entity\Cart;
use framework\packages\WebshopPackage\entity\Shipment;
use framework\packages\WebshopPackage\entity\ShipmentItem;

class WebshopTemporaryAccountService extends Service
{
    const ERROR_MESSAGE_MISSING_ADDRESS = 'missing.address';
    const ERROR_MESSAGE_MISSING_ORGANIZATION = 'missing.organization';
    const ERROR_MESSAGE_MISSING_ORGANIZATION_ADDRESS = 'missing.organization.address';
    const ERROR_MESSAGE_MISSING_DISPLAYED_NAME = 'missing.displayed.name';
    const ERROR_MESSAGE_MISSING_CONTACT_EMAIL = 'missing.contact.email';
    const ERROR_MESSAGE_MISSING_CONTACT_MOBILE = 'missing.contact.mobile';

    public static $cachedTemporaryAccountData;

    public static function getTemporaryAccount()
    {
        App::getContainer()->wireService('WebshopPackage/service/WebshopCartService');
        $cart = WebshopCartService::getCart();
        if (!$cart) {
            return null;
        }

        return $cart->getTemporaryAccount();
    }

    public static function setTemporaryPersonData($propertyName, $value)
    {
        App::getContainer()->wireService('WebshopPackage/service/WebshopCartService');
        $cart = WebshopCartService::getCart();
        if (!$cart) {
            return null;
        }
        $temporaryPerson = $cart->getTemporaryAccount()->getTemporaryPerson();
        $setter = 'set'.ucfirst($propertyName);
        $temporaryPerson->$setter($value);
        $temporaryPerson = $temporaryPerson->getRepository()->store($temporaryPerson);
    }

    public static function getTemporaryPersonData($propertyName)
    {
        App::getContainer()->wireService('WebshopPackage/service/WebshopCartService');
        $cart = WebshopCartService::getCart();
        $temporaryPerson = $cart->getTemporaryAccount()->getTemporaryPerson();
        $getter = 'get'.ucfirst($propertyName);

        return $temporaryPerson->$getter();
    }

    public static function setAddress(Address $address)
    {
        App::getContainer()->wireService('UserPackage/entity/Address');
        App::getContainer()->wireService('WebshopPackage/service/WebshopCartService');
        $cart = WebshopCartService::getCart();
        $temporaryPerson = $cart->getTemporaryAccount()->getTemporaryPerson();
        $temporaryPerson->setAddress($address);
        $temporaryPerson->getRepository()->store($temporaryPerson);
    }

    public static function createBlankTemporaryAccount(Address $address = null)
    {
        App::getContainer()->wireService('UserPackage/entity/TemporaryAccount');
        // $tempPerson->setCreatedAt($this->getCurrentTimestamp());
        $temporaryAccount = new TemporaryAccount();
        $temporaryAccount->setVisitorCode(App::getContainer()->getSession()->get('visitorCode'));
        $temporaryAccount->setCreatedAt(App::getContainer()->getCurrentTimestamp());
        $temporaryAccount = $temporaryAccount->getRepository()->store($temporaryAccount);
        $temporaryPerson = self::createBlankTemporaryPerson($temporaryAccount, $address);
        $temporaryAccount->setTemporaryPerson($temporaryPerson);
        $temporaryAccount = $temporaryAccount->getRepository()->store($temporaryAccount);

        return $temporaryAccount;
    }

    public static function createBlankTemporaryPerson(TemporaryAccount $temporaryAccount, Address $address = null) : TemporaryPerson
    {
        App::getContainer()->wireService('UserPackage/entity/TemporaryPerson');
        $temporaryPerson = new TemporaryPerson();
        $temporaryPerson->setTemporaryAccount($temporaryAccount);
        $temporaryPerson->setAddress($address);
        $temporaryPerson = $temporaryPerson->getRepository()->store($temporaryPerson);

        return $temporaryPerson;
    }

    // public static function removeTemporaryAccount()
    // {
    //     App::getContainer()->setService('UserPackage/repository/TemporaryAccountRepository');
    //     App::getContainer()->wireService('UserPackage/entity/TemporaryAccount');

    //     $tempAccRepo = App::getContainer()->getService('TemporaryAccountRepository');
    //     $temporaryAccount = $tempAccRepo->findOneBy(['conditions' => [
    //         ['key' => 'visitor_code', 'value' => App::getContainer()->getSession()->get('visitorCode')],
    //         ['key' => 'status', 'value' => TemporaryAccount::STATUS_OPEN]
    //     ]]);
    //     if ($temporaryAccount) {
    //         $tempAccRepo->remove($temporaryAccount->getId());
    //     }
    // }

    public static function assembleTemporaryAccountData(TemporaryAccount $temporaryAccount = null)
    {
        if ($temporaryAccount && !$temporaryAccount->getId()) {
            throw new \Exception('Temporary account must be saved to get extracted data from');
        }
        $key = $temporaryAccount ? $temporaryAccount->getId() : 'null';
        if (isset(self::$cachedTemporaryAccountData[$key])) {
            return self::$cachedTemporaryAccountData[$key];
        }
        App::getContainer()->wireService('UserPackage/entity/TemporaryAccount');

        $data = [
            'id' => null,
            'visitorCode' => null,
            'temporaryPerson' => [
                'id' => null,
                'address' => [
                    'id' => null,
                    'country' => [
                        'alpha2' => null,
                        'name' => null
                    ],
                    'string' => null
                ],
                'customerType' => null,
                'organization' => [
                    'id' => null,
                    'address' => [
                        'country' => [
                            'alpha2' => null,
                            'name' => null
                        ],
                        'string' => null
                    ]
                ],
                'displayedName' => null,
                'name' => null,
                'recipientName' => null,
                'email' => null,
                'mobile' => null,
                'customerNote' => null,
                'validity' => [
                    'isValid' => true,
                    'errorMessages' => [],
                ]
            ],
        ];

        // temporaryAccount id
        if ($temporaryAccount) {
            $data['id'] = $temporaryAccount->getId();
        }

        // temporaryAccount visitorCode
        if ($temporaryAccount) {
            $data['visitorCode'] = $temporaryAccount->getVisitorCode();
        }

        if ($temporaryAccount && $temporaryAccount->getTemporaryPerson()) {
            // id 
            $data['temporaryPerson']['id'] = $temporaryAccount->getTemporaryPerson()->getId();

            // address
            if ($temporaryAccount->getTemporaryPerson()->getAddress()) {
                $countryData = [
                    'alpha2' => null,
                    'name' => null
                ];
                if ($temporaryAccount->getTemporaryPerson()->getAddress()->getCountry()) {
                    $countryData = [
                        'alpha2' => $temporaryAccount->getTemporaryPerson()->getAddress()->getCountry()->getAlphaTwo(),
                        'name' => trans($temporaryAccount->getTemporaryPerson()->getAddress()->getCountry()->getTranslationReference()),
                    ];
                }
                $data['temporaryPerson']['address'] = [
                    'id' => $temporaryAccount->getTemporaryPerson()->getAddress()->getId(),
                    'country' => $countryData,
                    'string' => (string)$temporaryAccount->getTemporaryPerson()->getAddress()
                ];
            }

            // customerType
            if ($temporaryAccount->getTemporaryPerson()->getCustomerType()) {
                $data['temporaryPerson']['customerType'] = $temporaryAccount->getTemporaryPerson()->getCustomerType();
            }

            // organization 
            if ($temporaryAccount->getTemporaryPerson()->getCustomerType() == TemporaryPerson::CUSTOMER_TYPE_ORGANIZATION) {
                if ($temporaryAccount->getTemporaryPerson()->getOrganization()) {
                    $organization = $temporaryAccount->getTemporaryPerson()->getOrganization();
                    $data['temporaryPerson']['organization']['id'] = $organization->getId();
                    $organizationCountryData = null;
                    if ($organization->getAddress()) {
                        $organizationCountryData = [
                            'alpha2' => null,
                            'name' => null
                        ];
                        if ($organization->getAddress()->getCountry()) {
                            $organizationCountryData = [
                                'alpha2' => $organization->getAddress()->getCountry()->getAlphaTwo(),
                                'name' => trans($organization->getAddress()->getCountry()->getTranslationReference()),
                            ];
                        }
                        $organizationAddressData = [
                            'country' => $organizationCountryData,
                            'string' => (string)$organization->getAddress()
                        ];
                        $data['temporaryPerson']['organization']['address'] = $organizationAddressData;
                    } else {
                        $data['validity']['isValid'] = false;
                        $data['validity']['errorMessages'][] = self::ERROR_MESSAGE_MISSING_ORGANIZATION_ADDRESS;
                    }
                    $data['temporaryPerson']['organization']['address'] = $temporaryAccount->getTemporaryPerson()->getOrganization()->getId();
                } else {
                    $data['validity']['isValid'] = false;
                    $data['validity']['errorMessages'][] = self::ERROR_MESSAGE_MISSING_ORGANIZATION;
                }
            }

            // termsAndConditionsAccepted
            $data['temporaryPerson']['termsAndConditionsAccepted'] = $temporaryAccount->getTemporaryPerson()->getTermsAndConditionsAccepted();

            // name
            $data['temporaryPerson']['name'] = $temporaryAccount->getTemporaryPerson()->getName();

            // recipientName
            $data['temporaryPerson']['recipientName'] = $temporaryAccount->getTemporaryPerson()->getRecipientName();

            // displayedName
            $data['temporaryPerson']['displayedName'] = $data['temporaryPerson']['recipientName'] ? : $data['temporaryPerson']['name'];
            if (!$data['temporaryPerson']['displayedName']) {
                $data['validity']['isValid'] = false;
                $data['validity']['errorMessages'][] = self::ERROR_MESSAGE_MISSING_DISPLAYED_NAME;
            }

            // email 
            $data['temporaryPerson']['email'] = $temporaryAccount->getTemporaryPerson()->getEmail();
            if (!$data['temporaryPerson']['email']) {
                $data['validity']['isValid'] = false;
                $data['validity']['errorMessages'][] = self::ERROR_MESSAGE_MISSING_CONTACT_EMAIL;
            }

            // mobile 
            $data['temporaryPerson']['mobile'] = $temporaryAccount->getTemporaryPerson()->getMobile();
            if (!$data['temporaryPerson']['mobile']) {
                $data['validity']['isValid'] = false;
                $data['validity']['errorMessages'][] = self::ERROR_MESSAGE_MISSING_CONTACT_MOBILE;
            }

            // customerNote
            $data['temporaryPerson']['customerNote'] = $temporaryAccount->getTemporaryPerson()->getCustomerNote();
        }

        self::$cachedTemporaryAccountData[$key] = $data;

        return $data;
    }
}