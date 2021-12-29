<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCharitySearchRequest extends FormRequest
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
            's_code' => 'required',
            's_password' => 'required',
            's_email' => 'required|string|email:rfc,dns',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            's_code.required' => 'Please enter your charity code.',
            's_password.required' => 'Please enter a valid password.',
            's_email.required' => 'Please enter a valid email.',
        ];
    }
}
