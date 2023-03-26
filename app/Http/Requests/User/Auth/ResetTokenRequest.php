<?php

namespace App\Http\Requests\User\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ResetTokenRequest extends FormRequest
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
            'token' =>'required|exists:password_resets,token',
            'password' =>'required',
            'password_confirm'=>'required|same:password',

        ];
    }

    public function failedValidation(Validator $validator){
        throw new HttpResponseException($this->errorResponse($validator->errors()->first(), 422));
    }
    public function messages()
    {
        return [
            'email.exists'=> 'User email not found',
        ];
    }
}
