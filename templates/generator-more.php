<fieldset data-id="options">
	<legend>
		<img src="assets/images/admin.png" alt="">
		Weitere Angaben
	</legend>

	<div class="select">
		<label for="studipMaxVersion">
			<span title="Manifest: studipMaxVersion">
				Maximale Stud.IP-Version
			</span>
			<dfn>
				Angabe der maximalen Stud.IP-Version, mit der dieses
				Plugin noch lauffähig ist. Versucht man, es in einer
				neueren Version zu installieren, erhält man eine
				entsprechende Fehlermeldung und die Installation schlägt
				fehlt.
			</dfn>
		</label>
		<select name="plugin[studipMaxVersion]" id="studipMaxVersion">
			<option value="">- keine Angabe -</option>
		<?php foreach ($versions as $v): ?>
			<option <?= selected('studipMaxVersion', $v) ?>><?= $v ?></option>
		<?php endforeach; ?>
		</select>
	</div>

	<div class="text">
		<label for="description">
			<span title="Manifest: description">Beschreibung</span>
			<dfn>
				Eine Kurzbeschreibung des Plugins. Diese wird im System
				den Nutzern angezeigt, wenn das Plugin zur Aktivierung
				angeboten wird.<br>
				<strong>Zu beachten:</strong> Es sind keine Umbrüche
				erlaubt.
			</dfn>
		</label>
		<textarea name="plugin[description]" id="description"><?= @$description ?></textarea>
	</div>

	<div class="text">
		<label for="homepage">
			<span title="Manifest: homepage">Homepage</span>
			<dfn>
				Eine URL zur Homepage des Plugins, die weitere
				Informationen über dieses Plugin enthält. Das kann z.B.
				die entsprechende Seite des Plugins im offiziellen
				Plugin-Repository von Stud.IP sein.
			</dfn>
		</label>
		<input type="text" name="plugin[homepage]" id="homepage" value="<?= @$homepage ?>">
	</div>

	<div class="text">
		<label for="updateURL">
			<span title="Manifest: updateURL">Update-URL</span>
			<dfn>
				Verweis auf eine URL mit Update-Informationen zu diesem
				Plugin. Wenn dieser Eintrag vorhanden ist, kann das
				Plugin über die Update-Funktion der Plugin-Verwaltung
				aktualisiert werden.<br>
				<a href="http://docs.studip.de/develop/Entwickler/PluginSchnittstelle#toc4">Weitere Informationen</a>
			</dfn>
		</label>
		<input type="text" name="plugin[updateURL]" id="updateURL" value="<?= @$updateURL ?>">
	</div>

	<div class="text">
		<label for="tab">Tabweite</label>
		<input type="number" name="plugin[tab]" id="tab" value="<?= @$tab ?>">
	</div>
</fieldset>
