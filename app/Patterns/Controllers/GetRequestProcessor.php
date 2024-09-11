<?php

namespace App\Patterns\Controllers;

use Symfony\Component\HttpFoundation\Response as StatusCodes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Patterns\MethodResponses\FetchMethodResponse;

/**
 * Class GetRequestProcessor
 * @package App\Patterns\Controllers
 */
class GetRequestProcessor extends AbstractRequestProcessor
{
    /**
     * @param mixed $processRequestDelegate
     */
    public function __construct(
        private readonly mixed $processRequestDelegate,
    ) {}

    /**
     * @return JsonResponse|ResourceCollection|JsonResource
     */
    public function process(): JsonResponse|ResourceCollection|JsonResource
    {
        $this->logQueries();

        $response = [];
        /**
         * @var FetchMethodResponse $methodResponse
         */
        $methodResponse = call_user_func_array($this->processRequestDelegate, array(&$response));

        if ($methodResponse->isFound()) {

            if ($response instanceof JsonResource) {
                return $response;
            }

            return $this->response($methodResponse->getStatusTypeId(), $response);
        }

        if ($methodResponse->isError()) {
            return $this->response($methodResponse->getStatusTypeId());
        }

        if ($methodResponse->isInvalid()) {
            $this->responseFromInvalidRequest($response, $methodResponse->getMessages());
            return $this->response($methodResponse->getStatusTypeId(), $response);
        }

        return $this->response(StatusCodes::HTTP_NOT_FOUND);
    }
}
