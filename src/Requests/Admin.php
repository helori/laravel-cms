<?php

namespace Helori\LaravelCms\Requests;

use Illuminate\Foundation\Http\FormRequest;


class Admin extends FormRequest
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
            'email' => 'required|email',
            //'password' => 'required',
            'name' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => "L'email est requis",
            'password.required' => "Le mot de passe est requis",
            'name.required' => "Le nom est requis",
            'email.email' => "L'email n'a pas un format correct",
        ];
    }

    public function modifyInput()
    {
        $data = $this->all();

        if(isset($data['password'])){
            $data['password'] = bcrypt($data['password']);
        }

        $this->replace($data);
    }
}
