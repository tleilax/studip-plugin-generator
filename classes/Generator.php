<?
/**
 * Generator.php
 *
 * Actual plugin generator
 *
 * @version 1.0
 * @uses    Flexi_TemplateFactory
 * @uses    Flexi_Template
 * @uses    Request
 **/
class Generator
{
    protected $template_factory;

    protected $environment;
    protected $environment_dir;

    protected $files = array();
    protected $assets = array();

    protected $controllers = array();

    public function __construct($app_dir, $template_dir, $environment_dir)
    {
        $this->template_factory = new Flexi_TemplateFactory($app_dir . $template_dir);
        $this->environment_dir  = $app_dir . $environment_dir;
    }

    public function getControllers()
    {
        return $this->controllers;
    }

    public function populate($data)
    {
        $this->data = $data;

        extract($data);

        $plugin = array(
            'manifest'   => compact(words('pluginname pluginclassname origin '
                                         .'version studipMinVersion studipMaxVersion '
                                         .'description homepage updateURL '
                                         .'dbscheme dbscheme_content '
                                         .'uninstalldbscheme uninstalldbscheme_content')),
            'details'    => compact(words('assets author interfaces tab_width')),
            'assets'     => compact(words('migration css css_content js js_content')),
            'navigation' => compact(words('navigation')),
            'icon'       => compact(words('file sprite')),
        );

        $this->setEnvironment($environment);
        $this->manifest();
        $this->navigation();
        $this->assets();
        $this->plugin();
    }

    function setEnvironment($environment)
    {
        $file  = sprintf('%s/%s.php', $this->environment_dir, strtolower($environment));
        $class = ucfirst(strtolower($environment)) . 'Environment';

        if (!file_exists($file)) {
            throw new RuntimeException('Environment file ' . $file . ' for environment "' . $environment . '" not found');
        }
        require $file;

        if (!class_exists($class)) {
            throw new RuntimeException('Invalid environment file "' . $environment . '"');
        }
        $this->environment = new $class($this);

        return $this; # allow chaining
    }

    function addFile($name, $content)
    {
        $this->files[$name] = $content;
    }

    function manifest($data = null)
    {
        if ($data === null) {
            $data = $this->data;
        }

        $variables = words('pluginname pluginclassname origin version '
                          .'description homepage studipMinVersion studipMaxVersion '
                          .'dbscheme uninstalldbscheme updateURL');

        $manifest = sprintf('# %s v%s by %s' . "\n", $data['pluginname'], $data['version'], $data['author']);
        foreach ($variables as $key) {
            if (!empty($data[$key])) {
                $manifest .= sprintf("%s=%s\n", $key, $data[$key]);
            }
        }
        $this->addFile('plugin.manifest', $manifest);

        return $this; # allow chaining
    }

    function getDefaultController()
    {
        return strtolower($this->data['pluginclassname']);
    }

    function navigation($navigation = null)
    {
        if ($navigation === null) {
            $navigation = $this->data['navigation'];
        }

        $this->navigation = array();
        foreach ($navigation as $item) {
            $path  = $item['path'];
            $title = $item['title'];

            # path, title, action
            $action = explode('/', trim($item['action'], '/'));

            if (count($action) < 2) {
                $controller = $this->getDefaultController();
                $method     = empty($action[0]) ? 'index' : $action[0];
            } else {
                $controller = array_shift($action[0]);
                $method     = implode('/', $action[1]);
            }
            $this->navigation[] = compact(words('path title controller method'));
        }

        # extract controllers and view from navigation
        foreach ($this->navigation as $item) {
            $controller = $item['controller'];
            $method     = $item['method'];

            if (!isset($this->controllers[$controller])) {
                $this->controllers[$controller] = array();
            }
            $this->controllers[$controller][$method] = $item;
        }

        return $this; # allow chaining
    }
# output

