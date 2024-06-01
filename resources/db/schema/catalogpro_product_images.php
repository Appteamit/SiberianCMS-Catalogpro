<?php
/**
 *
 * Schema definition for 'catalogpro_product_images'
 *
 * Last update: 2020-06-18
 *
 */
$schemas = (!isset($schemas)) ? [] : $schemas;
$schemas['catalogpro_product_images'] = [
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
            'name' => 'FK_CATALOGPRO_PRODUCT_IMAGES_PID',
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
   'product_image' => [
        'type' => 'text',
        'is_null' => false,
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
    ],
    'created_at' => [
        'type' => 'datetime',
    ],
    'updated_at' => [
        'type' => 'datetime',
    ],
];
