<?php

/**
 * Class Catalogpro_CategoryController
 */
class Catalogpro_CategoryController extends Application_Controller_Default
{

    /**
     * Load a category
     */
    public function listAction()
    {
    	$this->loadPartials();
    }

     /**
     *add new category
     */
    public function addAction()
    {
    	$this->loadPartials();
    }


/*    public function qrcodeAction() {

        $value_id = $this->getRequest()->getParam("value_id");
        $category_id = $this->getRequest()->getParam("category_id");

        $url = $this->getApplication()->getBaseUrl().'/var/apps/browser/index-prod.html#/'.$this->getApplication()->getKey().'/catalogpro/mobile_list/index/value_id/'.$value_id.'/category_id/'.$category_id.'/is_scan/1';
         
        $client = new Zend_Http_Client();
        $url = $this->getApplication()->getQrcode(null, ['size' => '512x512', 'without_template' => 1]);
        $client->setUri($url);
        $client->setAdapter('Zend_Http_Client_Adapter_Curl');
        $response = $client->request();
        $qr_code = $response->getRawBody();

        if (!empty($qr_code)) {
            $this->_download($qr_code, 'category_qrcode.png', 'image/png');
        } else {
            $this->getSession()->addError(__('An error occurred during the generation of your QRCode. Please try again later.'));
            $this->_redirect('catalogpro/category/list');
        }

    }
*/

    /**
     * Embed QRCode generation!
     */
    public function qrcodeAction() {
        try {
            $value_id = $this->getRequest()->getParam("value_id");
             $category_id = $this->getRequest()->getParam("category_id");

        $url = $this->getApplication()->getBaseUrl().'/var/apps/browser/index-prod.html#/'.$this->getApplication()->getKey().'/catalogpro/mobile_list/index/value_id/'.$value_id.'/category_id/'.$category_id.'/is_scan/1';


            $qrCode = $this->generateQrCode($url);
                        
            // Directly output the QR code!
            header('Content-Type: ' . $qrCode->getContentType());
            echo $qrCode->writeString();
            die;
        } catch (Exception $e) {
            die(__('Invalid QRCode parameters.'));
        }
    }

           /**
     * @param $code
     * @param int $size
     * @param int $margin
     * @return \Endroid\QrCode\QrCode
     */
    private function generateQrCode ($code, $size = 200, $margin = 10) {
        $qrCode = new Endroid\QrCode\QrCode($code);
        $qrCode
            ->setSize($size)
            ->setWriterByName('png')
            ->setMargin($margin)
            ->setEncoding('UTF-8')
            ->setErrorCorrectionLevel(Endroid\QrCode\ErrorCorrectionLevel::MEDIUM)
            ->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0])
            ->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255])
            ->setValidateResult(false)
        ;

        return $qrCode;
    }


     /**
     * edit category
     */
    public function editAction()
    {   
         $modelCategory = (new Catalogpro_Model_Category());  
            if ($id = $this->getRequest()->getParam('id')) {
                $modelCategory->find($id); 
                if (!$modelCategory->getId()) {
                        $this->getRequest()->addError( p__("catalogpro",  "This category does not exist."));
                }
            }
        $this->loadPartials();
        $this->getLayout()->getPartial('content')->setCurrentCategory($modelCategory);
    }


    public function saveAction() {
        $payout = [];

        if($param = $this->getRequest()->getPost()) {     
            $value_id = $param['value_id'];
            try {  
            
                if(!empty($param['slider'])){
                    foreach ($param['slider'] as $key => $value) {
                       if (file_exists(Core_Model_Directory::getTmpDirectory(true) . "/" . $value)) {
                                list($relativePath, $filename) = $this->_getImageData($value);
                                $param['image'] = $relativePath . '/' . $filename;                            
                        }
                    }
                }

                $maxPostion = (new Catalogpro_Model_Category())->maxPosition($value_id);
                $position = $maxPostion[0] + 1;
                $param['status'] = $param['status'] == 1 ? 1: 0;
                   
                $model = (new Catalogpro_Model_Category())
                    ->find(['id' => $param['id']])
                    ->setValueId($value_id)
                    ->setCategoryName($param['category_name'])
                    ->setShortDescription($param['short_description'])
                    ->setStatus($param['status']);
                    
                    if(empty($param['id'])){
                        $model->setPosition($position);
                    }

                    if(!empty($param['image'])){
                        $model->setImage($param['image']);
                    }  
                                                          
                    $model->save();
                
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
         
            $filter = null;
            if (array_key_exists("search", $queries)) {
                $filter = $queries["search"];
            }
            
            $params = [
                "limit" => $limit,
                "offset" => $offset,
                "sorts" => $sorts,
                "filter" => $filter,
            ];
          
            $value_id = (new Catalogpro_Model_Catalogpro())->getCurrentValueId();
            
            $categories = (new Catalogpro_Model_Category())
                ->findByValueId($value_id, $params);

            $countAll = (new Catalogpro_Model_Category())->countAllForApp($value_id);
            $countFiltered =   (new Catalogpro_Model_Category())->countAllForApp($value_id, $params);

            $categoryJson = [];
            foreach ($categories as $category) {
                $data = $category->getData();
                $data['status'] = $data['status'] == 1 ? p__('catalogpro', "Active") : p__('catalogpro', "InActive"); 
                $data['short_description'] = !empty($data['short_description'])  ? $data['short_description'] : ''; 
                $categoryJson[] = $data;
            }

            $payload = [
                "records" => $categoryJson,
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


   //function for delete category  
    public function deleteAction() {
      try {
            $request = $this->getRequest();
            $id = $request->getParam("id", null);
            $model = new Catalogpro_Model_Category();
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

      /**
     * @param $param
     * @return array
     * @throws Siberian_Exception
     */
    public function sortableAction() {
        $payload = array();
        if($data = $this->getRequest()->getPost('data')) {  
            $value_id = (new Catalogpro_Model_Catalogpro())->getCurrentValueId();
            $model = new Catalogpro_Model_Category();                     
            $model->sortable($data, $value_id);                
            $payload = array("success" => 1);
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
            throw new Siberian_Exception(p__('catalogpro', 'An error occurred while saving your picture. Please try againg later.'));
        }
        return [$relativePath, $filename];
    }
     
}