<?php

declare(strict_types=1);

use App\Shared\ApplicationParams;
use Yiisoft\Bootstrap5\Button;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\View\WebView;

/**
 * @var WebView $this
 * @var ApplicationParams $applicationParams
 * @var UrlGeneratorInterface $urlGenerator
 */

$this->setTitle("{$applicationParams->name} - Admin");
?>

<div class="text-center">
    <h1>Welcome to the admin page!</h1>

    <?= Button::link('Manage person records', $urlGenerator->generate('person/admin')) ?>
    <?= Button::link('Manage chore records', $urlGenerator->generate('chore/admin')) ?>
</div>
