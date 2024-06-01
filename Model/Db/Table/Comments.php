<?php

class Catalogpro_Model_Db_Table_Comments extends Core_Model_Db_Table {
    protected $_name                    = "catalogpro_products_comments";
    protected $_primary                 = "id";
    

          /**
     * @param $productId
     * @param int $limit
     * @return array
     */
    public function findByProductId($productId, $params = []) {
        $select = $this->_db->select()
            ->from(['main' => $this->_name], [
            	"id",
                "customer_id",
                "comment_text",
                "rating_number",               
                "created_at"                                        
            ]);

           $select->joinLeft(['c' => 'customer'], 'c.customer_id = main.customer_id', ['c.firstname', 'c.lastname' , 'c.image']);

            $select->where("main.product_id = ?", $productId);
            $select->order('main.id DESC');
	         
          
          return  $this->_db->fetchAll($select);
    }


    
}