<?php

use App\Web\Shared\ActiveRecord\Chore;
use Yiisoft\Bootstrap5\BreadcrumbLink;
use Yiisoft\Bootstrap5\Breadcrumbs;
use Yiisoft\Bootstrap5\Button;
use Yiisoft\Bootstrap5\ButtonVariant;
use Yiisoft\Html\Html;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Yii\View\Renderer\Csrf;

/** @var WebView $this */
/** @var Chore $chore */
/** @var UrlGeneratorInterface $urlGenerator */
/** @var Csrf $csrf */

$person = $chore->getPerson();

$this->setTitle("{$applicationParams->name} - {$chore->name}");

echo Breadcrumbs::widget()
    ->links(
        BreadcrumbLink::to('Chore Admin', $urlGenerator->generate('chore/admin')),
        BreadcrumbLink::to($chore->name, active: true),
    );
?>

<h1>
    <?= Html::encode($chore->name) ?>
</h1>

<?php if ($person): ?>
<div>A chore for <?= Html::a($person->name, $urlGenerator->generate('person/view', ['id' => $person->getId()])) ?></div>
<?php endif; ?>

<?= Button::link('Edit', $urlGenerator->generate('chore/edit', ['id' => $chore->getId()])) ?>

<?php
$deleteForm = Html::form()
    ->post($urlGenerator->generate('chore/delete', ['id' => $chore->getId()]))
    ->csrf($csrf);
?>
<?= $deleteForm->open() ?>
    <?= Button::submit('Delete')->variant(ButtonVariant::DANGER) ?>
<?= $deleteForm->close() ?>
