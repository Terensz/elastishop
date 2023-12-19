<?php
namespace framework\packages\WebshopPackage\dataProvider;

use App;
use framework\component\helper\StringHelper;
use framework\component\parent\Service;

class PackDataProvider extends Service
{
    public static function getRawDataPattern()
    {
        return [
            'customer' => [
                'name' => null,
                'type' => null,
                'note' => null,
                'email' => null,
                'mobile' => null,
                'address' => null,
                'organization' => null
            ],
            'pack' => [
                'id' => null,
                'packItems' => [],
                'code' => null,
                'priority' => null,
                'permittedUserType' => null,
                'permittedForCurrentUser' => null,
                'paymentMethod' => null,
                'createdAt' => null,
                'status' => null,
                'publicStatusText' => null,
                'adminStatusText' => null,
                'payments' => [
                    'active' => null,
                    'successful' => null,
                    'failedForever' => []
                ],
                'currencyCode' => null,
                'confirmationSentAt' => null,
            ],
            'summary' => [
                'sumGrossPriceRounded2' => null,
                'sumGrossPriceFormatted' => null
            ]
        ];
    }

    public static function assembleDataSet($packObject, string $packItemGetter)
    {
        $packData = self::getRawDataPattern();
        App::getContainer()->wireService('WebshopPackage/dataProvider/AddressDataProvider');
        App::getContainer()->wireService('WebshopPackage/dataProvider/OrganizationDataProvider');
        App::getContainer()->wireService('WebshopPackage/dataProvider/PackItemDataProvider');

        if ($packObject->getTemporaryAccount() && $packObject->getTemporaryAccount()->getTemporaryPerson()) {
            $customerName = $packObject->getTemporaryAccount()->getTemporaryPerson()->getName();
            $recipientName = $packObject->getTemporaryAccount()->getTemporaryPerson()->getRecipientName();
            $customerType = $packObject->getTemporaryAccount()->getTemporaryPerson()->getCustomerType();
            $customerNote = $packObject->getTemporaryAccount()->getTemporaryPerson()->getCustomerNote();
            $customerEmail = $packObject->getTemporaryAccount()->getTemporaryPerson()->getEmail();
            $packData['customer']['name'] = $recipientName ? : $customerName;
            $packData['customer']['type'] = $customerType;
            $packData['customer']['note'] = $customerNote;
            $packData['customer']['email'] = $customerEmail;
            $shipmentData['customer']['address'] = AddressDataProvider::assembleDataSet($packObject->getTemporaryAccount()->getTemporaryPerson());
            $shipmentData['customer']['organization'] = OrganizationDataProvider::assembleDataSet($packObject->getTemporaryAccount()->getTemporaryPerson()->getOrganization());
        }
        $packData['pack']['id'] = $packObject->getId();
        // $packData['pack']['packItems'] = PackItemDataProvider::assembleDataSet($packObject->$packItemGetter());
    }
}