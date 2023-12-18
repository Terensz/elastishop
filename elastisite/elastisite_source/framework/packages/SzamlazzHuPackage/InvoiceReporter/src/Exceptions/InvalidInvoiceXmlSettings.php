<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu\Exceptions;

use Exception;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Should be triggered when the Query Invoice XML Settings configuration is invalid
 */
class InvalidInvoiceXmlSettings extends Exception
{
    protected Validator $validator;

    public function __construct(Validator $validator, string $message = '', int $code = 0, Throwable $previous = null)
    {
        Log::debug("[Szamlazz.hu] - Invalid Query Invoice XML Settings Configuration Exception: {$message} Code:  {$code}");
        parent::__construct($message, $code, $previous);
        $this->validator = $validator;
    }

    public function getValidator(): Validator
    {
        return $this->validator;
    }
}
