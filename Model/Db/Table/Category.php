<?php

class Catalogpro_Model_Db_Table_Category extends Core_Model_Db_Table {
    protected $_name                    = "catalogpro_categories";
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
                "category_name",
                "position",
                "image",               
                "status",
                "created_at",
                "short_description",
                "value_id",
                "total_product" => new Zend_Db_Expr('('.$this->_db->select()->from(array('i'=> 'catalogpro_products_category'),array(new Zend_Db_Expr('COUNT(i.id)')))->where('i.category_id = main.id').')'),                         
            ]);

             $select->where("main.value_id = ?", $value_id);
            
            if (array_key_exists("status", $params)) {
                $select->where("main.status = ?", $params['status']);
                
            }else{
                $select->where("main.status != ?", 2);
            }
         
            if (array_key_exists("limit", $params) && array_key_exists("offset", $params)) {
	            $select->limit($params["limit"], $params["offset"]);
	        }

	        if (array_key_exists("filter", $params)) {
	            $select->where("(main.category_name LIKE ?)", "%" . $params["filter"] . "%");
	        }
  
            if (array_key_exists("sorts", $params) && !empty($params["sorts"])) {
	            $orders = [];
	            foreach ($params["sorts"] as $key => $dir) {
	                $order = ($dir == -1) ? "DESC" : "ASC";
	                $orders = "main.{$key} {$order}";
	            }
	            $select->order($orders);
	        } else {
	            $select->order('main.position ASC');
	        }
          
          return $this->toModelClass($this->_db->fetchAll($select));
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

        $select->where("main.status != ?", 2);
   
        if (array_key_exists("filter", $params)) {
            $select->where("(main.category_name LIKE ?)", "%" . $params["filter"] . "%");
        }
 
        return $this->_db->fetchCol($select);
    }

    /**
     * @param $value_id
     */
    public function maxPosition($value_id)
    {
        $select =$this->_db->select()
            ->from(['main' => $this->_name], [ 
                 'MAX(main.position)'
                ])
        ->where('main.value_id = ?', $value_id);
       
        return $this->_db->fetchCol($select);
    }

     /**
     * @param $data
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
    
}