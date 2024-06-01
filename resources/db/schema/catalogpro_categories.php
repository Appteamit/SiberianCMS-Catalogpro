<?php
/**
 *
 * Schema definition for 'catalogpro_categories'
 *
 * Last update: 2020-06-15
 *
 */
$schemas = (!isset($schemas)) ? [] : $schemas;
$schemas['catalogpro_categories'] = [
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
            'name' => 'FK_CATALOGPRO_CATEGORIES_VID',
            'on_update' => 'CASCADE',
            'on_delete' => 'CASCADE',
        ],
        'index' => [
            'key_name' => 'value_id',
            'index_type' => 'BTREE',
            'is_null' => false,
            'is_unique' => false,
        ],
    ], 
    'category_name' => [
        'type' => 'varchar(255)',
        'is_null' => false,
    ],
    'short_description' => [
        'type' => 'varchar(255)',
        'is_null' => true,
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
    ],
    'position' => [
        'type' => 'int(11) unsigned',
        'default' => "1",
        'is_null' => true,
    ],
    'image' => [
        'type' => 'text',
        'is_null' => true,
    ],
    'status' => [
        'type' => 'tinyint(11)',
        'default' => "0",
        'is_null' => true,
    ],
    'created_at' => [
        'type' => 'datetime',
    ],
    'updated_at' => [
        'type' => 'datetime',
    ],
];