<?php
class TrailsEnvironment extends Environment
{
    private function getDefaultController()
    {
        return strtolower($this->generator->data['pluginclassname']);
    } 
    
    function initialize()
    {
        return "        require_once 'vendor/trails/trails.php';
        require_once 'app/controllers/studip_controller.php';
        // require_once 'app/controllers/authenticated_controller.php';

";
    }

    function plugin()
    {
        return '
    public function perform($unconsumed_path)
    {
        $dispatcher = new Trails_Dispatcher(
            $this->getPluginPath() . \'/app\',
            rtrim(PluginEngine::getLink($this, array(), null), \'/\'),
            \'' . $this->getDefaultController() . '\'
        );
        $dispatcher->plugin = $this;
        $dispatcher->dispatch($unconsumed_path);
    }
';
    }

    function assets()
    {
        $result = array(
            'app/models/readme.txt' => 'Erase me and place your models here',
        );

        foreach ($this->generator->getControllers() as $controller => $actions) {
            $file = 'app/controllers/' . $controller . '.php';
            $content = $this->trails_controller(ucfirst(strtolower($controller)), array_keys($actions));
            $result[$file] = $content;

            foreach (array_keys($actions) as $view) {
                $file = 'app/views/' . $controller . '/' . $view . '.php';
                $content = '<h1>' . $controller . '::' . $view . '</h1>
<p>
    Does it work?
    <strong><?= htmlReady(isset($test) ? $test : \'no\') ?></strong>
</p>';
                $result[$file] = $content;
            }
        }
        
        return $result;
    }
    
    private function trails_controller($name, $actions = array())
    {
        $temp = '';
        foreach ($actions as $action) {
            $temp .= '
    function ' . $action . '_action()
    {
        $this->test = \'Yes\';
    }
';
        }
        
        
        return '<?php
class ' . $name . 'Controller extends StudipController
{
    public function before_filter(&$action, &$args)
    {
        parent::before_filter($action, $args);

        $GLOBALS[\'CURRENT_PAGE\'] = \'' . $this->generator->data['pluginname'] . '\';
        PageLayout::setTitle(_(\'' . $this->generator->data['pluginname'] . '\'));
        
        # $this->flash = Trails_Flash::instance();
        
        # set default layout
        $layout = $GLOBALS[\'template_factory\']->open(\'layouts/base\');
        $this->set_layout($layout);
    }

    // customized #url_for for plugins
    function url_for($to)
    {
        $args = func_get_args();

        # find params
        $params = array();
        if (is_array(end($args))) {
            $params = array_pop($args);
        }

        # urlencode all but the first argument
        $args = array_map("urlencode", $args);
        $args[0] = $to;

        return PluginEngine::getURL($this->dispatcher->plugin, $params, join("/", $args));
    }

' . $temp . '
}';
    }
}