<?php
namespace framework\packages\WebshopPackage\database;

class TableMap
{
    public static function get()
    {
        return array(
            array(
                'tableName' => 'product_category',
                'collate' => 'utf8_hungarian_ci',
                'autoIncrement' => 4000,
                'columns' => array(
                    'id' => array(
                        'type' => 'INT',
                        'autoIncrement' => true,
                        'nullable' => false
                    ),
                    'name' => array(
                        'type' => 'VARCHAR',
                        'length' => '100',
                        'nullable' => false
                    ),
                    'code' => array(
                        'type' => 'VARCHAR',
                        'length' => '100',
                        'nullable' => true
                    ),
                    'website' => array(
                        'type' => 'VARCHAR',
                        'length' => '100',
                        'nullable' => false
                    ),
                    'product_category_id' => array(
                        'type' => 'INT',
                        'nullable' => true
                    ),
                    'created_at' => array(
                        'type' => 'DATETIME',
                        'nullable' => true,
                        'default' => 'now()'
                    ),
                    'status' => array(
                        'type' => 'INT',
                        'length' => '4',
                        'nullable' => false,
                        'default' => '1'
                    )
                )
            ),
            array(
                'tableName' => 'product',
                'collate' => 'utf8_hungarian_ci',
                'autoIncrement' => 12000,
                'columns' => array(
                    'id' => array(
                        'type' => 'INT',
                        'autoIncrement' => true,
                        'length' => null,
                        'nullable' => false
                    ),
                    'name' => array(
                        'type' => 'VARCHAR',
                        'length' => '100',
                        'nullable' => false
                    ),
                    'code' => array(
                        'type' => 'VARCHAR',
                        'length' => '100',
                        'nullable' => true
                    ),
                    'product_category_id' => array(
                        'type' => 'INT',
                        'nullable' => true
                    ),
                    'created_at' => array(
                        'type' => 'DATETIME',
                        'nullable' => true,
                        'default' => 'now()'
                    ),
                    'status' => array(
                        'type' => 'INT',
                        'length' => '4',
                        'nullable' => false,
                        'default' => '1'
                    )
                )
            ),
            array(
                'tableName' => 'storage',
                'collate' => 'utf8_hungarian_ci',
                'autoIncrement' => 12000,
                'columns' => array(
                    'id' => array(
                        'type' => 'INT',
                        'autoIncrement' => true,
                        'length' => null,
                        'nullable' => false
                    ),
                    'name' => array(
                        'type' => 'VARCHAR',
                        'length' => '200',
                        'nullable' => false
                    ),
                    'code' => array(
                        'type' => 'VARCHAR',
                        'length' => '100',
                        'nullable' => false
                    ),
                    'created_at' => array(
                        'type' => 'DATETIME',
                        'nullable' => true,
                        'default' => 'now()'
                    ),
                    'status' => array(
                        'type' => 'INT',
                        'length' => '4',
                        'nullable' => false,
                        'default' => '1'
                    )
                )
            ),
            array(
                'tableName' => 'stock',
                'collate' => 'utf8_hungarian_ci',
                'autoIncrement' => 12000,
                'columns' => array(
                    'id' => array(
                        'type' => 'INT',
                        'autoIncrement' => true,
                        'length' => null,
                        'nullable' => false
                    ),
                    'name' => array(
                        'type' => 'VARCHAR',
                        'length' => '200',
                        'nullable' => false
                    ),
                    'code' => array(
                        'type' => 'VARCHAR',
                        'length' => '100',
                        'nullable' => false
                    ),
                    'created_at' => array(
                        'type' => 'DATETIME',
                        'nullable' => true,
                        'default' => 'now()'
                    ),
                    'status' => array(
                        'type' => 'INT',
                        'length' => '4',
                        'nullable' => false,
                        'default' => '1'
                    )
                )
            )
        );
    }
}
