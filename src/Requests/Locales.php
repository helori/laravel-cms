<?php

namespace Helori\LaravelCms\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Locales extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function handle()
    {
        return config('app.locales', []);
    }
}
