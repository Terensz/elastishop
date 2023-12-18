<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu\DataTransferObjects;

use Spatie\LaravelData\Data;

/**
 * EU VAT Validator answer data object
 */
class EuVatData extends Data
{
    public function __construct(
        public readonly bool $valid,
        public readonly int $response_code,
        public readonly ?string $business_name,
        public readonly string $address,
        public readonly ?string $request_date,
        public readonly string $member_state,
        public readonly ?string $vat_number,
        public readonly string $message
    ) {
    }
}
