<fieldset data-id="base">
    <legend>
        <?= Assets::img('icons/16/black/home.png', array('class' => 'text-top')) ?>
        Grunddaten
    </legend>

    <div class="text">
        <label for="pluginname">
            <span title="Manifest: pluginname">Name</span>
            <dfn>
                Der Name des Plugins.
            </dfn>
        </label>
        <input required="required" type="text" name="pluginname" id="pluginname" 
               value="<?= $plugin['pluginname'] ?>">
    </div>

    <div class="text">
        <label for="author">
            Autor
            <dfn>
                Der Autor des Plugins. Dieser Wert wird in das Manifest
                des Plugins übernommen.
            </dfn>
        </label>
        <input type="text" name="author" id="author" 
               value="<?= $plugin['author'] ?>"
               placeholder="John Doe <john.doe@example.org>">
    </div>

    <div class="text">
        <label for="origin">
            <span title="Manifest: origin">Herkunft</span>
            <dfn>
                Der Ursprung des Plugins, üblicherweise der Name des
                Programmierers oder der Institution bzw. Gruppe, zu der
                dieser gehört.
            </dfn>
        </label>
        <input type="text" name="origin" id="origin"
               value="<?= $plugin['origin'] ?>">
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
        <select name="studipMinVersion" id="studipMinVersion">
        <? foreach ($versions as $version): ?>
            <option <? if ($plugin['studipMinVersion'] == $version) echo 'selected'; ?>>
                <?= $version ?>
            </option>
        <? endforeach; ?>
        </select>
    </div>

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
        <select name="studipMaxVersion" id="studipMaxVersion">
            <option value="">- <?= _('Keine Angabe') ?> -</option>
        <?php foreach ($versions as $version): ?>
            <option <? if ($plugin['studipMaxVersion'] == $version) echo 'selected'; ?>>
                <?= $version ?>
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
        <input type="text" name="pluginclassname" id="pluginclassname"
               value="<?= $plugin['pluginclassname'] ?>">
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
        <input type="text" name="version" id="version"
               value="<?= $plugin['version'] ?>">
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

    <? foreach ($interfaces as $interface => $description): ?>
        <label>
            <input type="checkbox" name="interfaces[]" value="<?= $interface ?>"
                   <? if (in_array($interface, $plugin['interfaces'])) echo 'checked'; ?>>
            <?= htmlReady($interface) ?>
            <span class="light">- <?= $description ?></span>
        </label>
    <?php endforeach; ?>
    </div>
</fieldset>
