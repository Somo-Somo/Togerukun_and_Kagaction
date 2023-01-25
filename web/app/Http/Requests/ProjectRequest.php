<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class ProjectRequest extends FormRequest
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
        ];
    }

    /**
     * バリデーション項目名定義
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => 'プロジェクト',
        ];
    }

    public function messages()
    {
        return [
            'required'    => ':attribute名を入力してください。',
            'max'         => ':attribute名は:max文字以内で入力してください。',
        ];
    }
}
