<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu\Client;

interface HTTPRequest
{
    public function curlOption(int $option, mixed $value): bool;

    /**
     * @param  array<int, int|bool|string>  $options
     */
    public function curlOptions(array $options): bool;

    public function execute(): string|bool;

    public function getInfo(int $option = null): mixed;

    public function close(): void;

    public function errorNumber(): int;

    public function errorString(): string;

    /**
     * @param  array<int, int|bool|string>  $options
     */
    public function curlResult(array $options): ?string;
}
