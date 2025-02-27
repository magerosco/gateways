<?php

namespace App\Services\Exception;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Spatie\Permission\Exceptions\UnauthorizedException;

class ExceptionHandlerService
{
    public function handleExceptions($exceptions)
    {
        $exceptions->renderable(function (ModelNotFoundException $e, Request $request) {
            return response()->json([
                'error' => 'Resource not found',
                'message' => 'The requested resource was not found.',
            ], 404);
        });

        $exceptions->renderable(function (NotFoundHttpException $e, Request $request) {
            return response()->json([
                'error' => 'Not Found',
                'message' => '',
            ], 404);
        });

        $exceptions->render(function (UnauthorizedException $e, Request $request) {
            return response()->json([
                'message' => 'You are not authorized.',
            ], 403);
        });
    }
}
