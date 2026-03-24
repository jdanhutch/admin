<?php

declare(strict_types=1);

namespace App\Web\Chore\View;

use App\Web\Shared\ActiveRecord\Chore;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Http\Status;
use Yiisoft\Router\HydratorAttribute\RouteArgument;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private WebViewRenderer $viewRenderer,
        private ResponseFactoryInterface $responseFactory,
    ) {}

    public function __invoke(
        #[RouteArgument('id')]
        string $id,
    ): ResponseInterface
    {
        $chore = Chore::findByUuidPk($id);

        if ($chore === null) {
            return $this->responseFactory->createResponse(Status::NOT_FOUND);
        }

        return $this->viewRenderer->render(__DIR__ . '/template', [
            'chore' => $chore
        ]);
    }
}
