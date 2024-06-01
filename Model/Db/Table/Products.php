<?php

class Catalogpro_Model_Db_Table_Products extends Core_Model_Db_Table {
    protected $_name                    = "catalogpro_products";
    protected $_primary                 = "id";
    
          /**
     * @param $value_id
     * @param int $limit
     * @return array
     */
    public function findByValueId($value_id, $params = []) {
        $select = $this->_db->select()
            ->from(['main' => $this->_name], [
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
                "value_id",
                "product_image" => new Zend_Db_Expr('('.$this->_db->select()->from(array('i'=> 'catalogpro_product_images'),array(new Zend_Db_Expr('i.product_image')))->where('i.product_id = main.id')->limit(1, 0).')'),  
                "is_favorite" => new Zend_Db_Expr('('.$this->_db->select()->from(array('f'=> 'catalogpro_products_favorites'),array(new Zend_Db_Expr('COUNT(f.id)')))->where('f.product_id = main.id')->where('f.customer_id', $params['customer_id']).')')                    
            ]);
        
            if (array_key_exists("category_id", $params) && !empty($params["category_id"])) {
	            $select->joinLeft(['c' => 'catalogpro_products_category'], 'c.product_id = main.id AND c.category_id = '.$params["category_id"].'' ,array());
               $select->where("(c.category_id = ?)", $params["category_id"] );
	          }

            if (array_key_exists("status", $params) && !empty($params["status"])) {
                $select->where("main.status = ?", $params['status']);
                
            }else{
                $select->where("main.status != ?", 2);
            }
         
            if (array_key_exists("limit", $params) && array_key_exists("offset", $params)) {
	            $select->limit($params["limit"], $params["offset"]);
	          }

	          if (array_key_exists("search", $params)  && !empty($params["search"])) {
              $term = $params["search"];
              $select->where("main.product_name LIKE '%$term%' OR main.short_description LIKE '%$term%' OR main.description LIKE '%$term%'");

            }
      
            if (array_key_exists("sorts", $params) && !empty($params["sorts"])) {
              $orders = [];
              foreach ($params["sorts"] as $key => $dir) {
                  $order = ($dir == -1) ? "DESC" : "ASC";
                  $orders = "main.{$key} {$order}";
              }
              $select->order($orders);

            } else {
            //   $select->order('main.id DESC');
              $select->order('main.position ASC'); // (R)
            }

            $select->where("main.value_id = ?", $value_id);

         return $this->toModelClass($this->_db->fetchAll($select));
    }

    /**
     * @param $data
     * @ // (R)
     */
    public function sortable($data, $value_id) {       
      try {
            foreach ($data as $key => $value) {
                if($value!=''){
                   $id = explode('_',$value, 2);
                    $this->_db->update($this->_name , array('position' => $key+1 ) , array('id = ? ' => $id[1], 'value_id = ?' => $value_id ));
                }
            }
        }catch(Exception $e) {
            $this->_db->rollBack();
        }
         return $this;
    }
 	 /**
     * @param $value_id
     */
    public function countAllForApp($value_id, $params = [])
    {
        $select =$this->_db->select()
            ->from(['main' => $this->_name], [ 
            	 'COUNT(main.id)'
                ])
            ->where('main.value_id = ?', $value_id);

       
        if (array_key_exists("category_id", $params) && !empty($params["category_id"])) {
            $select->joinLeft(['c' => 'catalogpro_products_category'], 'c.product_id = main.id AND c.category_id = '.$params["category_id"].'' ,array());
            $select->where("(c.category_id = ?)", $params["category_id"] );
        }

        if (array_key_exists("status", $params) && !empty($params["status"])) {
            $select->where("main.status = ?", $params['status']);
                
        }else{
            $select->where("main.status != ?", 2);
        }

		    if (array_key_exists("search", $params)  && !empty($params["search"])) {
            $term = $params["search"];
            $select->where("main.product_name LIKE '%$term%' OR main.short_description LIKE '%$term%' OR main.description LIKE '%$term%'");
        }
 
        return $this->_db->fetchCol($select);
    }
}