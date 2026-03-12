<?php

declare(strict_types=1);

namespace App\Web\Chore\Edit;

use App\Web\Chore\Edit\DataReader\PersonDataReader;
use App\Web\Shared\ActiveRecord\Chore;
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
        PersonDataReader $personDataReader
    ): ResponseInterface
    {
        $isNew = $id === 'new';
        $form = new Form();

        if ($isNew) {
            $chore = new Chore();
        } else {
            $chore = Chore::findByUuidPk($id);

            if ($chore === null) {
                return $this->responseFactory->createResponse(Status::NOT_FOUND);
            }

            $form->person = $chore->person_id;
            $form->name = $chore->name;
        }

        if (
            $this->formHydrator->populateFromPostAndValidate($form, $request)
            && $form->isValid()
        ) {
            $chore->person_id = $form->person;
            $chore->name = $form->name;

            if ($isNew) {
                $chore->generateId();
            }

            $chore->save();

            return $this->responseFactory
                ->createResponse(Status::SEE_OTHER)
                ->withHeader(
                    'Location',
                    $this->urlGenerator->generate('chore/view', ['id' => $chore->getId()])
                );
        }

        return $this->viewRenderer->render(__DIR__ . '/template', [
            'breadcrumbName' => $isNew ? '' : $chore->name,
            'isNew' => $isNew,
            'form' => $form,
            'id' => $id,
            'personDataReader' => $personDataReader
        ]);
    }
}
