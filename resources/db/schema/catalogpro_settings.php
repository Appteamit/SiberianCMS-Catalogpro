<?php

$schemas = (!isset($schemas)) ? [] : $schemas;
$schemas['catalogpro_settings'] = [
    'id' => [
        'type' => 'int(11) unsigned',
        'auto_increment' => true,
        'primary' => true,
    ],
    'value_id' => [
        'type' => 'int(11) unsigned',
        'foreign_key' => [
            'table' => 'application_option_value',
            'column' => 'value_id',
            'name' => 'FK_CATALOGPRO_SETTINGS_VID_AOV_VID',
            'on_update' => 'CASCADE',
            'on_delete' => 'CASCADE',
        ],
        'index' => [
            'key_name' => 'value_id',
            'index_type' => 'BTREE',
            'is_null' => false,
            'is_unique' => false,
        ]
    ],     
    'admin_email' => [
        'type' => 'varchar(120)',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'is_null' => true
    ],    
    'product_design' => [
        'type' => 'varchar(120)',
        'collation' => 'utf8_unicode_ci',
        'charset' => 'utf8',
        'is_null' => true,
        'default' => 'list'
    ],    
    'category_design' => [
        'type' => 'varchar(120)',
        'collation' => 'utf8_unicode_ci',
        'charset' => 'utf8',
        'is_null' => true,
        'default' => 'list'
    ],    
    'home_screen' => [
        'type' => 'varchar(120)',
        'collation' => 'utf8_unicode_ci',
        'charset' => 'utf8',
        'is_null' => true,
        'default' => 'category'
    ],
    'enable_comments' => [
        'type' => 'tinyint(1)',
        'default' => '1'
    ],
    'enable_report' => [
        'type' => 'tinyint(1)',
        'default' => '1'
    ],
    'enable_voting' => [
        'type' => 'tinyint(1)',
        'default' => '1'
    ],
    'enable_favorites' => [
        'type' => 'tinyint(1)',
        'default' => '1'
    ],
    'created_at' => [
        'type' => 'datetime',
    ],
    'updated_at' => [
        'type' => 'datetime',
    ]
];