<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|min:10|max:30',
            'password' => 'required|min:6|max:30',
            'case' => 'required|int|min:1|max:6'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'A email is required',
            'password.required' => 'A password is required',
            'case.required' => 'A password is required',
        ];
    }

    public function attributes()
    {
        return [
            'email' => 'email',
            'password' => 'password',
            'case' => 'case'
        ];
    }
}
