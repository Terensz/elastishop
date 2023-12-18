<?php
namespace framework\component\parent;

use framework\component\exception\ElastiException;

use framework\component\parent\Response;

class JsonResponse extends Response
{
    public function __construct(array $array)
    {
        return self::renderJson($array);
    }

    public static function renderJson(array $array)
    {
        if (!function_exists('json_encode')) {
            throw new ElastiException('Php-json not installed.', ElastiException::ERROR_TYPE_SECRET_PROG);
        }

        header('Content-Type: application/json');
        echo \json_encode($array, true);
        exit;
    }
}
