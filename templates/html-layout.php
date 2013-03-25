<!DOCTYPE html>
<html>
<head>
	<title>Stud.IP Plugin Generator v<?= VERSION ?></title>
	<link rel="shortcut icon" href="assets/images/plugin.png" type="image/x-icon">
	<link rel="stylesheet" href="style-v1.css" type="text/css">
</head>
<body>
	<header>
		<a href="http://studip.de"><img src="assets/images/header_logo.png" alt="Stud.IP" width="203" height="44"></a>
		Plugin-Generator
	</header>
	<?= $CONTENT ?>
	<footer>
		version <?= VERSION ?> |
		written by <a href="mailto:tleilax+studip@gmail.com">Jan-Hendrik Willms</a> in 2011 as a <a href="http://develop.studip.de">developer</a> tool for <a href="http://studip.de">Stud.IP</a>
	</footer>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
	<script>window.jQuery || document.write("<script src='assets/vendor/jquery-1.7.1.min.js'>\x3C/script>")</script>
	<script defer src="script-v1.js"></script>
</body>
</html>
