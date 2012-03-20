<?
class ToolsController extends StudipController
{
    function before_filter(&$action, &$args)
    {
        parent::before_filter($action, $args);

        $this->set_layout($GLOBALS['template_factory']->open('layouts/base'));
        PageLayout::setTitle('Tools - Plugin-Generator');
        Navigation::activateItem('/plugingenerator/tools');
    }

    function index_action()
    {
    }
}