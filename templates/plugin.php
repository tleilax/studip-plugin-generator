<?="<?php\n"?>

/**
 * <?= $data['pluginclassname'] ?>.class.php
 *
 * <?= ($data['description'] ?: '...') . "\n" ?>
 *
 * @author  <?= $data['author'] . "\n" ?>
 * @version <?= $data['version'] . "\n" ?>
 **/

class <?= $data['pluginclassname'] ?> extends StudIPPlugin implements <?= implode(', ', $data['interfaces']) . "\n" ?>
{

    public function __construct()
    {
        parent::__construct();
<? foreach ($navigation as $index => $item): ?>

        $navigation = new AutoNavigation(_('<?= $item['title'] ?>'));
        $navigation->setURL(PluginEngine::GetLink($this, array(), '<?= $item['method'] ?>'));
<? if ($index === 0 and @$assets['icon']): ?>
        $navigation->setImage($this->getPluginURL() . '/<?= $assets['icon'] ?>');
<? elseif (!$index): ?>
        $navigation->setImage(Assets::image_path('blank.gif'));
<? endif; ?>
        Navigation::addItem('<?= $item['path'] ?>', $navigation);
<? endforeach; ?>
<? if ($data['assets'] === 'global' and ($data['css'] or $data['js'])): ?>

<? if ($data['css']): ?>
        PageLayout::addStylesheet($this->getPluginURL() . '/<?= $data['css'] ?>');
<? endif; ?>
<? if ($data['js']): ?>
        PageLayout::addScript($this->getPluginURL() . '/<?= $data['js'] ?>');
<? endif; ?>
<? endif; ?>
<?= $environment->constructor() ?>
    }
<? $env_init = $environment->initialize(); ?>
<? if (($data['assets'] === 'local' and ($data['css'] or $data['js'])) or $env_init): ?>

    public function initialize ()
    {
<?= ''//implode("\n", array_collect($environments, 'get_initialize_template')) ?>
<? if ($data['assets'] === 'local' and $data['css']): ?>
        PageLayout::addStylesheet($this->getPluginURL() . '/<?= $data['css'] ?>');
<? endif; ?>
<? if ($data['assets'] === 'local' and $data['js']): ?>
        PageLayout::addScript($this->getPluginURL() . '/<?= $data['js'] ?>');
<? endif; ?>
<?= $env_init ?>
    }
<? endif; ?>
<? if (in_array('HomepagePlugin', $data['interfaces'])): ?>

    public function getHomepageTemplate($user_id)
    {
        // ...
    }
<? endif; ?>
<? if (in_array('PortalPlugin', $data['interfaces'])): ?>

    public function getPortalTemplate()
    {
        // ...
    }
<? endif; ?>
<? if (in_array('StandardPlugin', $data['interfaces'])): ?>

    public function getIconNavigation($course_id, $last_visit)
    {
        // ...
    }

    public function getInfoTemplate($course_id)
    {
        // ...
    }
<? endif; ?>
<? if (in_array('StudienmodulManagementPlugin', $data['interfaces'])): ?>

    public function getModuleTitle($module_id, $semester_id = null)
    {
        // ...
    }

    public function getModuleDescription($module_id, $semester_id = null)
    {
        // ...
    }

    public function getModuleInfoNavigation($module_id, $semester_id = null)
    {
        // ...
    }
<? endif; ?>
<?= $environment->plugin() ?>
}
