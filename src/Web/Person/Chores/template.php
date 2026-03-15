<?php

declare(strict_types=1);

use App\Shared\ApplicationParams;
use App\Web\Chore\Mark\Form;
use App\Web\Person\Chores\DataReader\ChoreDataReader;
use App\Web\Shared\ActiveRecord\Person;
use Yiisoft\Bootstrap5\BreadcrumbLink;
use Yiisoft\Bootstrap5\Breadcrumbs;
use Yiisoft\Form\Field\CheckboxLabelPlacement;
use Yiisoft\FormModel\Field;
use Yiisoft\Html\Tag\Input\Checkbox;
use Yiisoft\View\WebView;
use Yiisoft\Yii\DataView\GridView\Column\Base\DataContext;
use Yiisoft\Yii\DataView\GridView\Column\CheckboxColumn;
use Yiisoft\Yii\DataView\GridView\Column\DataColumn;
use Yiisoft\Yii\DataView\GridView\GridView;
use Yiisoft\Yii\View\Renderer\Csrf;

/**
 * @var WebView $this
 * @var ApplicationParams $applicationParams
 * @var UrlGeneratorInterface $urlGenerator
 * @var Csrf $csrf
 * @var Person $person
 * @var ChoreDataReader $dataReader
 */

$this->setTitle("{$applicationParams->name} - Person Admin");

echo Breadcrumbs::widget()
    ->links(
        BreadcrumbLink::to('Person Admin', $urlGenerator->generate('person/admin')),
        BreadcrumbLink::to($person->name, $urlGenerator->generate('person/view', ['id' => $person->getId()])),
        BreadcrumbLink::to('Chores', active: true),
    );
?>

<div>
    <h1 class="text-center">Chores</h1>

    <?= GridView::widget()
        ->dataReader($dataReader)
        ->noResultsText('No chore records found')
        ->columns(
            new CheckboxColumn(
                '',
                content: static function (Checkbox $input, DataContext $context) {
                    $form = new Form();
                    $form->done = (string) (int) $context->data->done;
                    return Field::checkbox($form, 'done')
                        ->inputAttributes([
                            'data-chore-id' => $context->data->id,
                            'class' => 'chore-checkbox form-check-input',
                        ])
                        ->labelPlacement(CheckboxLabelPlacement::DEFAULT)
                        ->hideLabel(true);
                }
            ),
            new DataColumn('chore'),
        ) ?>
</div>

<script>
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('chore-checkbox')) {
        const checkbox = e.target;
        const choreId = checkbox.dataset.choreId;
        const isDone = checkbox.checked ? 1 : 0;
        const formData = new FormData();

        e.target.disabled = true;

        formData.append(checkbox.name, isDone);
        formData.append('<?= $csrf->getParameterName() ?>', '<?= $csrf->getToken() ?>');

        fetch('<?= $urlGenerator->generate('chore/mark', ['id' => '__ID__']) ?>'.replace('__ID__', choreId), {
            method: 'POST',
            body: formData,
        }).then(response => {
            if (!response.ok) {
                checkbox.checked = !checkbox.checked;
                alert('Error updating chore');
            }
        }).catch(error => {
            checkbox.checked = !checkbox.checked;
            alert('Error updating chore: ' + error);
        }).finally(() => {
            checkbox.disabled = false;
        });
    }
});
</script>
