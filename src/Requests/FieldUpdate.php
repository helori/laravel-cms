<?php

namespace Helori\LaravelCms\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;


class FieldUpdate extends FormRequest
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
            'type' => 'required',
            'title' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'type.required' => 'Your field must have a type',
            'title.required' => 'Your field must have a title',
        ];
    }

    public function modifyInput()
    {
        $data = $this->all();

        if(isset($data['title'])){
            $data['name'] = Str::slug($data['title'], '_');
        }

        $this->replace($data);
    }
}
