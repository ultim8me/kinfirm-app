<?php

namespace App\Http\Controllers;

use App\Patterns\Controllers\GetRequestProcessor;
use App\ServiceAccessors\WithSizeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SizeController extends Controller
{
    use WithSizeService;

    /**
     * @return JsonResponse|ResourceCollection
     */
    public function index(): JsonResponse|ResourceCollection
    {
        $processDelegate = fn(&$sizes) => $this->getSizeService()->fetchAll(
            $sizes
        );

        return (new GetRequestProcessor($processDelegate))
            ->setWithQueryLogging(true)
            ->process();
    }

}
