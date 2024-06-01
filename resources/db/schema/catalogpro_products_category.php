<?php
/**
 *
 * Schema definition for 'catalogpro_products_category'
 *
 * Last update: 2020-06-15
 *
 */
$schemas = (!isset($schemas)) ? [] : $schemas;
$schemas['catalogpro_products_category'] = [
    'id' => [
        'type' => 'int(11) unsigned',
        'auto_increment' => true,
        'primary' => true,
    ],
    'category_id' => [
        'type' => 'int(11) unsigned',
        'foreign_key' => [
            'table' => 'catalogpro_categories',
            'column' => 'id',
            'name' => 'FK_CATALOGPRO_PRODUCT_CATEOGRY_AOV_CID',
            'on_update' => 'CASCADE',
            'on_delete' => 'CASCADE',
        ],
        'index' => [
            'key_name' => 'category_id',
            'index_type' => 'BTREE',
            'is_null' => false,
            'is_unique' => false,
        ],
    ],
    'product_id' => [
        'type' => 'int(11) unsigned',
        'foreign_key' => [
            'table' => 'catalogpro_products',
            'column' => 'id',
            'name' => 'FK_CATALOGPRO_PRODUCT_AOV_PID',
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
    'created_at' => [
        'type' => 'datetime',
    ],
    'updated_at' => [
        'type' => 'datetime',
    ],
];
