<?php

namespace App\Http\Controllers;

use App\Patterns\Controllers\GetRequestProcessor;
use App\ServiceAccessors\WithCityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CityController extends Controller
{
    use WithCityService;

    /**
     * @return JsonResponse|ResourceCollection
     */
    public function index(): JsonResponse|ResourceCollection
    {
        $processDelegate = fn(&$cities) => $this->getCityService()->fetchAll(
            $cities
        );

        return (new GetRequestProcessor($processDelegate))
            ->setWithQueryLogging(true)
            ->process();
    }
}
