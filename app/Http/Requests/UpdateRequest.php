<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        $rules =  [
            'username'  => 'required|unique:users,username,'.auth()->id().',id',
            'phone'     => 'required',
            'email'     => 'required|email|unique:users,email,'.auth()->id().',id',
        ];

        if($this->request->get('password')) {
            $rules['password']  = 'required|confirmed|min:8';
        }

        return $rules;
    }
}
