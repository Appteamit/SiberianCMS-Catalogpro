<?php 

/**
 * Class Catalogpro_Model_ProductCategory
 * @package Emart\Model
 */
class Catalogpro_Model_ProductCategory extends Core_Model_Default
{
    /**
     * @var null
     */
    public static $acl = null;

 
    /**
     * @var string
     */
    protected $_db_table = Catalogpro_Model_Db_Table_ProductCategory::class;

}