    function assets($data = null)
    {
        if ($data === null) {
            $data = $this->data;
        }
        
        $result = array();
        foreach (words('dbscheme uninstalldbscheme css js') as $asset) {
            if (!empty($this->data[$asset])) {
                $file    = $data[$asset];
                $content = isset($data[$asset . '_content']) ? $data[$asset . '_content'] : '';
                $this->addFile($file, $content);

                $this->assets[$asset] = $file;
            }
        }

        if (!empty($data['migration'])) {
            $class = implode(array_map('ucfirst', preg_split('/[^[:alnum:]]+/', $data['migration'])));

            $file  = 'migrations/1_' . implode('_', preg_split('/[^[:alnum:]]+/', strtolower($data['migration']))) . '.php';
            $content = $this->template_factory->render('migration', compact('class'));
            $this->addFile($file, $content);

            $this->assets[$asset] = $file;
        }

        if (!empty($data['file'])) {
            if ($data['sprite']) {
                $source = imagecreatefromstring($data['file']);
                $width = imagesx($source);
                $height = imagesy($source);

                $destination = imagecreatetruecolor($width * 4, $height);
                $alpha = imagecolorallocatealpha($destination, 0, 0, 0, 127);
                imagefilledrectangle($destination, 0, 0, $width * 4, $height, $alpha);
                imagealphablending($destination, false);
                imagesavealpha($destination, true);

                for ($y = 0; $y < $height; $y++) {
                    for ($x = 0; $x < $width; $x++) {
                        $pixel = imagecolorat($source, $x, $y);
                        #abb7ce light blue
                        $blue = imagecolorallocatealpha($destination, 0xab, 0xb7, 0xce, $pixel >> 24 & 0x7f);
                        for ($i = 0; $i < 4; $i++) {
                            imagesetpixel($destination, $x + $i * $width, $y, $i % 2 ? $pixel : $blue);
                        }
                    }
                }
                imagedestroy($source);

                # Add star
                $star = imagecreatefrompng(dirname(__FILE__) . '/../assets/images/star.png'); # TODO hardcoded = bad
                for ($y = 0; $y < 10; $y++) {
                    for ($x = 0; $x < 10; $x++) {
                        $pixel = imagecolorat($star, $x, $y);
                        if (($pixel >> 24 & 0x7f) !== 127) {
                            for ($i = 0; $i < 2; $i++) {
                                $u = $width * (2 + $i) + $width - 10 + $x;
                                imagesetpixel($destination, $u, $y, $pixel);
                            }
                        }
                    }
                }
                imagedestroy($star);

                ob_start();
                imagepng($destination);
                $data['file'] = ob_get_clean();

                imagedestroy($destination);
            }

            $this->addFile('assets/images/plugin.png', $data['file']);

            $this->assets['icon'] = 'assets/images/plugin.png';
        }

        // TODO Env
        $assets = $this->environment->assets();
        foreach ($assets as $name => $content) {
            $this->addFile($name, $content);
        }

        return $result; # allow chaining
    }

    function plugin()
    {
        $template = $this->template_factory->open('plugin');
        $template->assets      = $this->assets;
        $template->data        = $this->data;
        $template->environment = $this->environment;
        $template->navigation  = $this->navigation;
        $template->controllers = $this->controllers;
        $plugin = $template->render();

        $this->addFile($this->data['pluginclassname'] . '.php', $plugin);

        return $this; # allow chaining
    }

    function zip($return = false)
    {
        # zip file name
        $tmp = $GLOBALS['TMP_PATH'] . '/' . md5(uniqid('generated plugin', true));

        # create zip file
        $zip = new ZipArchive;
        if (true !== ($result = $zip->open($tmp, ZIPARCHIVE::CM_PKWARE_IMPLODE))) {
            throw new RuntimeException('Could not create temp file "' . $tmp . '", error #' . $result);
        }
        foreach ($this->files as $file => $content) {
            $zip->addFromString($file, $content);
        }
        $zip->close();

        if (!$return) {
            return $tmp;
        }

        $result = file_get_contents($tmp);
        unlink($tmp);
        return $result;
    }

    function download($filename = null)
    {
        # download name
        if ($filename === null) {
            $filename = sprintf('%s-%s.zip', $this->data['pluginclassname'], $this->data['version']);
        }

        # send file
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        echo $this->zip(true);

        # remove zip file
        unlink($tmp);

        return $this; # allow chaining
    }

    function install()
    {
        $zip = $this->zip();
        require_once 'app/models/plugin_administration.php';
        $plugin_admin = new PluginAdministration();
        $plugin_admin->installPlugin($zip);
        unlink($zip);

#        throw new RuntimeException('Urm, hello. I am a placeholder. Just go on, there is nothing to see, do or not do here. Just go away, click somewhere else. Please.');
    }

    function display()
    {
		$files = $this->files;
		require dirname(__FILE__) . '/../templates/display.php';
        die;
    }

//
    static function getDefaults()
    {
        $author = sprintf('%s <%s>',
                          get_fullname($GLOBALS['auth']->auth['uid']),
                          Helper::get_email($GLOBALS['auth']->auth['uid']));

        return array(
            'author'           => $author,
            'origin'           => $GLOBALS['STUDIP_INSTALLATION_ID'],
            'version'          => '0.1a',
            'interfaces'       => words('SystemPlugin'),

            'tab'              => 4,
            'environment'      => 'default',

            'navigation'       => array(array('path' => '/', 'title' => '', 'action' => '')),

            'css'              => 'assets/styles.css',
            'css_content'      => "/* CSS */\n",
            'dbscheme'         => 'sql/install.sql',
            'dbscheme_content' => "-- SQL\n",
            'js'               => 'assets/script.js',
            'js_content'       => "(function ($) {\n\n    // JS\n\n}(jQuery));\n",
            'uninstalldbscheme'         => 'sql/uninstall.sql',
            'uninstalldbscheme_content' => "-- SQL\n",
        );
    }
}
