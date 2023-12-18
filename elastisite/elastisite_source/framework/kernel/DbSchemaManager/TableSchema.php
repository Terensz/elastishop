<?php
namespace framework\kernel\DbSchemaManager;

use framework\component\parent\Service;
use framework\kernel\utility\BasicUtils;
use framework\kernel\utility\FileHandler;
use framework\packages\ToolPackage\service\DbSchemaManager\FieldSchema;

class TableSchema extends Service
{
    private $fields;

    public function addField($fieldName)
    {
        $this->fields[] = array(
            'fieldName' => $fieldName,
            'fieldSchema' => new FieldSchema()
        );
    }
}
