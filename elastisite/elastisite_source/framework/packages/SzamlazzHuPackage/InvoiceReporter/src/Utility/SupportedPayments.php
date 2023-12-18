<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu\Utility;

class SupportedPayments
{
    /**
     * @return array<int, string>
     */
    public static function forInvoice(): array
    {
        return [
            'transfer', 'cash', 'bank_card', 'credit_card', 'check', 'c.o.d.',
            'gift_card', 'barter', 'Borgun', 'group', 'EP_card', 'OTP_simple',
            'compensation', 'coupon', 'PayPal', 'PayU', 'SZEP_card',
            'free_of_charge', 'voucher',
        ];
    }
}
