<?php

class Catalogpro_Form_Settings extends Siberian_Form_Abstract
{
    
    public function init() {
        parent::init();
        
        $this
            ->setAction(__path("/catalogpro/application/editpost"))
            ->setAttrib("id", "form-add-catalogpro");
        
        self::addClass("create", $this); 

        $value_id = $this->addSimpleHidden("value_id");
        $store_id = $this->addSimpleHidden("id");

        $this->addSimpleCheckbox('enable_comments', p__('catalogpro', 'Enable Comments'));
        // $this->addSimpleCheckbox('enable_report', p__('catalogpro', 'Enable Report Comments'));
        $this->addSimpleCheckbox('enable_voting', p__('catalogpro', 'Enable Voting'));
        $this->addSimpleCheckbox('enable_favorites', p__('catalogpro', 'Enable Favorites'));
        $this->addSimpleText('admin_email', p__('catalogpro', 'Admin Email'));

        $this->addSimpleSelect('product_design', p__('catalogpro', 'Product Design'), [
            'list' => p__('catalogpro', 'List'),
            'grid' => p__('catalogpro', 'Grid'),
        ]);
        
        $this->addSimpleSelect('category_design', p__('catalogpro', 'Category Design'), [
            'list' => p__('catalogpro', 'List'),
            'grid' => p__('catalogpro', 'Grid'),
        ]);

        $this->addSimpleSelect('home_screen', p__('catalogpro', 'Home Screen'), [
            'product' => p__('catalogpro', 'Product'),
            'category' => p__('catalogpro', 'Category'),
        ]);
        $submit = $this->addSubmit( p__('catalogpro', 'Save'));
        $submit->addClass('pull-right');


   }
    
    public function setElementValueById($id, $value, $required = false) {
        $element = $this->getElement($id)->setValue($value);
        if( $required ) {
            $element->setRequired(true);
        }
    }  
    
}
?>