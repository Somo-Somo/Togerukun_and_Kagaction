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
            'name'  => 'required|max:48',
        ];
    }

    public function messages()
    {
        return [
            'required'    => 'プロジェクト名を入力してください。',
            'max'         => 'プロジェクト名は:max文字以内で入力してください。',
        ];
    }

    protected function makeProject()
    {
        $data = [];
        $data['uuid'] = (string) Str::uuid();
        $data['created_by_user_id'] = $this->user()->id;

        $this->merge($data);

        $this->validated()

        return $this;
    }
}
