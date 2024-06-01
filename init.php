<?php

use Siberian\Assets;
use Siberian\Translation;
use Siberian_Module as Module;

$init = function($bootstrap) {
    Assets::registerScss([
        '/app/local/modules/Catalogpro/features/catalogpro/scss/catalogpro.scss'
    ]);
    Translation::registerExtractor(
        'catalogpro',
        'Catalogpro',
        '/app/local/modules/Catalogpro/resources/translations/default/catalogpro.po');
    
};

