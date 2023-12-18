<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu\DataTransferObjects;

use Spatie\LaravelData\Data;

/**
 * Invoice XML Query api answer data object
 */
class InvoiceXmlData extends Data
{
    public function __construct(
        public readonly bool $sikeres,
        public readonly ?string $szamlaszam,
        public readonly ?string $tipus,
        public readonly ?string $hivdijbekszam,
        public readonly ?string $rendelesszam,
        public readonly ?string $email,
        public readonly ?array $totalossz,
        public readonly ?array $kifizetes,
        public readonly ?string $pdf_name,
        public readonly ?string $hibakod,
        public readonly ?string $hibauzenet
    ) {
    }
}
