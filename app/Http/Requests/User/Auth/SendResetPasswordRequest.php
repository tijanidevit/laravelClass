<?php

namespace App\Http\Requests\User\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Traits\ResponseTrait;

class sendResetPasswordRequest extends FormRequest
{
    use ResponseTrait;
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
           'email' => 'required|email|exists:users',
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