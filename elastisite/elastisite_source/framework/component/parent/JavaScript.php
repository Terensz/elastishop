<?php
namespace framework\component\parent;

use framework\component\parent\Response;

class JavaScript extends Response
{
    public function __construct($javaScript)
    {
        header('Content-Type: application/javascript');
        echo $javaScript;
    }
}
