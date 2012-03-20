<?php
class DefaultEnvironment extends Environment
{
    function constructor()
    {
        return '
        $this->template_factory = new Flexi_TemplateFactory($this->getPluginPath() . \'/templates\');
';
    }

    function plugin()
    {
        $result = '';

        foreach ($this->generator->getControllers() as $controller => $actions) {
            foreach (array_keys($actions) as $action) {
                if ($controller != $this->generator->getDefaultController()) {
                    $action = $navigation['controller'] . '_' . $action;
                }

                $result .= '
    public function ' . $action . '_action()
    {
        $template = $this->template_factory->open(\'' . $action . '\');
        $template->set_layout($GLOBALS[\'template_factory\']->open(\'layouts/base\'));

        $template->answer = \'Yes\';

        echo $template->render();
    }
';
                $this->generator->addFile('templates/' . $action . '.php', 'Does it work? <strong><?= htmlReady($answer) ?></strong>');
            }
        }

        return $result;
    }
}