<?php 

/**
 * Class Catalogpro_Model_Products
 * @package Emart\Model
 */
class Catalogpro_Model_Products extends Core_Model_Default
{
    /**
     * @var null
     */
    public static $acl = null;

 
    /**
     * @var string
     */
    protected $_db_table = Catalogpro_Model_Db_Table_Products::class;

    /**
     * @param $valuesId
     * @param array $params
     * @return Catalogpro_Model_Category[]
     */
    public function findByValueId($valuesId, $params = [])
    {
        return $this->getTable()->findByValueId($valuesId, $params);
    }
    /**
     * @param $valuesId
     * @param array $params
     * @ // (R)
     */
    public function sortable($params, $value_id)
    {
        return $this->getTable()->sortable($params, $value_id);
    }
    
    /**
     * @param $valuesId
     * @param array $params
     * @return Catalogpro_Model_Category[]
     */
    public function countAllForApp($valuesId, $params = [])
    {
        return $this->getTable()->countAllForApp($valuesId, $params);
    }


}