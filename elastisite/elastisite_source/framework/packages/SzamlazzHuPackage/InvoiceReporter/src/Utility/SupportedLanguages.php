<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu\Utility;

class SupportedLanguages
{
    /**
     * @return array<string>
     */
    public static function forInvoice(): array
    {
        return [
            'cz', 'de', 'en', 'es', 'fr', 'hr', 'hu', 'it', 'pl', 'ro', 'sk',
        ];
    }
}
