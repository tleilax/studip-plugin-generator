<?php
class DefaultEnvironment extends Environment
{
    function get_constructor_template ()
    {
        return <<<CODE

        \$this->template_factory = new Flexi_TemplateFactory(\$this->getPluginPath().'/templates');
CODE;
    }
    
    function get_main_function_template ()
    {
        return <<<CODE
    public function show_action ()
    {
        \$template = \$this->template_factory->open('show');
        \$template->set_attribute('answer', 'Yes');
        \$template->set_layout(\$GLOBALS['template_factory']->open('layouts/base_without_infobox'));
        echo \$template->render();
    }
CODE;
    }
}
