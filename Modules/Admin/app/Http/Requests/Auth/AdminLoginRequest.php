<?php

namespace Modules\Admin\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Admin\Http\Requests\Contracts\AdminLoginRequestInterface;

class AdminLoginRequest extends FormRequest implements AdminLoginRequestInterface
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ];
    }

    public function getEmail(): string
    {
        return (string) $this->validated('email');
    }

    public function getPassword(): string
    {
        return (string) $this->validated('password');
    }

    public function isRemember(): bool
    {
        return (bool) $this->boolean('remember');
    }

    public function getCredentials(): array
    {
        return [
            'email' => $this->getEmail(),
            'password' => $this->getPassword(),
        ];
    }
}
