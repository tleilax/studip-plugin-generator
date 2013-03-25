<fieldset data-id="base">
	<legend>
		<img src="assets/images/home.png" alt="">
		Grunddaten
	</legend>

	<div class="text">
		<label for="name">
			<span title="Manifest: pluginname">Name</span>
			<dfn>
				Der Name des Plugins.
			</dfn>
		</label>
		<input required type="text" name="plugin[pluginname]" id="name" value="<?= @$pluginname ?>">
	</div>

	<div class="text">
		<label for="author">
			Autor
			<dfn>
				Der Autor des Plugins. Dieser Wert wird in das Manifest
				des Plugins übernommen.
			</dfn>
		</label>
		<input required type="text" name="plugin[author]" id="author" value="<?= @$author ?>" placeholder="John Doe <john.doe@example.org>">
	</div>

	<div class="text">
		<label for="origin">
			<span title="Manifest: origin">Herkunft</span>
			<dfn>
				Der Ursprung des Plugins, üblicherweise der Name des
				Programmierers oder der Institution oder Gruppe, zu der
				dieser gehört.
			</dfn>
		</label>
		<input required type="text" name="plugin[origin]" id="origin" value="<?= @$origin ?>">
	</div>

	<div class="select">
		<label for="studipMinVersion">
			<span title="Manifest: studipMinVersion">
				Benötigte Stud.IP-Version
			</span>
			<dfn>
				Angabe der minimalen Stud.IP-Version, mit der dieses
				Plugin kompatibel ist. Versucht man, es in einer älteren
				Version zu installieren, erhält man eine entsprechende
				Fehlermeldung und die Installation schlägt fehlt.
			</dfn>
		</label>
		<select required name="plugin[studipMinVersion]" id="studipMinVersion">
		<?php foreach ($versions as $v): ?>
			<option <?= selected(@$studipMinVersion, $v) ?>>
				<?= $v ?>
			</option>
		<?php endforeach; ?>
		</select>
	</div>

	<div class="text">
		<label for="pluginclassname">
			<span title="Manifest: pluginclassname">Klassenname</span>
			<dfn>
				Der Name der Plugin-Klasse, also der PHP-Klasse, die von
				einer der Basisklassen der Pluginschnittstelle abgeleitet
				wurde. Im Manifest dürfen mehrere solcher Plugin-Klassen
				angegeben werden, wobei die "Hauptklasse" als erste
				aufgeführt werden muß. Dies dient dazu, in einem
				Plugin-Paket mehrere Plugin-Einstiegspunkte zu definieren,
				z.B. könnte das Plugin einen Einstiegspunkt über die
				Startseite und auch über die Veranstaltungen definieren.
			</dfn>
		</label>
		<input required type="text" name="plugin[pluginclassname]" id="pluginclassname" value="<?= @$pluginclassname ?>">
	</div>

	<div class="text">
		<label for="version">
			<span title="Manifest: version">Version</span>
			<dfn>
				Die Version des Plugins. Die Version sollte so gewählt
				werden, daß ein Vergleich mit der PHP-Funktion
				<a href="http://php.net/version_compare">version_compare()</a>
				sinnvoll möglich ist.
			</dfn>
		</label>
		<input required type="text" name="plugin[version]" id="version" value="<?= @$version ?>">
	</div>

	<div class="checkbox">
		<label>
			Plugin-Interfaces
			<dfn>
				Um an bestimmten Stellen in Stud.IP aktiv werden zu
				können, muss ein Plugin noch eines oder mehrere der
				Plugin-Interfaces implementieren.<br>
				<a href="http://docs.studip.de/develop/Entwickler/PluginSchnittstelle#toc9">Weitere Informationen</a>
			</dfn>
		</label>

	<?php foreach ($interfaces as $k => $v): ?>
		<label>
			<input type="checkbox" name="plugin[interface][]" value="<?= $k ?>" <?= checked(@$interface, $k) ?>>
			<?= $k ?>
			<span class="light">- <?= $v ?></span>
		</label>
	<?php endforeach; ?>
	</div>
</fieldset>
