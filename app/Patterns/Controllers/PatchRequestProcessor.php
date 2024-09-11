<?php

namespace App\Patterns\Controllers;

use Symfony\Component\HttpFoundation\Response as StatusCodes;
use Illuminate\Http\JsonResponse;
use App\Patterns\MethodResponses\UpdateMethodResponse;
use Closure;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class PatchRequestProcessor
 * @package App\Patterns\Controllers
 */
class PatchRequestProcessor extends AbstractRequestProcessor
{
    use WithTransactions;

    /**
     * PatchRequestProcessor constructor.
     * @param Closure $processRequestDelegate
     */
    public function __construct(
        private readonly Closure $processRequestDelegate
    ) {}

    /**
     * @return JsonResponse|JsonResource
     */
    public function process(): JsonResponse|JsonResource
    {
        $response = [];

        $this->beginTransactions();
        $this->logQueries();

        /**
         * @var UpdateMethodResponse $methodResponse
         */
        $methodResponse = call_user_func_array($this->processRequestDelegate, array(&$response));

        if ($methodResponse->isUpdated()) {
            $this->commitTransactions();

            if ($response instanceof JsonResource) {
                return $response;
            }

            return $this->response($methodResponse->getStatusTypeId(), $response);
        }

        if ($methodResponse->isNotFound()) {
            return $this->response($methodResponse->getStatusTypeId());
        }

        $this->rollbackTransactions();

        if ($methodResponse->isInvalid()) {
            $this->responseFromInvalidRequest($response, $methodResponse->getMessages());
            return $this->response($methodResponse->getStatusTypeId(), $response);
        }

        return $this->response(StatusCodes::HTTP_INTERNAL_SERVER_ERROR);
    }
}
