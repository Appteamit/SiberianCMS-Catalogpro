<?php
/**
 *
 * Schema definition for 'catalogpro_report'
 *
 * Last update: 2023-12-12
 *
 */
$schemas = (!isset($schemas)) ? [] : $schemas;
$schemas['catalogpro_report'] = [
    'report_id' => [
        'type' => 'int(11) unsigned',
        'auto_increment' => true,
        'primary' => true,
    ],
    'comment_id' => [
        'type' => 'int(11) unsigned',
        'foreign_key' => [
            'table' => 'catalogpro_products_comments',
            'column' => 'id',
            'name' => 'FK_CATALOGPRO_REPORT_COMMENT_CID',
            'on_update' => 'CASCADE',
            'on_delete' => 'CASCADE',
        ],
        'index' => [
            'key_name' => 'id',
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
            'name' => 'FK_CATALOGPRO_REPORT_CID',
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
    'report_reason' => [
        'type' => 'text',
        'is_null' => false,
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
    ],
    'report_comment' => [
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