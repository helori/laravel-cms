<?php

namespace Helori\LaravelCms\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Locale extends FormRequest
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

    public function rules()
    {
        return [
            'locale' => 'required|in:'.implode(',', config('app.locales')),
        ];
    }

    public function messages()
    {
        return [
            'locale.in' => __('Unsupported locale : "'.$this->input('locale').'". Supported locales are : '.implode(', ', config('app.locales'))),
        ];
    }

    public function handle()
    {
        $locale = $this->input('locale');

        app()->setLocale($locale);

        $this->session()->put('locale', $locale);

        /*if(auth()->check())
        {
            $user = auth()->user();
            $user->preferred_language = $locale;
            $user->save();
        }*/

        return app()->getLocale();
    }
}
