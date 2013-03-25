<form action="<?= $_SERVER['PHP_SELF'] ?>?action=generate" method="post"
	class="collapsable generator" enctype="multipart/form-data">

<?php include 'generator-base.php'; ?>
<?php include 'generator-more.php'; ?>
<?php include 'generator-assets.php'; ?>
<?php include 'generator-icon.php'; ?>
<?php #include 'generator-navigation.php'; ?>

	<fieldset class="buttons">
		<button type="submit">
			<img src="assets/images/erstellen-group-button.png" alt="Generieren" width="93" height="21">
		</button>
		<button type="reset">
			<img src="assets/images/abbrechen-button.png" alt="Abbrechen" width="93" height="21">
		</button>
	</fieldset>
</form>
