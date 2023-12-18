<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu\Client;

use Illuminate\Support\Facades\Log;

class CURLDebug
{
    protected bool $curlDebug = false;

    /**
     * @var resource|false for php output stream
     */
    protected $out;

    public function __construct(bool $curlDebug = false)
    {
        if ($curlDebug) {
            $this->curlDebug = true;
            ob_start();
            $this->out = fopen('php://output', 'w');
        }
    }

    public function debugOutput(): void
    {
        if ($this->curlDebug) {
            fclose($this->out);
            $debug = ob_get_clean();
            Log::debug("[trianity/szamlazzhu] cURL Verbose Debug: {$debug}");
        }
    }

    public function debugSettings(CURLRequest $request): void
    {
        if ($this->curlDebug) {
            $request->curlOption(CURLOPT_VERBOSE, true);
            $request->curlOption(CURLOPT_STDERR, $this->out);
        }
    }
}
