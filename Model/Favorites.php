<?php 

/**
 * Class Catalogpro_Model_Favorites
 * @package Emart\Model
 */
class Catalogpro_Model_Favorites extends Core_Model_Default
{
    /**
     * @var null
     */
    public static $acl = null;

 
    /**
     * @var string
     */
    protected $_db_table = Catalogpro_Model_Db_Table_Favorites::class;

   /**
     * @param $customer_id
     * @param array $params
     * @return Catalogpro_Model_Favorites[]
     */
    public function findByCustomerId($customer_id, $params = [])
    {
        return $this->getTable()->findByCustomerId($customer_id, $params);
    }
}