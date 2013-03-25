<?php
	if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
		redirect('index.php');
	}

	$generator = new Generator;
	$generator->addEnvironment(request('environment'));
	$generator->setVariables(request('plugin'));

	if (empty($_FILES['file']['error']) and $_FILES['file']['size'] > 0) {
		$name = 'assets/images/'.$_FILES['file']['name'];
		$content = file_get_contents($_FILES['file']['tmp_name']);
		if (request('create-icon')) {
			$generator->createIcon($name, $content);
		} else {
			$generator->addIcon($name, $content);
		}
	}

	$generator->addManifest();
	$generator->addBootstrap();
	$generator->addPluginClass();
	$generator->addAssets(array('css', 'js', 'dbscheme', 'uninstalldbscheme'));
	$generator->addMigration();
	$generator->addResources();

	$generator->close();
	$generator->output();

	exit; // prevent view from rendering