<?php
	if (!class_exists('ZipArchive')) {
		die('ZipArchive is required but not available.');
	}

	require 'config.php';

	date_default_timezone_set(@date_default_timezone_get());

	require 'classes/Generator.class.php';
	require 'classes/Environment.class.php';
	require 'includes/functions.php';
