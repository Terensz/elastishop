<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu\Exceptions;

use Exception;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Should be triggered when the Invoice/Proforma Seller data filled is invalid
 */
class InvalidSellerData extends Exception
{
    protected Validator $validator;

    public function __construct(Validator $validator, string $message = '', int $code = 0, Throwable $previous = null)
    {
        Log::debug("[Szamlazz.hu] - Invalid Seller Data Exception: {$message} Code:  {$code}");
        parent::__construct($message, $code, $previous);
        $this->validator = $validator;
    }

    public function getValidator(): Validator
    {
        return $this->validator;
    }
}
