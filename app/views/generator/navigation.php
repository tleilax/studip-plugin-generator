<? use Studip\Button, Studip\LinkButton;?>
<fieldset data-id="navigation">
    <legend>
        <?= Assets::img('icons/16/black/assessment', array('class' => 'text-top')) ?>
        <?= _('Navigation') ?>
    </legend>
    
    <table class="default navigation">
        <colgroup>
            <col width="20px">
            <col width="30%">
            <col width="30%">
            <col width="30%">
            <col>
        </colgroup>
        <thead>
            <tr>
                <th>&nbsp;</th>
                <th><?= _('Pfad') ?></th>
                <th><?= _('Titel') ?></th>
                <th><?= _('Aktion') ?></th>
                <th>
                    <button name="add">
                        <?= Assets::img('icons/16/blue/plus', array('alt' => _('Neu'))) ?>
                    </button>
                </th>
            </tr>
        </thead>
        <tbody>
        <? foreach ($plugin['navigation'] as $index => $item): ?>
            <tr class="<?= TextHelper::cycle('cycle_even', 'cycle_odd') ?>">
                <td>
                    <div class="move icon"></div>
                </td>
                <td><input type="text" name="navigation[<?= $index ?>][path]" value="<?= htmlReady($item['path']) ?>"></td>
                <td><input type="text" name="navigation[<?= $index ?>][title]" value="<?= htmlReady($item['title']) ?>"></td>
                <td><input type="text" name="navigation[<?= $index ?>][action]" value="<?= htmlReady($item['action']) ?>"></td>
                <td>
                    <button name="delete" value="<?= $index ?>">
                        <?= Assets::img('icons/16/blue/trash', array('alt' => _('Löschen'))) ?>
                    </button>
                </td>
            </tr>
        <? endforeach; ?>
        </tbody>
    </table>
</fieldset>