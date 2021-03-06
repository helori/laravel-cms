<?php

namespace Helori\LaravelCms\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;


class FieldsetUpdate extends FormRequest
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
            'title' => 'required|unique:fieldsets,title,'.$this->input('id'),
            //'table' => 'required|unique:fieldsets,table,'.$this->input('id'),
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Your fieldset must have a title',
            'title.unique' => 'This fieldset name is already used',
            //'table.required' => 'Your fieldset must have a table name',
            //'table.unique' => 'The table name is already used',
        ];
    }

    public function modifyInput()
    {
        $data = $this->all();

        if(isset($data['title'])){
            $data['slug'] = Str::slug($data['title'], '-');
            $data['table'] = Str::slug($data['title'], '_');
        }

        $this->replace($data);
    }
}
