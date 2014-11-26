<?php
class TrailsEnvironment extends Environment
{
    function get_bootstrap ()
    {
        return <<<CODE
    require_once 'vendor/trails/trails.php';
    require_once 'app/controllers/studip_controller.php';
#   require_once 'app/controllers/authenticated_controller.php';
CODE;
    }

    function get_main_function_template ()
    {
        return <<<CODE
    public function perform(\$unconsumed_path)
    {
        \$this->setupAutoload();
        \$dispatcher = new Trails_Dispatcher(
            \$this->getPluginPath(),
            rtrim(PluginEngine::getLink(\$this, array(), null), '/'),
            'show'
        );
        \$dispatcher->plugin = \$this;
        \$dispatcher->dispatch(\$unconsumed_path);
    }

    private function setupAutoload()
    {
        if (class_exists('StudipAutoloader')) {
            StudipAutoloader::addAutoloadPath(__DIR__ . '/models');
        } else {
            spl_autoload_register(function (\$class) {
                include_once __DIR__ . \$class . '.php';
            });
        }
    }
CODE;
    }
}
