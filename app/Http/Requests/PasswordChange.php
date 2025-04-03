<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Http\Exceptions\HttpResponseException;

use Illuminate\Contracts\Validation\Validator;

use App\Constants\ResponseCodes;

class PasswordChange extends FormRequest
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
    public function rules()
    {
        return [

            'password' => 'required',

            'confirm_password' => 'required|same:password'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $data = response()->json([

            'statusCode' => ResponseCodes::UNPROCESS_CONTENT,

            'status'     => false,

            'errors'     => $validator->errors()->messages(),

        ],ResponseCodes::UNPROCESS_CONTENT);

        throw new HttpResponseException($data);
    }
}
