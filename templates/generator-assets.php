<fieldset data-id="assets">
	<legend>
		<img src="assets/images/files.png" alt="">
		Zusätzliche Dateien
	</legend>
	
	<div class="text">
		<label for="migration">
			Name der 1. Migration
			<dfn>
				Wird dieses Feld ausgefüllt, so wird eine Migration mit dem
				angegebenen Namen im <em>migrations</em>-Ordner erstellt.
			</dfn>
		</label>
		<input type="text" name="plugin[migration]" id="migration" value="<?= @$migration ?>">
	</div>

	<div class="select">
		<label for="environment">
			Umgebung
			<dfn>
				Je nach Umgebung werden andere Einstellungen im Plugin
				vorgenommen und andere Dateien eingebunden.<br>
				<span class="light">
					<strong>TODO</strong>: Hier könnte durchaus noch viel mehr Text hin.
				</span>
			</dfn>
		</label>
		<select name="environment" id="environment">
		<?php foreach ($environments as $k => $v): ?>
			<option value="<?= $k ?>" <?= selected(@$environment, $k) ?>>
				<?= $v ?>
			</option>
		<?php endforeach; ?>
		</select>
	</div>

	<div class="text">
		<label for="dbscheme">
			<span title="Manifest: dbscheme">Installations-SQL</span>
			<dfn>
				Verweis auf eine Datei mit einem SQL-Skript, die sich
				innerhalb des Plugin-Pakets befindet. Dieses wird bei der
				Installation des Plugins ausgeführt (nicht aber bei
				Updates). Hier können Tabellen und Tabelleninhalte
				angelegt werden, die das Plugin benötigt. Der Dateiname
				ist dabei relativ anzugeben.
			</dfn>
		</label>
		<input type="text" name="plugin[dbscheme]" id="dbscheme" value="<?= @$dbscheme ?>">
	</div>

	<div class="text">
		<label for="dbscheme-content">Inhalt</label>
		<textarea id="dbscheme-content" name="plugin[dbscheme_content]"><?= $dbscheme_content ?></textarea>
	</div>

	<div class="text">
		<label for="uninstalldbscheme">
			<span title="Manifest: uninstalldbscheme">
				Deinstallations-SQL
			</span>
			<dfn>
				Verweis auf eine Datei mit einem SQL-Skript, die sich
				innerhalb des Plugin-Pakets befindet. Dieses wird
				unmittelbar vor dem Entfernen des Plugins ausgeführt.
				Hier können Tabellen und Tabelleninhalte wieder gelöscht
				werden, die das Plugin angelegt hat. Der Dateiname ist
				dabei relativ anzugeben.
			</dfn>
		</label>
		<input type="text" name="plugin[uninstalldbscheme]" id="uninstalldbscheme" value="<?= @$uninstalldbscheme ?>">
	</div>

	<div class="text">
		<label for="uninstalldbscheme-content">Inhalt</label>
		<textarea id="uninstalldbscheme-content" name="plugin[uninstalldbscheme_content]"><?= @$uninstalldbscheme_content ?></textarea>
	</div>

	<div class="text">
		<label for="css">
			<span>CSS</span>
			<dfn>
				Verweis auf eine Datei mit CSS-Inhalten. Diese Datei
				wird im Plugin-Ordner automatisch erzeugt.
			</dfn>
		</label>
		<input type="text" id="css" name="plugin[css]" value="<?= @$css ?>">
	</div>

	<div class="text">
		<label for="css-content">Inhalt</label>
		<textarea id="css-content" name="plugin[css_content]"><?= $css_content ?></textarea>
	</div>

	<div class="text">
		<label for="js">
			<span>Javascript</span>
			<dfn>
				Verweis auf eine Datei mit Javascript-Inhalten. Diese Datei
				wird im Plugin-Ordner automatisch erzeugt.
			</dfn>
		</label>
		<input type="text" id="js" name="plugin[js]" value="<?= @$js ?>">
	</div>

	<div class="text">
		<label for="js-content">Inhalt</label>
		<textarea id="js-content" name="plugin[js_content]"><?= $js_content ?></textarea>
	</div>

	<div class="checkbox">
		<label>
			CSS + Javascript laden
			<dfn>
				Das Plugin kann so erstellt werden, dass die oben
				angegeben CSS- und/oder JS-Dateien automatisch auf allen
				Seiten bzw. nur auf den Pluginseiten geladen werden.
			</dfn>
		</label>

		<label>
			<input type="radio" name="plugin[assets]" value="" <?= checked(@$assets, '') ?>>
			Nein
		</label>

		<label>
			<input type="radio" name="plugin[assets]" value="global" <?= checked(@$assets, 'global') ?>>
			Global (auf allen Seiten vorhanden)
		</label>

		<label>
			<input type="radio" name="plugin[assets]" value="local" <?= checked(@$assets, 'local') ?>>
			Lokal (nur auf Pluginseiten vorhanden)
		</label>
	</div>
	
</fieldset>