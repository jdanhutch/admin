<?php

declare(strict_types=1);

use App\Shared\ApplicationParams;
use App\Web\Person\Admin\DataReader\Person;
use App\Web\Person\Admin\DataReader\PersonDataReader;
use Yiisoft\Bootstrap5\Button;
use Yiisoft\Html\Html;
use Yiisoft\View\WebView;
use Yiisoft\Yii\DataView\GridView\Column\ActionButton;
use Yiisoft\Yii\DataView\GridView\Column\ActionColumn;
use Yiisoft\Yii\DataView\GridView\Column\DataColumn;
use Yiisoft\Yii\DataView\GridView\GridView;

/**
 * @var WebView $this
 * @var ApplicationParams $applicationParams
 * @var UrlGeneratorInterface $urlGenerator
 * @var PersonDataReader $dataReader
 */

$this->setTitle("{$applicationParams->name} - Person Admin");
?>

<div>
    <h1 class="text-center">Person Admin</h1>

    <?= Button::link('Create person', $urlGenerator->generate('person/edit', ['id' => 'new'])) ?>

    <?= GridView::widget()
        ->dataReader($dataReader)
        ->noResultsText('No person records found')
        ->columns(
            new DataColumn('id'),
            new DataColumn('name'),
            new ActionColumn(
                before: '<div class="btn-group">',
                after: '</div>',
                buttons: [
                    new ActionButton(
                        Html::span('View'),
                        static fn (Person $person) => $urlGenerator->generate('person/view', ['id' => $person->id])
                    ),
                    new ActionButton(
                        Html::span('Edit'),
                        static fn (Person $person) => $urlGenerator->generate('person/edit', ['id' => $person->id])
                    )
                ]
            )
        ) ?>
</div>
