<?php

namespace App\DataTransferObjects;

use App\Patterns\Resolver\IResolvableKey;

/**
 * Class UserDto
 * @package App\DataTransferObjects
 */
class UserDto extends AbstractDto implements IResolvableKey
{
    use WithId;
    use WithTimestamps;

    protected ?string $name;
    protected ?string $email;
    protected ?string $password = null;
    protected ?string $token;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return UserDto
     */
    public function setName(?string $name): UserDto
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return UserDto
     */
    public function setEmail(?string $email): UserDto
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     * @return UserDto
     */
    public function setPassword(?string $password): UserDto
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param string|null $token
     * @return UserDto
     */
    public function setToken(?string $token): UserDto
    {
        $this->token = $token;
        return $this;
    }

    public function getCacheKey(): ?string
    {
        if (is_null($this->getId())) {
            return null;
        }

        return sprintf("%s_%d", 'user', $this->getId());
    }
}
