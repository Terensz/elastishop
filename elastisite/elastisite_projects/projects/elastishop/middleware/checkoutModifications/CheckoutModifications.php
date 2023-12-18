<?php
namespace projects\elastishop\middleware\checkoutModifications;

// use framework\kernel\component\Kernel;

use App;
use framework\kernel\utility\FileHandler;
use framework\packages\BasicPackage\entity\Country;
use framework\packages\UserPackage\entity\Address;

class CheckoutModifications
{
    const AFFECTED_WEBSITES = ['Honey4u','Meheszellato'];

    const ADDRESS_DATA = [
        'choice1' => [
            'availableOnWebsites' => ['Honey4u','Meheszellato'],
            'street' => 'OcsaiStreetWarehouse',
            'fullAddress' => '1239 Budapest, Ócsai út, Transzformátor állomás buszmegálló'
        ],
        'choice2' => [
            'availableOnWebsites' => ['Honey4u'],
            'street' => 'VaciStreetPickUpPoint',
            'fullAddress' => '1138. Budapest, Váci út 159/A. 1. LH. FSZ. 4.'
        ]
    ];

    public function get($tempAcc, $validateForm)
    {
        $website = App::getWebsite();
        // dump($website);
        if (!in_array($website, self::AFFECTED_WEBSITES)) {
            return null;
        }

        $selectedPickUpPoint = App::getContainer()->getRequest()->get('WebshopPackage_checkout_pickUpPoint');
        $message = !empty($selectedPickUpPoint) ? '' : trans('missing.pick.up.point');

        $address = $tempAcc->getTemporaryPerson()->getAddress();
        $pickUpPoint = App::getContainer()->getRequest()->get('WebshopPackage_checkout_pickUpPoint');
        if ($pickUpPoint) {
            App::getContainer()->wireService('BasicPackage/entity/Country');
            App::getContainer()->wireService('UserPackage/entity/Address');

            if (!$address) {
                $address = new Address();
            }
            $country = new Country(348);
            // dump($country);
            $address->setCountry($country);
            // $address->setZipCode(self::ADDRESS_DATA[$pickUpPoint]['zipCode']);
            // $address->setCity(self::ADDRESS_DATA[$pickUpPoint]['city']);
            $address->setStreet(self::ADDRESS_DATA[$pickUpPoint]['street']);
            // $address->setStreetSuffix(self::ADDRESS_DATA[$pickUpPoint]['streetSuffix']);
            // $address->setHouseNumber(self::ADDRESS_DATA[$pickUpPoint]['houseNumber']);
            // $address->setStaircase(self::ADDRESS_DATA[$pickUpPoint]['staircase']);
            // $address->setFloor(self::ADDRESS_DATA[$pickUpPoint]['floor']);
            // $address->setDoor(self::ADDRESS_DATA[$pickUpPoint]['door']);
            $address = $address->getRepository()->store($address);

            $tempAcc->getTemporaryPerson()->setAddress($address);
            $tempAcc->getTemporaryPerson()->getRepository()->store($tempAcc->getTemporaryPerson());
            // dump($tempAcc->getTemporaryPerson());
            // dump($address);exit;
        }

        $viewPath = 'projects/Meheszellato/middleware/checkoutModifications/view/pickUpPoints.php';
        $viewPath = FileHandler::completePath($viewPath, 'projects');
        return [
            'address' => $address,
            'fillingAddressIsRequired' => false,
            'corporateShipmentEnabled' => false,
            'pickUpPointsView' => App::renderView($viewPath, [
                'thisWebsite' => App::getWebsite(),
                'addressData' => self::ADDRESS_DATA,
                'message' => $message, 
                'validateForm' => $validateForm,
                'selectedPickUpPoint' => $selectedPickUpPoint
            ])
        ];
    }
}
