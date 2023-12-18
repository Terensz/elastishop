<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu;

use Carbon\Carbon;
use PH7\Eu\Vat\Exception as EuVatExcept;
use PH7\Eu\Vat\Provider\Europa;
use PH7\Eu\Vat\Validator;
use Trianity\SzamlazzHu\DataTransferObjects\EuVatData;

/**
 * Validate Valid EU VAT ID
 * I'm Pierre-Henry Soria, a passionate Software Engineer and the creator of pH7CMS.
 * https://github.com/pH-7/eu-vat-validator
 *
 * Testdata: DE 8145276 (invalid) SK2023775105 (valid) ATU75279725 (valid)
 * HU23793956 (valid), BE0472429986 (valid) CZ25977687 (valid)
 */
class EuVatValidator
{
    /**
     * @var array<string>
     */
    protected array $country_codes = [
        'BE', 'BG', 'CZ', 'DK', 'DE', 'EE', 'IE',
        'EL', 'ES', 'FR', 'HR', 'IT', 'CY', 'LV',
        'LT', 'LU', 'HU', 'MT', 'NL', 'AT', 'PL',
        'PT', 'RO', 'SI', 'SK', 'FI', 'SE',
    ];

    protected string $euvat = '';

    protected string $country_code = '';

    protected bool $okForValidating = true;

    protected bool $valid = false;

    protected EuVatData $data;

    /**
     * EuVatValidator constructor.
     *
     * @param  string  $euvat_id - VAT ID to validate
     */
    public function __construct(string $euvat_id)
    {
        $this->country_code = strtoupper(substr(trim($euvat_id), 0, 2));
        $this->euvat = trim(substr($euvat_id, 2));

        $this->okForValidating = ($this->okForValidating &&
            in_array($this->country_code, $this->country_codes));

        $this->validate();
    }

    public function getData(): EuVatData
    {
        return $this->data;
    }

    protected function validate(): void
    {
        if ($this->okForValidating) {
            try {
                $euVatValidator = new Validator(
                    new Europa(),
                    $this->euvat,
                    $this->country_code
                );

                if ($euVatValidator->check()) {
                    //Store validated data
                    $this->successData($euVatValidator);
                } else {
                    $this->errorData(3, __('szamlazzhu::messages.invalid_euvatid'));
                }
            } catch (EuVatExcept) {
                $this->errorData(9, __('szamlazzhu::messages.missing_vies'));
            }
        } else {
            //Still invalid the EU VAT ID
            $this->errorData(6, __('szamlazzhu::messages.wrong_input'));
        }
    }

    protected function errorData(int $response_code, string $message): void
    {
        $this->valid = false;
        $this->data = EuVatData::from([
            'valid' => false,
            'response_code' => $response_code,
            'member_state' => '',
            'address' => '',
            'message' => $message,
        ]);
    }

    protected function successData(Validator $validator): void
    {
        $this->valid = true;
        $this->data = EuVatData::from([
            'valid' => true,
            'response_code' => 1,
            'business_name' => $validator->getName(),
            'address' => $validator->getAddress(),
            'request_date' => Carbon::now('Europe/Budapest')->toDateString(),
            'member_state' => $validator->getCountryCode(),
            'vat_number' => $validator->getVatNumber(),
            'message' => __('szamlazzhu::messages.valid_euvatid'),
        ]);
    }
}
