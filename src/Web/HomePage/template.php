<?php

declare(strict_types=1);

use App\Shared\ApplicationParams;
use Yiisoft\View\WebView;

/**
 * @var WebView $this
 * @var ApplicationParams $applicationParams
 */

$this->setTitle($applicationParams->name);
?>

<div class="text-center">
    <h1>Hello!</h1>

    <p>Let's get organized!</p>

    <h4>Topics</h4>

    <ul class="video-topics">
        <li>PostgreSQL</li>
        <li>Migrations</li>
        <li>Vertical slicing</li>
        <li>Forms</li>
        <li>Active record</li>
        <li>Data readers</li>
        <li>Testing</li>
    </ul>
</div>
