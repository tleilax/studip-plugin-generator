<fieldset data-id="icon">
    <legend>
        <?= Assets::img('icons/16/black/plus', array('class' => 'text-top')) ?>
        <?= _('Icon hinzufügen') ?>
    </legend>
    
    <div class="text">
        <label for="icon"><?= _('Icon-Datei') ?></label>
        <input type="file" name="file" id="file" accept="image/*">
    </div>
    
    <div class="checkbox">
        <label for="sprite">
            <?= _('Sprite erzeugen ') ?>
            <dfn>
                <?= _('TODO: Beschreiben') ?>
            </dfn>
        </label>
        <input type="checkbox" name="sprite" id="sprite" value="1"
               <? if ($plugin['sprite']) echo 'checked'; ?>>
    </div>
</fieldset>
