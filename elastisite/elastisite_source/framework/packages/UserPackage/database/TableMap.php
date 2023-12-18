<?php
namespace framework\packages\UserPackage\database;

class TableMap
{
    public static function get()
    {
        return array(
            // array(
            //     'tableName' => 'alma',
            //     'collate' => 'utf8_hungarian_ci',
            //     'autoIncrement' => 11000,
            //     'primaryKey' => array('id'),
            //     'columns' => array(
            //         'id' => array(
            //             'type' => 'INT',
            //             'autoIncrement' => true,
            //             'length' => null,
            //             'nullable' => false
            //         ),
            //         'code' => array(
            //             'type' => 'VARCHAR',
            //             'length' => '18',
            //             'nullable' => false
            //         )
            //     )
            // ),
            array(
                'tableName' => 'user_account',
                'collate' => 'utf8_hungarian_ci',
                'autoIncrement' => 11000,
                'primaryKey' => array('id'),
                'columns' => array(
                    'id' => array(
                        'type' => 'INT',
                        'autoIncrement' => true,
                        'length' => null,
                        'nullable' => false
                    ),
                    'code' => array(
                        'type' => 'VARCHAR',
                        'length' => '18',
                        'nullable' => false
                    ),
                    // 'alma' => array(
                    //     'type' => 'VARCHAR',
                    //     'length' => '23',
                    //     'nullable' => false
                    // ),
                    'registered_at' => array(
                        'type' => 'DATETIME',
                        'nullable' => false,
                        'default' => 'now()'
                    ),
                    'status' => array(
                        'type' => 'INT',
                        'length' => '4',
                        'nullable' => false,
                        'default' => '0'
                    )
                )
            ),
            array(
                'tableName' => 'person',
                'collate' => 'utf8_hungarian_ci',
                'autoIncrement' => 4000,
                'columns' => array(
                    'id' => array(
                        'type' => 'INT',
                        'autoIncrement' => true,
                        'length' => null,
                        'nullable' => false
                    ),
                    'user_account_id' => array(
                        'type' => 'INT',
                        'nullable' => true
                    ),
                    'full_name' => array(
                        'type' => 'VARCHAR',
                        'length' => '200',
                        'nullable' => true
                    ),
                    'username' => array(
                        'type' => 'VARCHAR',
                        'length' => '200',
                        'nullable' => true
                    ),
                    'password' => array(
                        'type' => 'VARCHAR',
                        'length' => '200',
                        'nullable' => true
                    ),
                    'email' => array(
                        'type' => 'TEXT',
                        'nullable' => true
                    ),
                    'mobile' => array(
                        'type' => 'VARCHAR',
                        'length' => '100',
                        'nullable' => true
                    )
                )
            ),
            array(
                'tableName' => 'user_account_registration_token',
                'collate' => 'utf8_hungarian_ci',
                'primaryKey' => array('id'),
                'autoIncrement' => 6000,
                'columns' => array(
                    'id' => array(
                        'type' => 'INT',
                        'autoIncrement' => true,
                        'length' => null,
                        'nullable' => false
                    ),
                    'user_account_id' => array(
                        'type' => 'INT',
                        'length' => '11',
                        'nullable' => true
                    ),
                    'token' => array(
                        'type' => 'VARCHAR',
                        'length' => '100',
                        'nullable' => false
                    ),
                    'created_at' => array(
                        'type' => 'DATETIME',
                        'nullable' => true,
                        'default' => 'now()'
                    ),
                    'redeemed_at' => array(
                        'type' => 'DATETIME',
                        'nullable' => true
                    )
                )
            ),
            array(
                'tableName' => 'user_login_token',
                'collate' => 'utf8_hungarian_ci',
                'primaryKey' => array('id'),
                'autoIncrement' => 9000,
                'columns' => array(
                    'id' => array(
                        'type' => 'INT',
                        'autoIncrement' => true,
                        'length' => null,
                        'nullable' => false
                    ),
                    'user_id' => array(
                        'type' => 'INT',
                        'length' => '11',
                        'nullable' => true
                    ),
                    'token' => array(
                        'type' => 'VARCHAR',
                        'length' => '100',
                        'nullable' => false
                    ),
                    'created_at' => array(
                        'type' => 'DATETIME',
                        'nullable' => true,
                        'default' => 'now()'
                    ),
                    'redeemed_at' => array(
                        'type' => 'DATETIME',
                        'nullable' => true
                    )
                )
            ),
            array(
                'tableName' => 'username_reservation',
                'collate' => 'utf8_hungarian_ci',
                'autoIncrement' => 5000,
                'columns' => array(
                    'id' => array(
                        'type' => 'INT',
                        'autoIncrement' => true,
                        'length' => null,
                        'nullable' => false
                    ),
                    'visitor_code' => array(
                        'type' => 'VARCHAR',
                        'length' => '12',
                        'nullable' => true
                    ),
                    'username' => array(
                        'type' => 'VARCHAR',
                        'length' => '250',
                        'nullable' => true
                    )
                )
            ),
            // array(
            //     'tableName' => 'visitor',
            //     'collate' => 'utf8_hungarian_ci',
            //     'autoIncrement' => 3000,
            //     'columns' => array(
            //         'id' => array(
            //             'type' => 'INT',
            //             'autoIncrement' => true,
            //             'length' => null,
            //             'nullable' => false
            //         ),
            //         'user_account_id' => array(
            //             'type' => 'INT',
            //             'length' => '11',
            //             'nullable' => true
            //         ),
            //         'code' => array(
            //             'type' => 'VARCHAR',
            //             'length' => '12',
            //             'nullable' => false
            //         ),
            //         'first_visit' => array(
            //             'type' => 'DATETIME',
            //             'nullable' => true
            //         )
            //     )
            // ),
            array(
                'tableName' => 'accepted_legal_notice',
                'collate' => 'utf8_hungarian_ci',
                'autoIncrement' => 3000,
                'columns' => array(
                    'id' => array(
                        'type' => 'INT',
                        'autoIncrement' => true,
                        'length' => null,
                        'nullable' => false
                    ),
                    'visitor_code' => array(
                        'type' => 'VARCHAR',
                        'length' => '12',
                        'nullable' => true
                    ),
                    'user_account_id' => array(
                        'type' => 'INT',
                        'length' => '11',
                        'nullable' => true
                    ),
                    'created_at' => array(
                        'type' => 'DATETIME',
                        'nullable' => true,
                        'default' => 'now()'
                    )
                )
            ),
            array(
                'tableName' => 'accepted_legal_notice_item',
                'collate' => 'utf8_hungarian_ci',
                'autoIncrement' => 3000,
                'columns' => array(
                    'id' => array(
                        'type' => 'INT',
                        'autoIncrement' => true,
                        'length' => null,
                        'nullable' => false
                    ),
                    'accepted_legal_notice_id' => array(
                        'type' => 'INT',
                        'length' => '11',
                        'nullable' => false
                    ),
                    'category' => array(
                        'type' => 'VARCHAR',
                        'length' => '12',
                        'nullable' => false
                    ),
                    'version' => array(
                        'type' => 'INT',
                        'length' => '11',
                        'nullable' => false
                    )
                )
            ),
            array(
                'tableName' => 'accepted_cookie_notice',
                'collate' => 'utf8_hungarian_ci',
                'autoIncrement' => 3000,
                'columns' => array(
                    'id' => array(
                        'type' => 'INT',
                        'autoIncrement' => true,
                        'length' => null,
                        'nullable' => false
                    ),
                    'visitor_code' => array(
                        'type' => 'VARCHAR',
                        'length' => '12',
                        'nullable' => false
                    ),
                    'user_account_id' => array(
                        'type' => 'INT',
                        'length' => '11',
                        'nullable' => true
                    ),
                    'session_accepted' => array(
                        'type' => 'TINYINT',
                        'nullable' => false,
                        'default' => '1'
                    ),
                    'first_party_accepted' => array(
                        'type' => 'TINYINT',
                        'nullable' => false,
                        'default' => '1'
                    ),
                    'third_party_accepted' => array(
                        'type' => 'TINYINT',
                        'nullable' => false,
                        'default' => '1'
                    ),
                    'created_at' => array(
                        'type' => 'DATETIME',
                        'nullable' => true,
                        'default' => 'now()'
                    )
                )
            )
        );
    }
}
