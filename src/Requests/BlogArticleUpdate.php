<?php

namespace Helori\LaravelCms\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;


class BlogArticleUpdate extends FormRequest
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

        if(isset($data['title'])){
            $data['slug'] = Str::slug($data['title'], '-');
        }

        $this->replace($data);
    }
}
