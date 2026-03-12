<?php

declare(strict_types=1);

namespace App\Web\Person\Edit;

use Yiisoft\FormModel\FormModel;
use Yiisoft\Validator\Label;
use Yiisoft\Validator\Rule\Length;

final class Form extends FormModel
{
    #[Label('Name')]
    #[Length(min: 1)]
    public string $name = '';
}
