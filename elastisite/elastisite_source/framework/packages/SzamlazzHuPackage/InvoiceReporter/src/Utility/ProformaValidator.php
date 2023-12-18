<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu\Utility;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Trianity\SzamlazzHu\Exceptions\InvalidBuyerData;
use Trianity\SzamlazzHu\Exceptions\InvalidInvoiceHeader;
use Trianity\SzamlazzHu\Exceptions\InvalidInvoiceSettings;
use Trianity\SzamlazzHu\Exceptions\InvalidItemsData;
use Trianity\SzamlazzHu\Exceptions\InvalidSellerData;
use Trianity\SzamlazzHu\Proforma;

class ProformaValidator
{
    public function validateSettings(Proforma $proforma): bool
    {
        $test = config('szamlazzhu.test', false);
        $config = config('szamlazzhu.client.credentials.live');
        if ($test) {
            $config = config('szamlazzhu.client.credentials.test');
        }
        $proforma->writeSettings([...$config, ...$proforma->readSettings()]);
        $validator = Validator::make($proforma->readSettings(), Rules::invoiceSettings());
        if ($validator->fails()) {
            $failedRules = $validator->failed();
            Log::error('Failed Settings Rules: '.print_r($failedRules, true));
            throw new InvalidInvoiceSettings($validator);
        }

        return true;
    }

    public function validateHeader(Proforma $proforma): bool
    {
        $header = config('szamlazzhu.header');
        $proforma->writeHeader([...$header, ...$proforma->readHeader()]);
        $validator = Validator::make($proforma->readHeader(), Rules::headerSettings());
        if ($validator->fails()) {
            $failedRules = $validator->failed();
            Log::error('Failed Header Rules: '.print_r($failedRules, true));
            throw new InvalidInvoiceHeader($validator);
        }

        return true;
    }

    public function validateSeller(Proforma $proforma): bool
    {
        $seller = config('szamlazzhu.seller');
        $proforma->writeSeller([...$seller, ...$proforma->readSeller()]);
        $validator = Validator::make($proforma->readSeller(), Rules::sellerSettings());
        if ($validator->fails()) {
            $failedRules = $validator->failed();
            Log::error('Failed Seller Rules: '.print_r($failedRules, true));
            throw new InvalidSellerData($validator);
        }

        return true;
    }

    public function validateBuyer(Proforma $proforma): bool
    {
        $validator = Validator::make($proforma->readBuyer(), Rules::buyerSettings());
        if ($validator->fails()) {
            $failedRules = $validator->failed();
            Log::error('Failed Buyer Rules: '.print_r($failedRules, true));
            throw new InvalidBuyerData($validator);
        }

        return true;
    }

    public function validateItems(Proforma $proforma): bool
    {
        Prices::correctItemPrices($proforma);

        $validator = Validator::make($proforma->readItems(), Rules::itemsSettings());
        if ($validator->fails()) {
            $failedRules = $validator->failed();
            Log::error('Failed Items Rules: '.print_r($failedRules, true));
            throw new InvalidItemsData($validator);
        }

        return true;
    }
}
