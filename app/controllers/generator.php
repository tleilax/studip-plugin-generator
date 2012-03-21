<?php
class GeneratorController extends PluginGeneratorController {

    function before_filter(&$action, &$args)
    {
        // Rewrite action?
        $this->actions = words('manifest details assets navigation icon polyfill');
        if (in_array($action, $this->actions)) {
            $args   = array($action);
            $action = 'index';
        }

        // Initialize plugin stub
        if (!isset($_SESSION['plugin-generator'])) {
            $author = sprintf('%s <%s>',
                              get_fullname($GLOBALS['auth']->auth['uid']),
                              Helper::get_email($GLOBALS['auth']->auth['uid']));

            $_SESSION['plugin-generator']['plugin'] = Generator::getDefaults();
            $_SESSION['plugin-generator']['passed'] = array_fill_keys(array_keys($this->actions), false);
        }
        $this->plugin = $_SESSION['plugin-generator']['plugin'];

        // Initialize controller
        parent::before_filter($action, $args);

        $this->set_layout($GLOBALS['template_factory']->open('layouts/base'));
        PageLayout::setTitle('Plugin-Generator');
        Navigation::activateItem('/tools/plugingenerator');
    }
    
    function after_filter($action, $args)
    {
        // Store plugin
        if (isset($this->plugin)) {
            $_SESSION['plugin-generator']['plugin'] = $this->plugin;
        }
    }

    /**
     * Extract plugin part from request
     *
     * @param string $step
     * @param array  $errors
     **/
    private function extract($step, &$errors = array())
    {
        $steps = array(
            'manifest'   => words('pluginname author origin studipMinVersion studipMaxVersion pluginclassname version interfaces'),
            'details'    => words('description homepage updateURL tab'),
            'assets'     => words('migration environment dbscheme dbscheme_content uninstalldbscheme uninstalldbscheme_content '
                                 .'css css_content js js_content assets'),
            'navigation' => words('navigation'),
            'icon'       => words('sprite'),
            'polyfill'   => words('polyfills'),
        );

        if (!isset($steps[$step])) {
            throw new Exception('Invalid step "' . $step . '" invoked');
        }

        $result = array();
        $errors = array();

        $request = Request::getInstance();

        foreach ($steps[$step] as $variable) {
            $result[$variable] = $request[$variable];
        }

        if ($step === 'manifest') {
            $result['interfaces'] = Request::optionArray('interfaces');

            if (!$result['pluginname']) {
                $errors['pluginname'] = _('Pluginname nicht angegeben');
            }
            if (!$result['author']) {
                $errors['author'] = _('Kein Autor angegeben');
            }
            if (!$result['origin']) {
                $errors['origin'] = _('Keine Herkunft angegeben');
            }
            if (!$result['pluginclassname']) {
                $errors['pluginclassname'] = _('Kein Klassenname für das Plugin angegeben');
            }
            if (!$result['version']) {
                $errors['version'] = _('Keine Version angegeben');
            }
            if (!$result['studipMinVersion']) {
                $errors['studipMinVersion'] = _('Keine minimale Stud.IP-Version angegeben');
            }
            if (empty($result['interfaces'])) {
                $errors['interfaces'] = _('Kein Interface ausgewählt');
            }
        } else if ($step === 'navigation') {
            $result['navigation'] = Request::getArray('navigation');
        } else if ($step === 'icon' and !empty($_FILES['file']['name'])) {
            $tmp_icon = $GLOBALS['TMP_PATH'] . '/' . md5(uniqid('plugin-icon', true));

            if (strpos($_FILES['file']['type'], 'image/') !== 0) {
                $errors['file'] = sprintf(_('Ungültiger Dateityp "%s"'), $_FILES['file']['type']);
            } else if ($_FILES['file']['error'] !== 0
                   or !move_uploaded_file($_FILES['file']['tmp_name'], $tmp_icon))
            {
                $errors['file'] = _('Fehler bei der Datenübertragung');
            } else {
                $content = file_get_contents($tmp_icon);
                unlink($tmp_icon);

                $result['file'] = $content;
            }
        }

        return $result;
    }

    function index_action($step = 'manifest')
    {
        $step = Request::option('step', $step);

        $this->previous = $this->next = $last = false;
        foreach ($this->actions as $action) {
            if ($last == $step) {
                $this->next = $action;
            }
            if ($action == $step) {
                $this->previous = $last;
            }
            $last = $action;
        }

        // if (Request::isPost()) {    # since Stud.IP 2.1
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->plugin = array_merge($this->plugin, $this->extract($step, $errors));
            $_SESSION['plugin-generator']['passed'][$step] = empty($errors);
        }

        if (!empty($errors)) {
            PageLayout::postMessage(Messagebox::error(_('Es sind Fehler aufgetreten:'), $errors));
        } elseif (Request::submitted('action')) {
            $action = Request::option('action');
            if (!in_array($action, array('display download install')))
            
            $generator = new Generator($this->dispatcher->plugin->getPluginPath(),
                                       '/templates', '/environments');
            $generator->populate($this->plugin);

            $generator->$action();
            if ($action === 'install') {
                $this->redirect(URLHelper::getURL('dispatch.php/admin/plugin'));
                return;
            }
#            PageLayout::postMessage(Messagebox::success('Jipp.'));
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (Request::submitted('back')) {
                $step = Request::option('back', $this->previous);
            } else if (Request::submitted('forward')) {
                $step = Request::option('forward', $this->next);
            }
            $this->redirect('generator/' . $step);
            return;
        }

        $this->step = $step;


        switch ($step) {
            case 'manifest':
                $this->interfaces = array(
                    'HomepagePlugin'               => _('Homepage eines Nutzers'),
                    'PortalPlugin'                 => _('Startseite (Portalseite)'),
                    'StandardPlugin'               => _('Veranstaltungen und Einrichtungen'),
                    'StudienmodulManagementPlugin' => _('Studienmodulsuche'),
                    'SystemPlugin'                 => _('Systemweite Erweiterungen'),
                );
                $this->versions = words('2.0 2.1 2.2 2.3 2.4');
                break;
            case 'assets':
                $this->environments = array(
                    'default' => _('Standard'),
                    'trails'  => _('Trails'),
                );
                break;
            case 'polyfill':
                $this->polyfills = Polyfill::getVersions();
                break;
        }

        $variables = array(
            'step'       => $step,
            'controller' => $this,
        );
        $progress = $this->get_template_factory()->render('infobox-route', $variables);
        $this->setInfoboxImage($this->dispatcher->plugin->getPluginURL() . '/assets/images/puzzle.jpg')
             ->addToInfobox(_('Fortschritt'), $progress)
             ->addToInfobox(_('Aktionen'),
                            sprintf('<a href="%s">Reset</a>', $this->url_for('generator/' . $step . '?reset=1')),
                            Assets::image_path('icons/16/black/refresh.png'));
    }

    function reset_action()
    {
        unset($this->plugin, $_SESSION['plugin-generator']);
        $this->redirect('generator');
    }
}
