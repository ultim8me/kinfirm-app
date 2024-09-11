<?php

namespace App\Patterns\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\Translation\TranslatableMessage;

/**
 * Class AbstractRequestProcessor
 * @package App\Patterns\Controllers
 */
abstract class AbstractRequestProcessor
{
    use WithQueryLogging;

    abstract function process(): JsonResponse|ResourceCollection|RedirectResponse|JsonResource;

    /**
     * @param array|null $response
     * @param TranslatableMessage[] $messages
     *
     * @return bool
     */
    public function responseFromInvalidRequest(?array &$response, array $messages): bool
    {
        if (empty($messages)) {
            return true;
        }

        foreach ($messages as $message) {
            $response['errors'][] = trans($message->getMessage());
        }

        return true;
    }

    protected function response(int $statusCode, mixed $content = null): JsonResponse
    {
        return response()->json($content, $statusCode);
    }
}
