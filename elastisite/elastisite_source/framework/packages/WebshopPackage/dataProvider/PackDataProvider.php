<?php
namespace framework\packages\dataProvider\service;

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
                'mobile' => null
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
}