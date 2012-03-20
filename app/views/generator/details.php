<fieldset data-id="options">
    <legend>
        <?= Assets::img('icons/16/black/admin.png', array('class' => 'text-top')) ?>
        Weitere Angaben
    </legend>

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
        <textarea name="description" id="description"><?= $plugin['description'] ?></textarea>
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
        <input type="url" name="homepage" id="homepage"
               value="<?= $plugin['homepage'] ?>">
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
        <input type="url" name="updateURL" id="updateURL"
               value="<?= $plugin['updateURL'] ?>">
    </div>

    <div class="text">
        <label for="tab">Tabweite</label>
        <input type="number" name="tab" id="tab" value="<?= $plugin['tab'] ?>">
    </div>
</fieldset>
