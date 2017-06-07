<?php

namespace Helori\LaravelCms\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;


class Table extends FormRequest
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
            'table' => 'required',
            'alias' => 'required',
        ];
    }

    public function messages()
    {
        return [
            
        ];
    }

    public function modifyInput()
    {
        $data = $this->all();

        if(isset($data['alias'])){
            $data['alias'] = Str::slug($data['alias'], '_');
        }

        if(isset($data['table_name'])){
            $data['table_name'] = Str::slug($data['table_name'], '_');
        }

        $this->replace($data);
    }
}
