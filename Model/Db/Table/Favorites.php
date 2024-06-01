<?php

class Catalogpro_Model_Db_Table_Favorites extends Core_Model_Db_Table {
    protected $_name                    = "catalogpro_products_favorites";
    protected $_primary                 = "id";
    

     /**
     * @param $customer_id
     * @param int $limit
     * @return array
     */
    public function findByCustomerId($customer_id, $params = []) {
        $select = $this->_db->select()
            ->from(['main' => $this->_name], [
            	"id as favorite_id",
            	"product_id",
            	"customer_id"
            ]);
       
            $select->where("main.customer_id = ?", $customer_id);

            $select->joinLeft(['c' => 'catalogpro_products'], 'c.id = main.product_id' , [
            	"id",
                "parent_id",
                "short_description",
                "product_name",
                "price",               
                "special_price",
                "status",
                "description",
                "viewed",
                "created_at",
                "product_image" => new Zend_Db_Expr('('.$this->_db->select()->from(array('i'=> 'catalogpro_product_images'),array(new Zend_Db_Expr('i.product_image')))->where('i.product_id = main.product_id')->limit(1, 0).')'),  
                "is_favorite" => new Zend_Db_Expr('('.$this->_db->select()->from(array('f'=> 'catalogpro_products_favorites'),array(new Zend_Db_Expr('COUNT(f.id)')))->where('f.product_id = main.product_id')->where('f.customer_id', $params['customer_id']).')')                    
            ]);
          
            if (array_key_exists("limit", $params) && array_key_exists("offset", $params)) {
	            $select->limit($params["limit"], $params["offset"]);
	        }
            
            $select->order('main.id DESC');
             
          return $this->toModelClass($this->_db->fetchAll($select));
    }
 
}