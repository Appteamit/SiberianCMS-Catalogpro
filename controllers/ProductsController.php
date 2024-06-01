<?php

/**
 * Class Catalogpro_ProductsController
 */
class Catalogpro_ProductsController extends Application_Controller_Default
{


    /**
     *load all products and manage 
     */
    public function listAction()
    {
    	$this->loadPartials();
    }

    /**
     *add new products
     */
    public function addAction()
    {
    	$this->loadPartials();
    }

     /**
     * edit products
     */
    public function editAction()
    {   
         $modelProduct = (new Catalogpro_Model_Products());  
            if ($id = $this->getRequest()->getParam('id')) {
                $modelProduct->find($id); 
                if (!$modelProduct->getId()) {
                        $this->getRequest()->addError( p__("catalogpro",  "This product does not exist."));
                }
            }
        $category_id = 0;
        $modelProductCategory = (new Catalogpro_Model_ProductCategory())
                    ->find(['product_id' => $id]);
        if($modelProductCategory->getId()){
            $category_id = (integer) $modelProductCategory->getCategoryId();
        }

        $modelProductImages = (new Catalogpro_Model_Images())
                    ->findAll(['product_id' => $id]);

        $this->loadPartials();
        $this->getLayout()->getPartial('content')->setCurrentProduct($modelProduct)->setCategoryId($category_id)->setProductImages($modelProductImages);
    }
    
     /**
     * fetch category
     */
     public function findAllAction() {
        
        try {
            $request = $this->getRequest();
            $limit = $request->getParam("perPage", 25);
            $offset = $request->getParam("offset", 0);
            $sorts = $request->getParam("sorts", []);
            $queries = $request->getParam("queries", []);
         
            $search = null;
            if (array_key_exists("search", $queries)) {
                $search = $queries["search"];
            }
            if (array_key_exists("cid", $queries)) {
                $category_id = $queries["cid"];
            }
            
            $params = [
                "limit" => $limit,
                "offset" => $offset,
                "sorts" => $sorts,
                "search" => $search,
                "category_id" => $category_id
            ];
          
            $value_id = (new Catalogpro_Model_Catalogpro())->getCurrentValueId();
            
            $products = (new Catalogpro_Model_Products())
                ->findByValueId($value_id, $params);

            $countAll = (new Catalogpro_Model_Products())->countAllForApp($value_id);
            $countFiltered =   (new Catalogpro_Model_Products())->countAllForApp($value_id, $params);

            $productsJson = [];
            foreach ($products as $product) {
                $data = $product->getData();
                $data['id'] = (integer) $data['id'];
                $data['status'] = $data['status'] == 1 ? p__('catalogpro', "Active") : p__('catalogpro', "InActive"); 
                $data['currency'] = $this->getApplication()->getCurrency();
                $data['is_special'] = false;
                if(!empty($data['special_price']) && $data['special_price'] > 0){
                    $data['is_special'] = true;
                }
                $data['price'] = $this->getApplication()->getCurrency().$data['price'];
                $data['special_price'] = $this->getApplication()->getCurrency().$data['special_price'];
               $productsJson[] = $data;
            }

            $payload = [
                "records" => $productsJson,
                "queryRecordCount" => $countFiltered[0],
                "totalRecordCount" => $countAll[0]
            ];

        } catch (\Exception $e) {
            $payload = [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }

        $this->_sendJson($payload);
    }
    
      /**
     * @param $param
     * @return array
     * @throws Siberian_Exception
     * @ // (R)
     */
    public function sortableAction() {
        $payload = array();
        if($data = $this->getRequest()->getPost('data')) {
            $value_id = (new Catalogpro_Model_Catalogpro())->getCurrentValueId();
            $model = new Catalogpro_Model_Products();           
            $model->sortable($data, $value_id);                
            $payload = array("success" => 1);
        }
        
        $this->_sendJson($payload);     
    }

    public function saveAction() {
        $payout = [];

        if($param = $this->getRequest()->getPost()) {     
            $value_id = $param['value_id'];

            try {  
                $param['status'] = $param['status'] == 1 ? 1: 0;
                   
                $modelProduct = (new Catalogpro_Model_Products())
                    ->find(['id' => $param['id']])
                    ->setValueId($value_id)
                    ->setProductName($param['product_name'])
                    ->setShortDescription($param['short_description'])
                    ->setPrice($param['price'])
                    ->setSpecialPrice($param['special_price'])
                    ->setDescription($param['description'])
                    ->setStatus($param['status'])
                    ->save();

                $product_id =  $modelProduct->getId();
                $modelProductCategory = (new Catalogpro_Model_ProductCategory())
                    ->find(['product_id' => $product_id, 'category_id' => $param['category']])
                    ->setProductId($product_id)
                    ->setCategoryId($param['category'])
                    ->save();


                if(!empty($param['images'])){
                    foreach ($param['images'] as $key => $value) {
                       if (file_exists(Core_Model_Directory::getTmpDirectory(true) . "/" . $value)) {
                                list($relativePath, $filename) = $this->_getImageData($value);
                                $imageUrl = $relativePath . '/' . $filename;
                                $productImage = (new Catalogpro_Model_Images())
                                                ->setProductId($product_id)
                                                ->setProductImage($imageUrl)
                                                ->save();                            
                        }
                    }
                }

                $this->getSession()->addSuccess(p__('catalogpro', "Info successfully saved")); 
                $payout = [
                    "success" => 1
                ];

          }catch(Exception $e) {
                $payout = [
                    "error" => 1,
                    "message" => $e->getMessage(),
                    'message_button' => 1,
                    'message_loader' => 1
                ];
            }

            $this->getResponse()->setBody(Zend_Json::encode($payout))->sendResponse();
            die;

        }
    }


     //function for delete category  
    public function deleteAction() {
      try {
            $request = $this->getRequest();
            $id = $request->getParam("id", null);
            $model = new Catalogpro_Model_Products();
            $model->find(array('id' => $id));
            $model->setStatus(2);
            $model->save();

                $payload = [
                    'success' => true,
                    'message' => p__('catalogpro', 'Successfully deleted'),
                ];
        } catch (\Exception $e) {
            $payload = [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }

        $this->_sendJson($payload);
    }

     //function for delete category  
    public function deleteImageAction() {
      try {
            $request = $this->getRequest();
            $id = $request->getParam("id", null);
            $model = new Catalogpro_Model_Images();
            $model->find(array('id' => $id));
            $model->delete();

                $payload = [
                    'success' => true,
                    'message' => p__('catalogpro', 'Successfully deleted'),
                ];
        } catch (\Exception $e) {
            $payload = [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }

        $this->_sendJson($payload);
    }


     /**
     * @param $image
     * @return array
     * @throws Siberian_Exception
     */
    private function _getImageData($image)
    {

        $img_src = Core_Model_Directory::getTmpDirectory(true) . "/" . $image;

        $info = pathinfo($img_src);

        $filename = $info['basename'];

        $relativePath = $this->getCurrentOptionValue()->getImagePathTo();

        $img_dst = Application_Model_Application::getBaseImagePath() . $relativePath;

        if (!is_dir($img_dst)) {
            mkdir($img_dst, 0777, true);
        }
        $img_dst .= '/' . $filename;
        rename($img_src, $img_dst);
        
        if (!file_exists($img_dst)) {
            throw new Siberian_Exception(p__('propertylisting', 'An error occurred while saving your picture. Please try againg later.'));
        }
        return [$relativePath, $filename];
    }


}