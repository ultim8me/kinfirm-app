<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\LogoutRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\UserRequest;
use App\Patterns\Controllers\GetRequestProcessor;
use App\Patterns\Controllers\PatchRequestProcessor;
use App\Patterns\Controllers\PutRequestProcessor;
use App\ServiceAccessors\WithUserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthController extends Controller
{
    use WithUserService;

    /**
     * @param RegisterRequest $request
     * @return JsonResponse|JsonResource
     */
    public function register(RegisterRequest $request): JsonResponse|JsonResource
    {
        $processDelegate = fn(array &$response) => $this->getUserService()->register(
            $request->getDTO(),
            $response
        );

        return (new PutRequestProcessor($processDelegate))
            ->setWithTransactions(true)
            ->process();
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse|JsonResource
     */
    public function login(LoginRequest $request): JsonResponse|JsonResource
    {
        $processDelegate = fn(array &$response) => $this->getUserService()->login($request->getDTO(), $response);

        return (new GetRequestProcessor($processDelegate))
            ->setWithQueryLogging(true)
            ->process();
    }

    /**
     * @param UserRequest $request
     * @return JsonResponse|JsonResource
     */
    public function user(UserRequest $request): JsonResponse|JsonResource
    {
        $processDelegate = fn(array &$response) => $this->getUserService()->user($request->getDTO(), $response);

        return (new GetRequestProcessor($processDelegate))
            ->setWithQueryLogging(false)
            ->process();
    }

    /**
     * @param LogoutRequest $request
     * @return JsonResponse
     */
    public function logout(LogoutRequest $request): JsonResponse
    {
        $processDelegate = fn(array &$response) => $this->getUserService()->logout(
            $request->getDTO(),
            $response
        );

        return (new PatchRequestProcessor($processDelegate))
            ->setWithTransactions(true)
            ->process();
    }
}
