<?php

declare(strict_types=1);

use Appleton\Faq\Http\Controllers\FaqController;
use Illuminate\Support\Facades\Route;

Route::prefix(config()->string('faq.route_prefix', 'api'))
    ->group(function () {
        Route::get('/faq/{faq}', [FaqController::class, 'showFaq'])->name('faq.show');
        Route::group(['middleware' => config()->array('faq.auth_middleware')], function () {
            Route::post('/faq', [FaqController::class, 'storeFaq'])->name('faq.store');
            Route::delete('/faq/{faq}', [FaqController::class,'deleteFaq'])->name('faq.delete');
            Route::post('/faq/{faq}/restore', [FaqController::class, 'restoreFaq'])->name('faq.restore');
            Route::delete('/faq/{faq}/force', [FaqController::class, 'forceDeleteFaq'])->name('faq.forceDelete');

            // Questions
            Route::post('/faq/{faq}/questions', [FaqController::class, 'addQuestion'])->name('faq.addQuestion');
            Route::patch('/faq/{faq}/questions/{question}', [FaqController::class, 'updateQuestion'])->name('faq.updateQuestion');
            Route::delete('/faq/{faq}/questions/{question}', [FaqController::class, 'deleteQuestion'])->name('faq.deleteQuestion');
            Route::post('/faq/{faq}/questions/{question}/restore', [FaqController::class, 'restoreQuestion'])->name('faq.restoreQuestion');
            Route::delete('/faq/{faq}/questions/{question}/force', [FaqController::class, 'forceDeleteQuestion'])->name('faq.forceDeleteQuestion');
        });
    });