<?php

namespace App\Patterns\Controllers;

use Symfony\Component\HttpFoundation\Response as StatusCodes;
use Illuminate\Http\JsonResponse;
use App\Patterns\MethodResponses\CreateMethodResponse;
use Closure;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class PutRequestProcessor
 * @package App\Patterns\Controllers
 */
class PutRequestProcessor extends AbstractRequestProcessor
{
    use WithTransactions;

    /**
     * PutRequestProcessor constructor.
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
         * @var CreateMethodResponse $methodResponse
         */
        $methodResponse = call_user_func_array($this->processRequestDelegate, array(&$response));

        if ($methodResponse->isCreated()) {
            $this->commitTransactions();

            if ($response instanceof JsonResource) {
                return $response;
            }

            return $this->response($methodResponse->getStatusTypeId(), $response);
        }

        $this->rollbackTransactions();

        if ($methodResponse->isInvalid()) {
            $this->responseFromInvalidRequest($response, $methodResponse->getMessages());
            return $this->response($methodResponse->getStatusTypeId(), $response);
        }

        return $this->response(StatusCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

}
