<?php 

/**
 * Class Catalogpro_Model_Comments
 * @package Emart\Model
 */
class Catalogpro_Model_Comments extends Core_Model_Default
{
    /**
     * @var null
     */
    public static $acl = null;

 
    /**
     * @var string
     */
    protected $_db_table = Catalogpro_Model_Db_Table_Comments::class;

    /**
     * @param $productId
     * @param array $params
     * @return Catalogpro_Model_Comments[]
     */
    public function findByProductId($productId, $params = [])
    {
        return $this->getTable()->findByProductId($productId, $params);
    }

}