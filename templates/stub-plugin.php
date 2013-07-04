<?="<?php\n"?>
require 'bootstrap.php';

/**
 * <?= $pluginclassname ?>.class.php
 *
 * ...
 *
 * @author  <?= $author."\n" ?>
 * @version <?= $version."\n" ?>
 */

class <?= $pluginclassname ?> extends StudIPPlugin implements <?= implode(', ', $interface) ?> {

	public function __construct() {
		parent::__construct();

		$navigation = new AutoNavigation(_('<?= $pluginname ?>'));
		$navigation->setURL(PluginEngine::GetURL($this, array(), 'show'));
<?php if (@$icon): ?>
		$navigation->setImage($this->getPluginURL().'/<?= $icon ?>');
<?php else: ?>
		$navigation->setImage(Assets::image_path('blank.gif'));
<?php endif; ?>
		Navigation::addItem('/<?= str_replace('Plugin', '', strtolower($pluginclassname)) ?>', $navigation);
<?= implode("\n", array_collect($environments, 'get_constructor_template')) ?>
<?php if ($assets === 'global'): ?>

<?php if ($css): ?>
		PageLayout::addStylesheet($this->getPluginURL().'/<?= $css ?>');
<?php endif; ?>
<?php if ($js): ?>
		PageLayout::addScript($this->getPluginURL().'/<?= $js ?>');
<?php endif; ?>
<?php endif; ?>
	}

	public function initialize () {
<?= implode("\n", array_collect($environments, 'get_initialize_template')) ?>

<?php if ($assets === 'local'): ?>
	
<?php if ($css): ?>
		PageLayout::addStylesheet($this->getPluginURL().'/<?= $css ?>');
<?php endif; ?>
<?php if ($js): ?>
		PageLayout::addScript($this->getPluginURL().'/<?= $js ?>');
<?php endif; ?>
<?php endif; ?>
	}

<?php if (in_array('HomepagePlugin', $interface)): ?>
	public function getHomepageTemplate($user_id) {
		// ...
	}

<?php endif; ?>
<?php if (in_array('PortalPlugin', $interface)): ?>
	public function getPortalTemplate() {
		// ...
	}

<?php endif; ?>
<?php if (in_array('StandardPlugin', $interface)): ?>
<?php if ($studipMinVersion >= '2.4'): ?>
	public function getTabNavigation($course_id) {
		return array();
	}

	public function getNotificationObjects($course_id, $since, $user_id) {
		return array();
	}

	public function getIconNavigation($course_id, $last_visit, $user_id) {
<?php else: ?>
	public function getIconNavigation($course_id, $last_visit) {
<?php endif; ?>
		// ...
	}

	public function getInfoTemplate($course_id) {
		// ...
	}

<?php endif; ?>
<?php if (in_array('PortalPlugin', $interface)): ?>
	public function getModuleTitle($module_id, $semester_id = null) {
		// ...
	}

	public function getModuleDescription($module_id, $semester_id = null) {
		// ...
	}

	public function getModuleInfoNavigation($module_id, $semester_id = null) {
		// ...
	}

<?php endif; ?>
<?= implode("\n", array_collect($environments, 'get_main_function_template')) ?>

}
