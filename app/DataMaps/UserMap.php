<?php

namespace App\DataMaps;


use App\DataTransferObjects\UserDto;
use App\Models\PrimaryDatabase\User;

/**
 * Class UserMap
 */
class UserMap
{
    /**
     * @param User|null $user
     * @param UserDto $userDto
     * @return bool
     */
    public function toUserDto(?User $user, UserDto $userDto): bool
    {
        try {

            $userDto
                ->setId($user[User::ID])
                ->setName($user[User::NAME])
                ->setEmail($user[User::EMAIL])
            ;

        } catch (\Exception $exception) {
            return false;
        }

        return true;
    }

    public function toUserDtos(array $users, array &$userDtos): bool
    {
        foreach ($users as $user) {
            $userDto = new UserDto();
            $this->toUserDto($user, $userDto);
            $userDtos[] = $userDto;
        }
        return true;
    }

    /**
     * @param UserDto $userDto
     * @param User $user
     * @return bool
     */
    public function fromUserDto(UserDto $userDto, User $user): bool
    {
        try {

            $user[User::ID] = $userDto->getId();
            $user[User::NAME] = $userDto->getName();
            $user[User::EMAIL] = $userDto->getEmail();
            $user[User::PASSWORD] = $userDto->getPassword();

        } catch (\Exception $exception) {
            return false;
        }

        return true;
    }
}
