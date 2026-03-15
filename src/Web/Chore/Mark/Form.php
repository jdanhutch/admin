<?php

declare(strict_types=1);

namespace App\Web\Chore\Mark;

use Yiisoft\FormModel\FormModel;
use Yiisoft\Validator\Label;
use Yiisoft\Validator\Rule\Number;

final class Form extends FormModel
{
    #[Label('Done')]
    #[Number(0, 1)]
    public string $done = '0';
}
