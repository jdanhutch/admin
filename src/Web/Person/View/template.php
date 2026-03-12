<?php

use App\Web\Shared\ActiveRecord\Person;
use Yiisoft\Bootstrap5\BreadcrumbLink;
use Yiisoft\Bootstrap5\Breadcrumbs;
use Yiisoft\Bootstrap5\Button;
use Yiisoft\Bootstrap5\ButtonVariant;
use Yiisoft\Html\Html;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Yii\View\Renderer\Csrf;

/** @var WebView $this */
/** @var Person $person */
/** @var UrlGeneratorInterface $urlGenerator */
/** @var Csrf $csrf */

$this->setTitle("{$applicationParams->name} - {$person->name}");

echo Breadcrumbs::widget()
    ->links(
        BreadcrumbLink::to('Person Admin', $urlGenerator->generate('person/admin')),
        BreadcrumbLink::to($person->name, active: true),
    );
?>

<h1>
    <?= Html::encode($person->name) ?>
</h1>

<?= Button::link('Edit', $urlGenerator->generate('person/edit', ['id' => $person->getId()])) ?>

<?php
$deleteForm = Html::form()
    ->post($urlGenerator->generate('person/delete', ['id' => $person->getId()]))
    ->csrf($csrf);
?>
<?= $deleteForm->open() ?>
    <?= Button::submit('Delete')->variant(ButtonVariant::DANGER) ?>
<?= $deleteForm->close() ?>
