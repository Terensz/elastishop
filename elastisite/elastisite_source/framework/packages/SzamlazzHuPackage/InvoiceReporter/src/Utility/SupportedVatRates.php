<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu\Utility;

class SupportedVatRates
{
    /**
     * Supported Vat Rates:
     *
     * TEHK : Outside the scope of Hungarian VAT
     * TAHK : Not subject to VAT
     * TAM  : supply exempt from VAT / exempt supply
     * AAM  : person exempt from VAT / exempt person
     * EUT  : Within EU (former 'EU')
     * EUKT : Outside EU (former 'EUK')
     * MAA  : exempt from tax
     * F.AFA : reverse VAT
     * K.AFA : differential VAT
     * HO : Harmadik országban teljesített ügylet (TEHK) - Translation WIP
     * EUE : Másik tagállamban teljesített, nem fordítottan adózó ügylet - Translation WIP
     * EUFADE : Másik tagállamban teljesített, nem az Áfa tv. 37. §-a alá tartozó, fordítottan adózó ügylet - Translation WIP
     * EUFAD37 : -Áfa tv. 37. §-a alapján másik tagállamban teljesített, fordítottan adózó ügylet - Translation WIP
     * ATK : ÁFA tárgyi hatályán kívüli - Translation WIP
     * NAM : adómentesség egyéb nemzetközi ügyletekhez - Translation WIP
     * EAM : adómentes termékexport harmadik országba - Translation WIP
     * KBAUK : Közösségen belüli termékértékesítés UK - Translation WIP
     * KBAET : Közösségen belüli termékértékesítés ET - Translation WIP
     * 0, 5, 7, 18, 19, 20, 25, 27 | exact VAT rate
     *
     * @return array<int, string>
     */
    public static function forInvoice(): array
    {
        return [
            'TEHK', 'TAHK', 'TAM', 'AAM', 'EUT', 'EUKT',
            'MAA', 'F.AFA', 'K.AFA', 'HO', 'EUE', 'EUFADE',
            'EUFAD37', 'ATK', 'NAM', 'EAM', 'KBAUK',
            'KBAET', '0', '5', '7', '18', '19', '20', '25', '27',
        ];
    }
}
