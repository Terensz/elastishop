<?php
namespace framework\component\parent;

// use framework\component\parent\RouteRendering;
use framework\kernel\utility\BasicUtils;
use framework\component\parent\Response;
use framework\component\exception\ElastiException;

class CommandController extends RouteRendering
{
    protected $controllerType = 'command';
}
