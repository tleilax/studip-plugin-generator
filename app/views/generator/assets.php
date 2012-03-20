<fieldset data-id="assets">
    <legend>
        <?= Assets::img('icons/16/black/files.png', array('class' => 'text-top')) ?>
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
        <input type="text" name="migration" id="migration"
               value="<?= $plugin['migration'] ?>">
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
        <?php foreach ($environments as $environment => $title): ?>
            <option value="<?= $environment ?>" <? if ($plugin['environment'] === $environment) echo 'selected'; ?>>
                <?= $title ?>
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
        <input type="text" name="dbscheme" id="dbscheme" value="<?= $plugin['dbscheme'] ?>">
    </div>

    <div class="text">
        <label for="dbscheme-content">Inhalt</label>
        <textarea id="dbscheme-content" name="dbscheme_content"><?= $plugin['dbscheme_content'] ?></textarea>
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
        <input type="text" name="uninstalldbscheme" id="uninstalldbscheme"
               value="<?= $plugin['uninstalldbscheme'] ?>">
    </div>

    <div class="text">
        <label for="uninstalldbscheme-content">Inhalt</label>
        <textarea id="uninstalldbscheme-content" name="uninstalldbscheme_content"><?= $plugin['uninstalldbscheme_content'] ?></textarea>
    </div>

    <div class="text">
        <label for="css">
            <span>CSS</span>
            <dfn>
                Verweis auf eine Datei mit CSS-Inhalten. Diese Datei
                wird im Plugin-Ordner automatisch erzeugt.
            </dfn>
        </label>
        <input type="text" id="css" name="css" value="<?= $plugin['css'] ?>">
    </div>

    <div class="text">
        <label for="css-content">Inhalt</label>
        <textarea id="css-content" name="css_content"><?= $plugin['css_content'] ?></textarea>
    </div>

    <div class="text">
        <label for="js">
            <span>Javascript</span>
            <dfn>
                Verweis auf eine Datei mit Javascript-Inhalten. Diese Datei
                wird im Plugin-Ordner automatisch erzeugt.
            </dfn>
        </label>
        <input type="text" id="js" name="js" value="<?= $plugin['js'] ?>">
    </div>

    <div class="text">
        <label for="js-content">Inhalt</label>
        <textarea id="js-content" name="js_content"><?= $plugin['js_content'] ?></textarea>
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
            <input type="radio" name="assets" value="" <? if (empty($plugin['assets'])) echo 'checked'; ?>>
            Nein
        </label>

        <label>
            <input type="radio" name="assets" value="global" <? if ($plugin['assets'] === 'global') echo 'checked'; ?>>
            Global (auf allen Seiten vorhanden)
        </label>

        <label>
            <input type="radio" name="assets" value="local" <? if ($plugin['assets'] === 'local') echo 'checked'; ?>>
            Lokal (nur auf Pluginseiten vorhanden)
        </label>
    </div>
    
</fieldset>