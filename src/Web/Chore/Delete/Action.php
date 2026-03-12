<?php

declare(strict_types=1);

namespace App\Web\Chore\Delete;

use App\Web\Shared\ActiveRecord\Chore;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Http\Status;
use Yiisoft\Router\HydratorAttribute\RouteArgument;
use Yiisoft\Router\UrlGeneratorInterface;

final readonly class Action
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private UrlGeneratorInterface $urlGenerator,
    ) {}

    public function __invoke(
        #[RouteArgument('id')]
        string $id
    ): ResponseInterface
    {
        $chore = Chore::findByPk($id);

        if ($chore === null) {
            return $this->responseFactory->createResponse(Status::NOT_FOUND);
        } else if ($chore->delete() < 1) {
            return $this->responseFactory->createResponse(Status::INTERNAL_SERVER_ERROR);
        } else {
            return $this->responseFactory
                ->createResponse(Status::SEE_OTHER)
                ->withHeader('Location', $this->urlGenerator->generate('chore/admin'));
        }
    }
}
