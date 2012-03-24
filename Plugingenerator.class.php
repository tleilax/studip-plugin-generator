<?php
require 'bootstrap.php';

/**
 * Plugingenerator.class.php
 *
 * ...
 *
 * @author  Jan-Hendrik Willms <tleilax+studip@gmail.com>
 * @version 0.7
 **/
class Plugingenerator extends StudIPPlugin implements SystemPlugin
{
    function __construct()
    {
        parent::__construct();

        require_once 'polyfills/Button.php';
        require_once 'polyfills/LinkButton.php';
        PageLayout::addStylesheet($this->getPluginURL() . '/polyfills/buttons.css');

        $navigation = new AutoNavigation(_('Plugin-Generator'));
        $navigation->setURL(PluginEngine::GetLink($this, array(), ''));
        $navigation->setImage(Assets::image_path('icons/16/black/plugin.png'));
        Navigation::addItem('/tools/plugingenerator', $navigation);

/*/
        $navigation = new AutoNavigation(_('Plugin-Generator'));
        $navigation->setURL(PluginEngine::GetLink($this, array(), ''));
        Navigation::addItem('/plugingenerator/index', $navigation);

        $navigation = new AutoNavigation(_('Tools'));
        $navigation->setURL(PluginEngine::GetLink($this, array(), 'tools'));
        Navigation::addItem('/plugingenerator/tools', $navigation);
//*/
    }

    function initialize ()
    {
        $styles = $this->combineAssets('plugingenerator', words('form buttons tooltip generator styles'), '.css');
        PageLayout::addStylesheet($styles);

        $scripts = $this->combineAssets('plugingenerator', words('form-protect generator application'), '.js');
        PageLayout::addScript($scripts);
    }

    function perform($unconsumed_path)
    {
        $dispatcher = new Trails_Dispatcher(
            $this->getPluginPath() . '/app',
            rtrim(PluginEngine::getLink($this, array(), null), '/'),
            'generator'
        );
        $dispatcher->plugin = $this;
        $dispatcher->dispatch($unconsumed_path);
    }

    private function combineAssets($target, $sources = array(), $extension = '', $path = 'assets/')
    {
        $target = $path . $target . $extension;

        if (Request::int('reset') or !file_exists($this->getPluginPath() . '/' . $target)) {
            $combined = '';
            foreach ($sources as $source) {
                $file = $this->getPluginPath() . '/' . $path . $source . $extension;
                $asset = file_get_contents($file);
                $asset = trim($asset) . "\n";
                $asset = preg_replace('/assets\/images\/([^)]+)\.png/ex', 'Assets::image_path("$1")', $asset);

                $combined .= $asset;
            }

            file_put_contents($this->getPluginPath() . '/' . $target, $combined);
        }
        return $this->getPluginURL() . '/' . $target;
    }

    // TODO include this in plugins
    private function includePolyfills($target_version = null)
    {
        if ($target_version === null) {
            $target_version = $GLOBALS['SOFTWARE_VERSION'];
        }

        if ($target_version < '2.1') {
            require_once 'polyfills/CSRFProtection.php';
            require_once 'polyfills/SkipLinks.php';
        }

        if ($target_version < '2.2') {
            require_once 'polyfills/UpdateInformation.php';
        }

        if ($target_version < '2.3') {
            require_once 'polyfills/Button.php';
            require_once 'polyfills/LinkButton.php';
            PageLayout::addStylesheet($this->getPluginURL() . '/polyfills/buttons.css');
        }
    }
}
