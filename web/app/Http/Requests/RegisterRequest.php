<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
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
            'name'  => 'required|max:64',
            'email' => 'required|email:strict|unique:users',
            'password' => [
                'required',
                'min:8',
                'max:128',
                'regex:/^[!-~]+$/',
                Password::min(0)->letters()->numbers(),
            ]
        ];
    }


    /**
     * バリデーション項目名定義
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => 'ユーザー名',
            'email' => 'メールアドレス',
            'password' => 'パスワード'
        ];
    }


    /**
     * バリデーションメッセージ
     * @return array
     */
    public function messages()
    {
        return [
            'required'    => ':attributeをご入力してください。',
            'min'         => ':attributeは:min文字以上の入力がご必要です。',
            'max'         => ':attributeは:max文字以内でご入力してください。',
            'email.email' => '正しい:attributeの形式でご入力ください。',
            'email.unique' => '既に登録済みの:attributeです。',
            'password.regex' => ':attributeは半角英数字・記号のみでご入力ください',
        ];
    }
}
