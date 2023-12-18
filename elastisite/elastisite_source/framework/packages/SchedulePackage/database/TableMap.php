<?php
namespace framework\packages\SchedulePackage\database;

class TableMap
{
    public static function get()
    {
        return array(
            array(
                'tableName' => 'event',
                'collate' => 'utf8_hungarian_ci',
                'autoIncrement' => 3000,
                'primaryKey' => array('id'),
                'columns' => array(
                    'id' => array(
                        'type' => 'INT',
                        'autoIncrement' => true,
                        'length' => null,
                        'nullable' => false
                    ),
                    'title' => array(
                        'type' => 'VARCHAR',
                        'length' => '128',
                        'nullable' => false
                    ),
                    'description' => array(
                        'type' => 'TEXT',
                        'nullable' => true,
                        // 'default' => 'null'
                    ),
                    'start_date' => array(
                        'type' => 'DATETIME',
                        'nullable' => true,
                        'default' => 'null'
                    ),
                    'end_date' => array(
                        'type' => 'DATETIME',
                        'nullable' => true,
                        'default' => 'null'
                    ),
                    'max_subscribers' => array(
                        'type' => 'INT',
                        'nullable' => true,
                        'default' => 'null'
                    ),
                    'created_at' => array(
                        'type' => 'DATETIME',
                        'nullable' => true,
                        'default' => 'null'
                    ),
                    'owner' => array(
                        'type' => 'INT',
                        'nullable' => true,
                        'default' => 'null'
                    ),
                    'active' => array(
                        'type' => 'BOOLEAN',
                        'nullable' => false,
                        'default' => '1'
                    )
                )
            )
        );
    }
}
