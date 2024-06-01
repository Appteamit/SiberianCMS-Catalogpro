<?php

/**
 * Class Catalogpro_ApplicationController
 */
class Catalogpro_ApplicationController extends Application_Controller_Default
{

    /**
     *
     */
    public function editAction()
    {
        parent::editAction();
    }

  /**
     *
     */
    public function editpostAction()
    {
        try
        {
            $values = $this->getRequest()->getPost();
            $form = new Catalogpro_Form_Settings();

            if ($form->isValid($values))
            {
                $settings = new Catalogpro_Model_Settings();
                $settings->addData($values);
                $settings->save();

                $payload = ["success" => "1", "success_message" => p__("catalogpro", "Saved successfully") , 'message_timeout' => 1, 'message_button' => 0, 'message_loader' => 0, ];

            }
            else
            {
                /** Do whatever you need when form is not valid */
                $payload = ["error" => true, "message" => $form->getTextErrors() , "errors" => $form->getTextErrors(true) , ];
            }

        }
        catch(\Exception $e)
        {
            $payload = ["error" => true, "message" => $e->getMessage() , ];
        }

        $this->_sendJson($payload);
    }

    /*Crop emart image*/
    public function cropAction() {

        if($datas = $this->getRequest()->getPost()) {
            try {
                $uploader = new Core_Model_Lib_Uploader();
                $file = $uploader->savecrop($datas);
                $datas = [
                    'success' => 1,
                    'file' => $file,
                    'message_success' => p__("catalogpro", "Upload successfully"),
                    'message_button' => 0,
                    'message_timeout' => 2,
                ];
            } catch (Exception $e) {
                $datas = [
                    'error' => 1,
                    'message' => $e->getMessage()
                ];
            }
            $this->getLayout()->setHtml(Zend_Json::encode($datas));
         }
    }
   
}