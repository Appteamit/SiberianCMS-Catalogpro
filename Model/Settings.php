<?php 

/**
 * Class Catalogpro_Model_Settings
 * @package Emart\Model
 */
class Catalogpro_Model_Settings extends Core_Model_Default
{
    /**
     * @var null
     */
    public static $acl = null;

 
    /**
     * @var string
     */
    protected $_db_table = Catalogpro_Model_Db_Table_Settings::class;

}