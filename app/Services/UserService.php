<?php

namespace App\Services;

use App\Constants\ErrorMessages;
use App\DataMapAccessors\WithUserMap;
use App\DataTransferObjects\UserDto;
use App\Exceptions\ServiceException;
use App\Http\Resources\UserResource;
use App\Models\PrimaryDatabase\User;
use App\Patterns\Log\LogException;
use App\Patterns\Log\LogMessage;
use App\Patterns\MethodResponses\CreateMethodResponse;
use App\Patterns\MethodResponses\FetchMethodResponse;
use App\Patterns\MethodResponses\UpdateMethodResponse;
use Illuminate\Database\QueryException;

/**
 * Class UserService
 */
class UserService
{
    use WithUserMap;

    /**
     * @param UserDto $userDto
     * @param mixed $response
     * @return CreateMethodResponse
     */
    public function register(UserDto $userDto, mixed &$response): CreateMethodResponse
    {
        try {

            if ($user = User::create($userDto->toArray())) {

                $accessToken = $user->createToken(sprintf("%s registration", $userDto->getEmail()))->accessToken;

                if (!$this->getUserMap()->toUserDto($user, $userDto)) {
                    throw new ServiceException(ErrorMessages::USER_MAP_TO_DTO_FAILED);
                }

                $user->access_token = $accessToken;

                $response = UserResource::make($user);
            }

        } catch (QueryException|ServiceException $exception) {
            return CreateMethodResponse::error([
                new LogMessage(ErrorMessages::USER_REGISTRATION_FAILED),
                new LogException($exception)
            ]);
        }

        return CreateMethodResponse::created();
    }

    /**
     * @param UserDto $userDto
     * @param mixed $response
     * @return FetchMethodResponse
     */
    public function login(UserDto $userDto, mixed &$response): FetchMethodResponse
    {
        try {

            $user = new User();
            if (!$this->getUserMap()->fromUserDto($userDto, $user)) {
                throw new ServiceException(ErrorMessages::USER_MAP_FROM_DTO_FAILED);
            }

            $accessToken = $user->createToken(sprintf("%s login", $userDto->getEmail()))->accessToken;

            $user->access_token = $accessToken;

            $response = UserResource::make($user);

        } catch (\Exception|ServiceException $exception) {
            return FetchMethodResponse::error([
                new LogMessage(ErrorMessages::USER_LOGIN_FAILED),
                new LogException($exception)
            ]);
        }

        return FetchMethodResponse::found();
    }

    /**
     * @param UserDto $userDto
     * @param mixed $response
     * @return UpdateMethodResponse
     */
    public function logout(UserDto $userDto, mixed &$response): UpdateMethodResponse
    {
        try {

            $user = new User();
            if (!$this->getUserMap()->fromUserDto($userDto, $user)) {
                throw new ServiceException(ErrorMessages::USER_MAP_FROM_DTO_FAILED);
            }

            foreach ($user->tokens as $token) {
                $token->revoke();
            }

            $response = [
                'message' => 'Successfully logged out'
            ];

        } catch (\Exception|ServiceException $exception) {
            return UpdateMethodResponse::error([
                new LogMessage(ErrorMessages::USER_LOGOUT_FAILED),
                new LogException($exception)
            ]);
        }

        return UpdateMethodResponse::updated();
    }

    /**
     * @param UserDto $userDto
     * @param mixed $response
     * @return FetchMethodResponse
     */
    public function user(UserDto $userDto, mixed &$response): FetchMethodResponse
    {
        try {

            $user = new User();
            if (!$this->getUserMap()->fromUserDto($userDto, $user)) {
                throw new ServiceException(ErrorMessages::USER_MAP_FROM_DTO_FAILED);
            }

            $response = UserResource::make($user);

        } catch (\Exception|ServiceException $exception) {
            return FetchMethodResponse::error([
                new LogMessage(ErrorMessages::USER_FETCH_FAILED),
                new LogException($exception)
            ]);
        }

        return FetchMethodResponse::found();
    }
}
