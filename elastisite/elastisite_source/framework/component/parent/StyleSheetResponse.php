<?php
namespace framework\component\parent;

use framework\component\parent\Response;

class StyleSheetResponse extends Response
{
    public function __construct(string $view)
    {
        header('Content-Type: text/css');

        echo $view; exit;
    }
}
