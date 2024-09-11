<?php
namespace App\ServiceAccessors;

use App\Services\UserService;

trait WithUserService
{
    /**
     * @internal This value is managed by getUserService.
     * Direct access is discouraged.
     *
     * @var ?UserService An UserService object.
     */
    private ?UserService $userService = null;

    /**
     * Accessor method to get the UserService value.
     *
     * @return UserService An UserService object.
     */
    public function getUserService() : UserService
    {
        return $this->userService ?? app(UserService::class);
    }
}
