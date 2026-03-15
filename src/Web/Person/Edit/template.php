<?php

use App\Web\Person\Edit\Form;
use Yiisoft\Bootstrap5\BreadcrumbLink;
use Yiisoft\Bootstrap5\Breadcrumbs;
use Yiisoft\Bootstrap5\Button;
use Yiisoft\Bootstrap5\ButtonVariant;
use Yiisoft\FormModel\Field;
use Yiisoft\Html\Html;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Yii\View\Renderer\Csrf;

/**
 * @var Form $form
 * @var string[] $errors
 * @var UrlGeneratorInterface $urlGenerator
 * @var Csrf $csrf
 * @var string $id
 */

$creating = $id === 'new';
$action = $creating ? 'Create' : 'Edit';

$adminUrl = $urlGenerator->generate('person/admin');

$breadcrumbLinks = [
    BreadcrumbLink::to('Person Admin', $adminUrl),
];

$htmlForm = Html::form()
    ->post($urlGenerator->generate('person/edit', ['id' => $id]))
    ->csrf($csrf);

$this->setTitle("{$applicationParams->name} - {$action} Person");

if (!$creating) {
    $breadcrumbLinks[] = BreadcrumbLink::to($form->name);
}

end($breadcrumbLinks)->active(true);

echo Breadcrumbs::widget()
    ->links(...$breadcrumbLinks);
?>

<?= $htmlForm->open() ?>
    <?= Field::text($form, 'name')->required() ?>
    <?= Button::link('Cancel', $creating ? $adminUrl : $urlGenerator->generate('person/view', ['id' => $id])) ?>
    <?= Button::submit('Save')->variant(ButtonVariant::PRIMARY) ?>
<?= $htmlForm->close() ?>
