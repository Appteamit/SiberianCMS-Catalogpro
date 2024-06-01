<?php

use Siberian\Exception;

/**
 * Class Catalogpro_Mobile_ProductController
 */
class Catalogpro_Mobile_ProductController extends Application_Controller_Mobile_Default
{
    
    /**
     * Fetch All
     *
     */
    public function loadProductsAction()
    {
        try {

            if($value_id = $this->getRequest()->getParam('value_id')){              
                $category_id = $this->getRequest()->getParam('category_id', null);
                $search = $this->getRequest()->getParam('search', null);
                $settings = (new Catalogpro_Model_Settings())->find(['value_id' => $value_id]);
                // Get Products 
                $product_collection = [];
                $params = ["limit" => 10, 
                            "offset" => $this->getRequest()->getParam('offset', 0), 
                            "status" => 1,
                            "category_id" => $category_id,
                            "customer_id" => $this->_getCustomerId(false),
                            "search" => $search
                        ];

                $products = (new Catalogpro_Model_Products())->findByValueId($value_id, $params);   
                foreach ($products as $product) {
                    $data = $product->getData();

                    $data['is_price_empty'] = false;
                    $data['id'] = (integer) $data['id'];
                    $data['status'] = $data['status'] == 1 ? p__('catalogpro', "Active") : p__('catalogpro', "InActive"); 
                    $data['currency'] = Core_Model_Language::getCurrencySymbol();
                    $data['is_special'] = false;
                    if(!empty($data['special_price']) && $data['special_price'] > 0){
                        $data['is_special'] = true;
                    }

                    $data['is_price_empty'] = (empty($data['price']) || $data['price'] <= 0) ? true : false;
                    $data['price'] = Core_Model_Language::getCurrencySymbol().$data['price'];
                    $data['special_price'] = Core_Model_Language::getCurrencySymbol().$data['special_price'];
                   $product_collection[] = $data;
                }
                
                $modelCategory = (new Catalogpro_Model_Category())->find(['id' => $category_id]); 
                
                $payload = [
                        'success' => true,
                         'products' => $product_collection,
                         'category' => $modelCategory->getData(),
                         'settings' => $settings->getData()
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
     * Fetch Favorites All
     *
     */
    public function loadProductsFavoritesAction()
    {
        try {

            if($value_id = $this->getRequest()->getParam('value_id')){              
               $customerId = $this->_getCustomerId();
                
                // Get Products 
                $product_collection = [];
                $params = ["limit" => 10, 
                            "offset" => $this->getRequest()->getParam('offset', 0), 
                            "status" => 1,
                         ];

                $products = (new Catalogpro_Model_Favorites())->findByCustomerId($customerId, $params);   

                foreach ($products as $product) {
                    $data = $product->getData();
                    $data['id'] = (integer) $data['id'];
                    $data['status'] = $data['status'] == 1 ? p__('catalogpro', "Active") : p__('catalogpro', "InActive"); 
                    $data['currency'] = Core_Model_Language::getCurrencySymbol();
                    $data['is_special'] = false;
                    if(!empty($data['special_price']) && $data['special_price'] > 0){
                        $data['is_special'] = true;
                    }
                    $data['is_price_empty'] = (empty($data['price']) || $data['price'] <= 0) ? true : false;
                    $data['price'] = Core_Model_Language::getCurrencySymbol().$data['price'];
                    $data['special_price'] = Core_Model_Language::getCurrencySymbol().$data['special_price'];
                   $product_collection[] = $data;
                }
               
                $payload = [
                        'success' => true,
                         'products' => $product_collection,
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
     * product details
     *
     */
    public function productDetailsAction()
    {
        try {

            if($value_id = $this->getRequest()->getParam('value_id')) {              
                $product_id = $this->getRequest()->getParam('product_id', 0);
               
                $product = (new Catalogpro_Model_Products())->find(["id" => $product_id]);
                $product = $product->getData();
                $product['currency'] = Core_Model_Language::getCurrencySymbol();
                $product['is_special'] = false;
                if(!empty($product['special_price']) && $product['special_price'] > 0){
                    $product['is_special'] = true;
                }
                $product['is_price_empty'] = (empty($product['price']) || $product['price'] <= 0) ? true : false;
                $product['price'] = Core_Model_Language::getCurrencySymbol().$product['price'];
                $product['special_price'] = Core_Model_Language::getCurrencySymbol().$product['special_price'];

                $gallery = (new Catalogpro_Model_Images())->findAll(['product_id' => $product_id])->toArray();

                $customerId = $this->_getCustomerId(false);
                $favorite = (new Catalogpro_Model_Favorites())->find(["product_id" => $product_id, 'customer_id' => $customerId]);
                $product['is_favorite'] = 0;
                if($favorite->getId()){
                    $product['is_favorite'] = 1;
                }

                $comments = (new Catalogpro_Model_Comments())->findByProductId($product_id);
                $total_comment = count($comments);
                $rating =  $avg_rating = 0;
               
                foreach ($comments as $key => $value) {
                    $rating =  $rating + (integer) $value['rating_number'];
                }
                if($total_comment > 0){
                     $avg_rating = number_format(($rating/$total_comment), 1);
                }
               
               
                $payload = [
                    'success' => true,
                    'product' => $product,
                    'gallery' => $gallery,
                    'total_images' => count($gallery),
                    'comments' => $comments,
                    'avg_rating' => $avg_rating
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
     * get Comment 
     *
     */
    public function getCommentAction()
    {
        try {

            if($value_id = $this->getRequest()->getParam('value_id')) {  
                $comment_id = $this->getRequest()->getParam('comment_id', 0);
                $comments = (new Catalogpro_Model_Comments())->find($comment_id);
                $payload = [
                    'success' => true,
                    'comments' => $comments->getData()
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
     * product details
     *
     */
    public function markAsFavoriteAction()
    {
        try {

            if($value_id = $this->getRequest()->getParam('value_id')){              
                $product_id = $this->getRequest()->getParam('product_id', 0);
                $customerId = $this->_getCustomerId();

                $favorite = (new Catalogpro_Model_Favorites())->find(["product_id" => $product_id, 'customer_id' => $customerId]);
                
                $is_favorite = 0;

                if($favorite->getId()){
                    $favorite->delete();
                
                }else{
                    $favorite->setCustomerId( $customerId);
                    $favorite->setProductId($product_id);
                    $favorite->save();
                    $is_favorite = 1;
                }    
                
                $payload = [
                    'success' => true,
                    'favorite' => $favorite->getData(),
                    'is_favorite' => $is_favorite
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
     * save Rating
     *
     */
    public function saveRatingAction()
    {
        try {

            if($value_id = $this->getRequest()->getParam('value_id')){              
                $params = $this->getRequest()->getBodyParams();
                $customerId = $this->_getCustomerId();

                $comment = (new Catalogpro_Model_Comments())
                                    ->setProductId($params['product_id'])
                                    ->setCustomerId($customerId)
                                    ->setCommentText($params['comment_text'])
                                    ->setRatingNumber($params['rating_number'])
                                    ->save();
                 
                $payload = [
                    'success' => true,
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
     * save Report
     *
     */
    public function submitReportAction()
    {
        try {

            if($value_id = $this->getRequest()->getParam('value_id')){              
                $params = $this->getRequest()->getBodyParams();
                $customerId = $this->_getCustomerId();
                $report = (new Catalogpro_Model_Report())
                                    ->setCommentId($params['comment_id'])
                                    ->setCustomerId($customerId)
                                    ->setReportReason($params['selectedReason'])
                                    ->setReportComment($params['additionalComments'])
                                    ->save();
                 
                $payload = [
                    'success' => true,
                    'selectedReason' => $params['selectedReason'],
                    'additionalComments' => $params['additionalComments'],
                    'customerId' => $customerId,
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
            throw new Exception(p__('emenu', 'Customer login required!'));
        }
        return $customerId;
    }

  
}