<?php

declare(strict_types=1);

namespace App\Web\Chore\Admin;

use App\Web\Chore\Admin\DataReader\ChoreDataReader;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private WebViewRenderer $viewRenderer,
    ) {}

    public function __invoke(
        ChoreDataReader $choreDataReader,
    ): ResponseInterface
    {
        return $this->viewRenderer->render(
            __DIR__ . '/template',
            [
                'dataReader' => $choreDataReader
            ]
        );
    }
}
