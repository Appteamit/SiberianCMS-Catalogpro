<?php
/**
 *
 * Schema definition for 'catalogpro_products'
 *
 * Last update: 2023-12-07
 *
 */
$schemas = (!isset($schemas)) ? [] : $schemas;
$schemas['catalogpro_products'] = [
    'id' => [
        'type' => 'int(11) unsigned',
        'auto_increment' => true,
        'primary' => true,
    ],
    'parent_id' => [
        'type' => 'tinyint(11)',
        'default' => "0",
        'is_null' => true,
    ],
    'value_id' => [
        'type' => 'int(11) unsigned',
        'foreign_key' => [
            'table' => 'application_option_value',
            'column' => 'value_id',
            'name' => 'FK_CATALOGPRO_PRODUCTS_PID',
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
    'product_name' => [
        'type' => 'varchar(255)',
        'is_null' => false,
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
    ],
    'short_description' => [
        'type' => 'varchar(255)',
        'is_null' => false,
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
    ],
    'price' => [
        'type' => 'double unsigned',
        'default' => "0",
        'is_null' => true,
    ],
    'special_price' => [
        'type' => 'double unsigned',
        'default' => "0",
        'is_null' => true,
    ],
    'status' => [
        'type' => 'tinyint(11)',
        'default' => "1",
        'is_null' => true,
    ],
    'description' => [
        'type' => 'text',
        'is_null' => false,
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
    ],
    'viewed' => [
        'type' => 'int(11) unsigned ',
        'default' => "0",
    ],
    // (R)
    'position' => [
        'type' => 'int(11) unsigned',
        'default' => "1",
        'is_null' => true,
    ],
    'created_at' => [
        'type' => 'datetime',
    ],
    'updated_at' => [
        'type' => 'datetime',
    ],
];