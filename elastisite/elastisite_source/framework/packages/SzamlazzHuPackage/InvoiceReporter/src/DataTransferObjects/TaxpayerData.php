<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu\DataTransferObjects;

use Spatie\LaravelData\Data;

/**
 * Taxpayer Query api answer data object
 */
class TaxpayerData extends Data
{
    public function __construct(
        public readonly bool $validity,
        public readonly ?string $info_date,
        public readonly int $response_code,
        public readonly string $message,
        public readonly ?string $long_name,
        public readonly ?string $name,
        public readonly ?string $taxpayer_id,
        public readonly ?string $vat_code,
        public readonly ?string $county_code,
        public readonly ?string $org_type,
        public readonly ?string $address_type,
        public readonly ?string $country_code,
        public readonly ?string $postal_code,
        public readonly ?string $city,
        public readonly ?string $street_name,
        public readonly ?string $public_place_category,
        public readonly ?string $number,
        public readonly ?string $building,
        public readonly ?string $floor,
        public readonly ?string $door
    ) {
    }
}
