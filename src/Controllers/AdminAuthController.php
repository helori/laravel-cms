<?php

namespace Helori\LaravelAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;


class AdminAuthController extends Controller
{
    use AuthenticatesUsers;

    protected $guard = 'admin';
    protected $redirectTo = '/admin';
    protected $redirectAfterLogout = '/admin/login';
    protected $loginView = 'laravel-admin::login';

    // overloaded :
    public function showLoginForm()
    {
        return view($this->loginView);
    }

    // overloaded :
    protected function guard()
    {
        return Auth::guard('admin');
    }

    public function welcome(Request $request)
    {
        return view('laravel-admin::welcome', ['layout_view' => 'laravel-admin::layout']);
    }
}
