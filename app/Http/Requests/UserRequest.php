<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $required = $this->isMethod('POST') ? 'required' : 'nullable';

        return [
            'name' => [
                $required, 'string', 'max:255',
            ],
            'email' => [
                $required, 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->user),
            ],
            'password' => [
                $required, 'string', 'min:8',
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->input('password') == null) {
            $this->request->remove('password');
        }
    }

    protected function passedValidation(): void
    {
        abort_if(count($this->validated()) == 0, 422, 'Required at least one field');
    }

    public function bodyParameters()
    {
        return [
            'name' => [
                'example' => 'Test User',
            ],
            'email' => [
                'example' => 'test@example.com',
            ],
            'password' => [
                'example' => Str::random(10),
            ],
        ];
    }
}
