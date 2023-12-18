<?php
namespace framework\kernel\DbSchemaManager;

use framework\component\parent\Service;
use framework\kernel\utility\BasicUtils;
use framework\kernel\utility\FileHandler;

class DbSchemaManager extends Service
{
    public function __construct()
    {
        // $this->wireService('ToolPackage/service/DbSchemaManager/TableSchema');
        // $this->wireService('ToolPackage/service/DbSchemaManager/FieldSchema');
    }
}
