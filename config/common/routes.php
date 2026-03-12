<?php

declare(strict_types=1);

use App\Web;
use Yiisoft\Http\Method;
use Yiisoft\Router\Group;
use Yiisoft\Router\Route;

return [
    Group::create()
        ->routes(
            Route::get('/')
                ->action(Web\HomePage\Action::class)
                ->name('home'),

            Route::get('/admin')
                ->action(Web\Admin\Action::class)
                ->name('admin'),

            Group::create('/person-admin')
                ->routes(
                    Route::get('')
                        ->action(Web\Person\Admin\Action::class)
                        ->name('person/admin'),
                    Route::get('/{id}')
                        ->action(Web\Person\View\Action::class)
                        ->name('person/view'),
                    Route::methods([Method::GET, Method::POST], '/{id}/edit')
                        ->action(Web\Person\Edit\Action::class)
                        ->name('person/edit'),
                    Route::post('/{id}/delete')
                        ->action(Web\Person\Delete\Action::class)
                        ->name('person/delete'),
                ),

            Group::create('/chore-admin')
                ->routes(
                    Route::get('')
                        ->action(Web\Chore\Admin\Action::class)
                        ->name('chore/admin'),
                    Route::get('/{id}')
                        ->action(Web\Chore\View\Action::class)
                        ->name('chore/view'),
                    Route::methods([Method::GET, Method::POST], '/{id}/edit')
                        ->action(Web\Chore\Edit\Action::class)
                        ->name('chore/edit'),
                    Route::post('/{id}/delete')
                        ->action(Web\Chore\Delete\Action::class)
                        ->name('chore/delete'),
                ),
        ),
];
