<?php
/**
 *
 * Schema definition for 'catalogpro_products_favorites'
 *
 * Last update: 2020-06-22
 *
 */
$schemas = (!isset($schemas)) ? [] : $schemas;
$schemas['catalogpro_products_favorites'] = [
    'id' => [
        'type' => 'int(11) unsigned',
        'auto_increment' => true,
        'primary' => true,
    ],
    'product_id' => [
        'type' => 'int(11) unsigned',
        'foreign_key' => [
            'table' => 'catalogpro_products',
            'column' => 'id',
            'name' => 'FK_CATALOGPRO_PRODUCT_FAVORITES_AOV_PID',
            'on_update' => 'CASCADE',
            'on_delete' => 'CASCADE',
        ],
        'index' => [
            'key_name' => 'product_id',
            'index_type' => 'BTREE',
            'is_null' => false,
            'is_unique' => false,
        ],
    ],
    'customer_id' => [
        'type' => 'int(11) unsigned',
        'foreign_key' => [
            'table' => 'customer',
            'column' => 'customer_id',
            'name' => 'FK_CATALOGPRO_PRODUCT_FAVORITES_CUSTOMER_CID',
            'on_update' => 'CASCADE',
            'on_delete' => 'CASCADE',
        ],
        'index' => [
            'key_name' => 'customer_id',
            'index_type' => 'BTREE',
            'is_null' => false,
            'is_unique' => false,
        ],
    ], 
    'created_at' => [
        'type' => 'datetime',
    ],
    'updated_at' => [
        'type' => 'datetime',
    ],
];
