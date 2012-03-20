<?
use Studip\Button, Studip\LinkButton;
$steps = words('manifest more assets navigation icon polyfill');
?>
<form action="<?= $controller->url_for('generator/' . $step) ?>" method="post"
      class="generator" enctype="multipart/form-data" data-step="<?= $step ?>">

    <input type="hidden" name="step" value="<?= $step ?>">

    <?= $this->render_partial('generator/' . $step) ?>

    <div>
        <div class="button-group" style="vertical-align: middle;">
            <?= Button::create(_('Zurück'), 'back', $previous === false ? array('disabled' => '') : array('value' => $previous)) ?>
            <?= Button::create(_('Weiter'), 'forward', $next === false ? array('disabled' => '') : array('value' => $next)) ?>
        </div>
        
        <div style="float: right;vertical-align: middle;">
            <div class="button-group" style="vertical-align: middle;">
                <?= Button::create(_('Anzeigen'), 'action', array('value' => 'display')) ?>
                <?= Button::create(_('Herunterladen'), 'action', array('value' => 'download')) ?>
                <?= Button::create(_('Installieren'), 'action', array('value' => 'install')) ?>
            </div>

            <?= LinkButton::createCancel(_('Zurücksetzen'), $controller->url_for('generator/reset')) ?>
        </div>
    </div>
</form>
