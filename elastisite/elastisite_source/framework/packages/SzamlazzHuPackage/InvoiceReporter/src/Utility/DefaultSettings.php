<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu\Utility;

class DefaultSettings
{
    /**
     * @var array<string, array<string, bool|string>>
     */
    protected array $fieldData = [
        'user' => [
            'xmlName' => 'felhasznalo',
            'type' => 'string',
            'required' => false,
            'default' => '',
        ],
        'password' => [
            'xmlName' => 'jelszo',
            'type' => 'string',
            'required' => false,
            'default' => '',
        ],
        'api_key' => [
            'xmlName' => 'szamlaagentkulcs',
            'type' => 'string',
            'required' => false,
            'default' => '',
        ],
        'eInvoice' => [
            'xmlName' => 'eszamla',
            'type' => 'bool',
            'required' => true,
            'default' => 'true',
        ],
        'iDownload' => [
            'xmlName' => 'szamlaLetoltes',
            'type' => 'bool',
            'required' => true,
            'default' => 'true',
        ],
        'iDownloadCount' => [
            'xmlName' => 'szamlaLetoltesPld',
            'type' => 'int',
            'required' => false,
            'default' => '',
        ],
        'answerVersion' => [
            'xmlName' => 'valaszVerzio',
            'type' => 'int',
            'required' => false,
            'default' => '2',
        ],
        'aggregator' => [
            'xmlName' => 'aggregator',
            'type' => 'string',
            'required' => false,
            'default' => '',
        ],
        'guardian' => [
            'xmlName' => 'guardian',
            'type' => 'bool',
            'required' => false,
            'default' => 'false',
        ],
        'cikkazoninvoice' => [
            'xmlName' => 'cikkazoninvoice',
            'type' => 'bool',
            'required' => false,
            'default' => 'false',
        ],
        'external_inv_id' => [
            'xmlName' => 'szamlaKulsoAzon',
            'type' => 'string',
            'required' => false,
            'default' => '',
        ],
    ];

    /**
     * @return array<string, array<string, bool|string>>
     */
    public function fields(): array
    {
        return $this->fieldData;
    }
}
