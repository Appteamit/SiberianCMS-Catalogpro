<?php 

/**
 * Class Catalogpro_Model_Catalogpro
 * @package Emart\Model
 */
class Catalogpro_Model_Catalogpro extends Core_Model_Default
{
 
    /**
     * @param $value_id
     * @return array|bool
     */
    public function getInappStates($value_id)
    {
        
        $inAppStates = [
            [
                "state" => "catalogpro-home",
                "offline" => false,
                "params" => [
                    "value_id" => $value_id,
                ],            
            ],
        ];

        return $inAppStates;
    }

    /**
     * @return null
     */
    public static function getCurrentValueId()
    {
        $app = self::getApplication();
        if ($app) {
            $options = $app->getOptions();
            foreach ($options as $option) {
                if ($option->getCode() === "catalogpro") {
                    return $option->getId();
                }
            }
        }
        return null;
    }

    /**
     * @return null
     */
    public static function getCurrent()
    {
        $app = self::getApplication();
        if ($app) {
            $options = $app->getOptions();
            foreach ($options as $option) {
                if ($option->getCode() === "catalogpro") {
                    return $option;
                }
            }
        }
        return null;
    }
 
}