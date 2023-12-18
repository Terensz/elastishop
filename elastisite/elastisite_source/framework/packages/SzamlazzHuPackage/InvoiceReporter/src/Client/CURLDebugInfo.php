<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu\Client;

use Illuminate\Support\Facades\Log;

class CURLDebugInfo
{
    public function noHttpCode(CURLRequest $request): bool
    {
        $info = $request->getInfo();
        $request->close();
        if (empty($info['http_code'])) {
            Log::error('[trianity/szamlazzhu] cURL ERROR: No HTTP code was returned.');

            return true;
        }
        Log::debug("[trianity/szamlazzhu] cURL call, the server responded: {$info['http_code']}");

        return false;
    }

    public function emptyResult(bool|string $result, CURLRequest $request): bool
    {
        if (empty($result)) {
            Log::error('[trianity/szamlazzhu] cURL ERROR: '.$request->errorString());
            $request->close();

            return true;
        }

        return false;
    }
}
