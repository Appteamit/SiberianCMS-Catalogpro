<?php

use Siberian\Exception;

/**
 * Class Catalogpro_Mobile_ViewController
 */
class Catalogpro_Mobile_ViewController extends Application_Controller_Mobile_Default
{

    /**
     * Fetch All
     *
     */
    public function findAllAction()
    {
        try {

            if($value_id = $this->getRequest()->getParam('value_id')){              

                $settings = (new Catalogpro_Model_Settings())->find(['value_id' => $value_id]);
                $page_title = $this->getCurrentOptionValue()->getTabbarName();
                
                // Get Categories 
                $categories_collection = [];
                $page_title = p__('catalogpro', 'Categories');
                $params = ["limit" => 100, "offset" => 0, "status" => 1 ];
                $categories = (new Catalogpro_Model_Category())->findByValueId($value_id, $params);                    
                foreach ($categories as $item) {
                    $item = $item->getData();
                    $categories_collection[] = [
                        "id" => (integer) $item['id'],
                        "category_name"=> (string) $item['category_name'],
                        "image"=> (string) $item['image'],
                        "total_product" => (integer) $item['total_product'],
                        "short_description" => $item['short_description']
                    ];
                }
             

                 // Get Products 
                $product_collection = [];
                if($settings->getHomeScreen() == "product") {
                    $page_title = p__('catalogpro', 'Products');
                    $params = ["limit" => 10, "offset" => 0, "status" => 1, "customer_id" => $this->_getCustomerId(false) ];
                    $products = (new Catalogpro_Model_Products())->findByValueId($value_id, $params);            
                     
                    foreach ($products as $product) {
                        $data = $product->getData();
                        $data['id'] = (integer) $data['id'];
                        $data['status'] = $data['status'] == 1 ? p__('catalogpro', "Active") : p__('catalogpro', "InActive"); 
                        $data['currency'] = Core_Model_Language::getCurrencySymbol();
                        $data['is_special'] = false;
                        if(!empty($data['special_price']) && $data['special_price'] > 0){
                            $data['is_special'] = true;
                        }
                        $data['price'] = Core_Model_Language::getCurrencySymbol().$data['price'];
                        $data['special_price'] = Core_Model_Language::getCurrencySymbol().$data['special_price'];
                       $product_collection[] = $data;
                    }
                }

                $payload = [
                        'success' => true,
                        'page_title' => (string) $page_title,
                        'settings' => $settings->getData(),
                        'categories' => $categories_collection,
                        'products' => $product_collection
                   ];
                 
            }

        } catch (\Exception $e) {
            $payload = [
                "error" => true,
                "message" => $e->getMessage()
            ];
        }

        $this->_sendJson($payload);
    }
    
    /**
     * @param bool $throw
     * @return mixed|null
     * @throws Exception
     * @throws Zend_Session_Exception
     */
    private function _getCustomerId($throw = true)
    {
        $request = $this->getRequest();
        $session = $this->getSession();
        $customerId = $session->getCustomerId();
        if ($throw && empty($customerId)) {
            throw new Exception(p__('catalogpro', 'Customer login required!'));
        }
        return $customerId;
    }

  
}

