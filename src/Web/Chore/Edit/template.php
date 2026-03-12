<?php

use App\Web\Chore\Edit\DataReader\Person;
use App\Web\Chore\Edit\DataReader\PersonDataReader;
use App\Web\Chore\Edit\Form;
use Yiisoft\Bootstrap5\BreadcrumbLink;
use Yiisoft\Bootstrap5\Breadcrumbs;
use Yiisoft\Bootstrap5\Button;
use Yiisoft\Bootstrap5\ButtonVariant;
use Yiisoft\FormModel\Field;
use Yiisoft\Html\Html;
use Yiisoft\Html\Tag\Option;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Yii\View\Renderer\Csrf;

/**
 * @var string $breadcrumbName
 * @var Form $form
 * @var string[] $errors
 * @var UrlGeneratorInterface $urlGenerator
 * @var Csrf $csrf
 * @var string $id
 * @var bool $isNew
 * @var PersonDataReader $personDataReader
 */

$action = $isNew ? 'Create' : 'Edit';

$adminUrl = $urlGenerator->generate('chore/admin');

$breadcrumbLinks = [
    BreadcrumbLink::to('Chore Admin', $adminUrl),
];

$htmlForm = Html::form()
    ->post($urlGenerator->generate('chore/edit', ['id' => $id]))
    ->csrf($csrf);

$this->setTitle("{$applicationParams->name} - {$action} Chore");

if ($breadcrumbName) {
    $breadcrumbLinks[] = BreadcrumbLink::to($breadcrumbName);
}

end($breadcrumbLinks)->active(true);

echo Breadcrumbs::widget()
    ->links(...$breadcrumbLinks);
?>

<?= $htmlForm->open() ?>
    <?= Field::select($form, 'person')->prompt('Select a person')->options(...array_map(
        static fn (Person $personData) => Option::tag()->value($personData->id)->content($personData->name),
        iterator_to_array($personDataReader->read())
    ))->required() ?>
    <?= Field::text($form, 'name')->required() ?>
    <?= Button::link('Cancel', $isNew ? $adminUrl : $urlGenerator->generate('chore/view', ['id' => $id])) ?>
    <?= Button::submit('Save')->variant(ButtonVariant::PRIMARY) ?>
<?= $htmlForm->close() ?>
