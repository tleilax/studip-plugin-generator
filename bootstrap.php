<?php
    #global includes
    require_once 'vendor/trails/trails.php';
    require_once 'app/controllers/studip_controller.php';

    # local includes
    require 'app/controllers/plugin_generator_controller.php';

    function uol_pg_autoload($name)
    {
        @include dirname(__FILE__) . '/classes/' . $name . '.php';
    }
    spl_autoload_register('uol_pg_autoload');
    
    # Defaults
    Polyfill::define(dirname(__FILE__) . '/polyfills.json');
