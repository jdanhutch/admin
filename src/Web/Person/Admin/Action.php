<?php

declare(strict_types=1);

namespace App\Web\Person\Admin;

use App\Web\Person\Admin\DataReader\PersonDataReader;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private WebViewRenderer $viewRenderer,
    ) {}

    public function __invoke(
        PersonDataReader $personDataReader,
    ): ResponseInterface
    {
        return $this->viewRenderer->render(
            __DIR__ . '/template',
            [
                'dataReader' => $personDataReader
            ]
        );
    }
}
