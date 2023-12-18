<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu\Client;

use CurlHandle;
use Illuminate\Support\Facades\Log;

class CURLRequest implements HTTPRequest
{
    protected bool $curlDebug;

    protected CurlHandle|false $handle;

    protected CURLDebug $debug;

    protected CURLDebugInfo $debuginfo;

    protected string $cookies;

    public function __construct(bool $curlDebug = false, string $url = null)
    {
        $this->curlDebug = $curlDebug;
        $this->debug = new CURLDebug($curlDebug);
        $this->handle = curl_init($url);
        $this->debuginfo = new CURLDebugInfo();
        $this->cookies = tempnam('/tmp', 'cookie.txt');
    }

    public function curlOption(int $option, mixed $value): bool
    {
        return curl_setopt($this->handle, $option, $value);
    }

    /**
     * @param  array<int, int|bool|string>  $options
     */
    public function curlOptions(array $options): bool
    {
        return curl_setopt_array($this->handle, $options);
    }

    public function execute(): string|bool
    {
        return curl_exec($this->handle);
    }

    public function getInfo(int $option = null): mixed
    {
        return curl_getinfo($this->handle, $option);
    }

    public function close(): void
    {
        curl_close($this->handle);
    }

    public function errorNumber(): int
    {
        return curl_errno($this->handle);
    }

    public function errorString(): string
    {
        return curl_error($this->handle);
    }

    /**
     * @param  array<int, int|bool|string>  $options
     */
    public function curlResult(array $options): ?string
    {
        if (! $this->handle) {
            Log::error("[trianity/szamlazzhu] Couldn't initialize a CurlHandle");

            return null;
        }
        $this->defaultOptions();
        foreach ($options as $option => $value) {
            $this->curlOption($option, $value);
        }

        $this->debug->debugSettings($this);
        $result = $this->execute();
        $this->debug->debugOutput();

        if ($this->debuginfo->emptyResult($result, $this) || $this->debuginfo->noHttpCode($this)) {
            return null;
        }

        return $result;
    }

    protected function defaultOptions(): void
    {
        $this->curlOptions(
            [
                CURLOPT_POST => true,
                CURLOPT_HTTPHEADER => [],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_COOKIEJAR => $this->cookies,
                CURLOPT_COOKIEFILE => $this->cookies,
            ]
        );
    }
}
