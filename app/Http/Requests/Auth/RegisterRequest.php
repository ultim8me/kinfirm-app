<?php

namespace App\Http\Requests\Auth;

use App\DataTransferObjects\UserDto;
use App\Http\Requests\RequestDtoInterface;
use App\Models\PrimaryDatabase\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response as StatusCodes;

class RegisterRequest extends FormRequest implements RequestDtoInterface
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
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            User::NAME => Str::title($this->{User::NAME}),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            User::NAME => 'required|string|min:4|max:50',
            User::EMAIL => 'required|email||max:75|unique:mysql.users,email',
            User::PASSWORD => 'required|confirmed|min:8',
        ];
    }

    public function getDTO(): UserDto
    {
        $validated = $this->validated();

        return (new UserDto)
            ->setName($validated[User::NAME])
            ->setEmail($validated[User::EMAIL])
            ->setPassword($validated[User::PASSWORD])
        ;
    }
}
