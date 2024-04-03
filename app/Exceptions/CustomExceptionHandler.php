<?php
namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class CustomExceptionHandler extends ExceptionHandler
{
    /**
     * @param $request
     * @param Exception|\Throwable $e
     * @return JsonResponse
     */
    public function render($request, Exception|\Throwable $e): JsonResponse
    {
        if ($e instanceof ValidationException) {
            return $this->handleValidationException($e);
        }

        return $this->handleOtherExceptions($e);
    }

    /**
     * @param ValidationException $e
     * @return JsonResponse
     */
    protected function handleValidationException(ValidationException $e): JsonResponse
    {
        return response()->json([
            'success' => false,
            'type' => 'validation',
            'errors' => $e->errors()
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @param Exception|\Throwable $e
     * @return JsonResponse
     */
    public function handleOtherExceptions(Exception|\Throwable $e): JsonResponse
    {
        Log::error($e->getMessage(), ['exception' => $e]);

        $cachedResponse = Cache::remember('external_api_response', now()->addMinutes(10), function () {
            return Http::get('http://example.com/api');
        });

        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
            'cached_response' => $cachedResponse->json(),
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param Exception|\Throwable $e
     * @return void
     * @throws \Throwable
     */
    public function report(Exception|\Throwable $e): void
    {
        Log::error($e->getMessage(), ['exception' => $e]);
        parent::report($e);
    }
}
