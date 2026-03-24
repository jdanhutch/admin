<?php

declare(strict_types=1);

namespace App\Web\Person\Edit;

use App\Web\Shared\ActiveRecord\Person;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\FormModel\FormHydrator;
use Yiisoft\Http\Status;
use Yiisoft\Router\HydratorAttribute\RouteArgument;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private WebViewRenderer $viewRenderer,
        private ResponseFactoryInterface $responseFactory,
        private UrlGeneratorInterface $urlGenerator,
        private FormHydrator $formHydrator,
    ) {}

    public function __invoke(
        #[RouteArgument('id')]
        string $id,
        ServerRequestInterface $request,
    ): ResponseInterface
    {
        $isNew = $id === 'new';
        $form = new Form();

        if ($isNew) {
            $person = new Person();
        } else {
            $person = Person::findByUuidPk($id);

            if ($person === null) {
                return $this->responseFactory->createResponse(Status::NOT_FOUND);
            }

            $form->name = $person->name;
        }

        if (
            $this->formHydrator->populateFromPostAndValidate($form, $request)
            && $form->isValid()
        ) {
            $person->name = $form->name;

            if ($isNew) {
                $person->generateId();
            }

            $person->save();

            return $this->responseFactory
                ->createResponse(Status::SEE_OTHER)
                ->withHeader(
                    'Location',
                    $this->urlGenerator->generate('person/view', ['id' => $person->getId()])
                );
        }

        return $this->viewRenderer->render(__DIR__ . '/template', [
            'form' => $form,
            'id' => $id
        ]);
    }
}
