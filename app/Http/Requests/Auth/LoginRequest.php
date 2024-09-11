<?php

namespace App\Http\Requests\Auth;

use App\DataMaps\UserMap;
use App\DataTransferObjects\UserDto;
use App\Http\Requests\RequestDtoInterface;
use App\Models\PrimaryDatabase\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Symfony\Component\HttpFoundation\Response as StatusCodes;

class LoginRequest extends FormRequest implements RequestDtoInterface
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
            User::EMAIL => 'required|email|max:75|exists:mysql.users,email',
            User::PASSWORD => 'required',
        ];
    }

    public function getDTO(): UserDto
    {
        $userDto = new UserDto();
        (new UserMap())->toUserDto($this->user(), $userDto);

        return $userDto;
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if (!auth()->attempt([User::EMAIL => $this->{User::EMAIL}, User::PASSWORD => $this->{User::PASSWORD}], $this->remember_me ?? false)) {
                    $validator->errors()->add(
                        'password',
                        'Password does not match!'
                    );
                }
            }
        ];
    }
}
