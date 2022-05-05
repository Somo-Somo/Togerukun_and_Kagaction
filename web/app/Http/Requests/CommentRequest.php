<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
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
            'text' => 'required|max:4000',
            'uuid' => 'required'
        ];
    }

    /**
     * バリデーション項目名定義
     * @return array
     */
    public function attributes()
    {
        return [
            'text' => 'コメント',
            'uuid' => 'uuid',
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
            'max'         => ':attributeは:max文字以内でご入力してください。',
        ];
    }
}
