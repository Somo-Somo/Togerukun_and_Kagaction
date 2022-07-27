<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class TodoRequest extends FormRequest
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
            'uuid' => 'required',
            'date'  => 'date_format:Y-m-d'
        ];
    }

    /**
     * バリデーション項目名定義
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => 'Todo',
            'uuid' => 'uuid',
            'date' => '日付'
        ];
    }

    public function messages()
    {
        return [
            'required'    => ':attributeを入力してください。',
            'max'         => ':attribute名は:max文字以内で入力してください。',
            'date_format' => '正しい:attributeのフォーマットで入力してください'
        ];
    }
}
