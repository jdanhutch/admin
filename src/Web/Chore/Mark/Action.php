<?php

declare(strict_types=1);

namespace App\Web\Chore\Mark;

use App\Web\Shared\ActiveRecord\Chore;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\FormModel\FormHydrator;
use Yiisoft\Http\Status;
use Yiisoft\Router\HydratorAttribute\RouteArgument;

final readonly class Action
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private FormHydrator $formHydrator,
    ) {}

    public function __invoke(
        #[RouteArgument('id')]
        string $id,
        ServerRequestInterface $request,
    ): ResponseInterface
    {
        $chore = Chore::findByPk($id);

        if ($chore === null) {
            return $this->responseFactory->createResponse(Status::NOT_FOUND);
        }

        $form = new Form();

        if (!(
            $this->formHydrator->populateFromPostAndValidate($form, $request)
            && $form->isValid()
        )) {
            return $this->responseFactory->createResponse(Status::BAD_REQUEST);
        }

        $chore->done = (bool) $form->done;
        $chore->save();

        return $this->responseFactory->createResponse(Status::OK);
    }
}
