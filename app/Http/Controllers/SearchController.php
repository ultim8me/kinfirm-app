<?php

namespace App\Http\Controllers;

use App\Patterns\Controllers\GetRequestProcessor;
use App\ServiceAccessors\WithSearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SearchController extends Controller
{
    use WithSearchService;

    /**
     * @param Request $request
     * @return JsonResponse|ResourceCollection
     */
    public function global(Request $request): JsonResponse|ResourceCollection
    {
        $processDelegate = fn(&$response) => $this->getSearchService()->globalSearch(
            $request->query('q'),
            $request->query('t', 10),
            $response
        );

        return (new GetRequestProcessor($processDelegate))
            ->setWithQueryLogging(true)
            ->process();
    }

    /**
     * @param Request $request
     * @return JsonResponse|ResourceCollection
     */
    public function product(Request $request): JsonResponse|ResourceCollection
    {
        $processDelegate = fn(&$response) => $this->getSearchService()->productSearch(
            $request->query('q'),
            $request->query('t', 10),
            $response
        );

        return (new GetRequestProcessor($processDelegate))
            ->setWithQueryLogging(true)
            ->process();
    }

}
