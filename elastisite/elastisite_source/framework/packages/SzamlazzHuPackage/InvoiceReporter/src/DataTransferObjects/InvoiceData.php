<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu\DataTransferObjects;

use Spatie\LaravelData\Data;

/**
 * Proforma/Invoice api answer data object
 */
class InvoiceData extends Data
{
    public function __construct(
        public readonly bool $sikeres,
        public readonly ?string $szamlaszam,
        public readonly ?float $szamlanetto,
        public readonly ?float $szamlabrutto,
        public readonly ?float $kintlevoseg,
        public readonly ?string $vevoifiokurl,
        public readonly ?string $pdf_name,
        public readonly ?string $hibakod,
        public readonly ?string $hibauzenet
    ) {
    }
}
