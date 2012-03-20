<fieldset data-id="polyfill">
    <legend>
        <?= Assets::img('icons/16/black/doit', array('class' => 'text-top')) ?>
        <?= _('Kompatibilität') ?>
    </legend>

<? foreach ($polyfills as $version => $fills): ?>
    <h2>Stud.IP &lt; <?= $version ?></h2>
    
    <? foreach ($fills as $name => $polyfill): ?>
    <div class="checkbox">
        <label>
            <input type="checkbox" name="polyfills[<?= $name ?>]" value="1"
                   <? if ($plugin['polyfills'][$name]) echo 'checked'; ?>>
            <?= htmlReady($polyfill['name']) ?>
        </label>
    </div>
    <? endforeach; ?>
<? endforeach; ?>
</fieldset>
