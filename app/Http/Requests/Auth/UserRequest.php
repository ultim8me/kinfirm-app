<?php

namespace App\Http\Requests\Auth;

use App\DataMaps\UserMap;
use App\DataTransferObjects\UserDto;
use App\Http\Requests\RequestDtoInterface;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response as StatusCodes;

class UserRequest extends FormRequest implements RequestDtoInterface
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function failedAuthorization()
    {
        abort(StatusCodes::HTTP_FORBIDDEN, 'Access denied');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [

        ];
    }

    public function getDTO(): UserDto
    {
        $userDto = new UserDto();
        (new UserMap())->toUserDto($this->user(), $userDto);

        return $userDto;
    }
}
