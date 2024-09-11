<?php

namespace App\Http\Controllers;

use App\Patterns\Controllers\GetRequestProcessor;
use App\ServiceAccessors\WithTagService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TagController extends Controller
{
    use WithTagService;

    /**
     * @return JsonResponse|ResourceCollection
     */
    public function index(): JsonResponse|ResourceCollection
    {
        $processDelegate = fn(&$tags) => $this->getTagService()->fetchAll(
            $tags
        );

        return (new GetRequestProcessor($processDelegate))
            ->setWithQueryLogging(true)
            ->process();
    }

    /**
     * @param Request $request
     * @return JsonResponse|ResourceCollection
     */
    public function popular(Request $request): JsonResponse|ResourceCollection
    {
        $processDelegate = fn(&$tags) => $this->getTagService()->fetchPopular(
            $tags,
            $request->query('t', 10)
        );

        return (new GetRequestProcessor($processDelegate))
            ->setWithQueryLogging(true)
            ->process();
    }
}
