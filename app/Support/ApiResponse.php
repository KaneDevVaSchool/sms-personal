<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

trait ApiResponse
{
    /**
     * @param  array<string, mixed>  $extra
     * @return array<string, mixed>
     */
    protected function responseMeta(array $extra = []): array
    {
        return array_merge([
            'request_id' => request()->header('X-Request-ID') ?? (string) Str::uuid(),
            'trace_id' => (string) (app()->bound('trace_id') ? app('trace_id') : Str::uuid()),
            'timestamp' => now()->toIso8601String(),
        ], $extra);
    }

    protected function success(mixed $data = null, array $meta = [], int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'error' => null,
            'meta' => $this->responseMeta($meta),
        ], $status);
    }

    protected function failure(
        string $code,
        string $message,
        bool $retryable = false,
        int $status = 200,
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'data' => null,
            'error' => [
                'code' => $code,
                'message' => $message,
                'retryable' => $retryable,
            ],
            'meta' => $this->responseMeta(),
        ], $status);
    }
}
