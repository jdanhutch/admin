<?php

declare(strict_types=1);

use App\Shared\ApplicationParams;
use App\Web\Chore\Admin\DataReader\Chore;
use App\Web\Chore\Admin\DataReader\ChoreDataReader;
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
 * @var ChoreDataReader $dataReader
 */

$this->setTitle("{$applicationParams->name} - Chore Admin");
?>

<div>
    <h1 class="text-center">Chore Admin</h1>

    <?= Button::link('Create chore', $urlGenerator->generate('chore/edit', ['id' => 'new'])) ?>

    <?= GridView::widget()
        ->dataReader($dataReader)
        ->noResultsText('No chore records found')
        ->columns(
            new DataColumn('id'),
            new DataColumn('name'),
            new ActionColumn(
                before: '<div class="btn-group">',
                after: '</div>',
                buttons: [
                    new ActionButton(
                        Html::span('View'),
                        static fn (Chore $chore) => $urlGenerator->generate('chore/view', ['id' => $chore->id])
                    ),
                    new ActionButton(
                        Html::span('Edit'),
                        static fn (Chore $chore) => $urlGenerator->generate('chore/edit', ['id' => $chore->id])
                    )
                ]
            )
        ) ?>
</div>
