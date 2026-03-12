<?php

declare(strict_types=1);

namespace App\Web\Chore\Edit;

use Yiisoft\FormModel\FormModel;
use Yiisoft\Validator\Label;
use Yiisoft\Validator\Rule\Length;
use Yiisoft\Validator\Rule\Uuid;

final class Form extends FormModel
{
    #[Label('Person')]
    #[Uuid()]
    public string $person = '';

    #[Label('Name')]
    #[Length(min: 1)]
    public string $name = '';
}
