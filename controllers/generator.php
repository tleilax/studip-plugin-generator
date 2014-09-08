<?php
	return array_merge(array(
		'environment' => request('environment', 'trails'),
		'environments' => array(
			'default' => 'Standard',
			'trails'  => 'Trails',
		),
		'interfaces' => array(
			'HomepagePlugin'               => 'Homepage eines Nutzers',
			'PortalPlugin'                 => 'Startseite (Portalseite)',
			'StandardPlugin'               => 'Veranstaltungen und Einrichtungen',
			'StudienmodulManagementPlugin' => 'Studienmodulsuche',
			'SystemPlugin'                 => 'Systemweite Erweiterungen',
		),
		'versions'   => array('2.0', '2.1', '2.2', '2.3', '2.4', '2.5','3.0','3.1', '3.2'),
	), request('plugin', array(
		'assets'                    => '',
		'css'                       => 'assets/style.css',
		'css_content'               => '/* CSS */ ',
		'dbscheme_content'          => '-- SQL',
		'interface'                 => array('SystemPlugin'),
		'js'                        => 'assets/application.js',
		'js_content'                => "(function ($) {\n\n\t// JS\n\n}(jQuery));",
		'studipMinVersion'          => '2.4',
		'tab'                       => 4,
		'uninstalldbscheme_content' => '-- SQL',
		'version'                   => '0.1a',
	)));
