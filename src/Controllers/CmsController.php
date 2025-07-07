<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\User;

use App\Http\Requests\Admin\MediaRead;
use App\Http\Requests\Admin\MediaDownload;
use App\Http\Requests\Admin\MediaUpdate;
use App\Http\Requests\Admin\MediaDelete;

use App\Http\Requests\Admin\ResourceCreate;
use App\Http\Requests\Admin\ResourceList;
use App\Http\Requests\Admin\ResourceRead;
use App\Http\Requests\Admin\ResourceUpdate;
use App\Http\Requests\Admin\ResourceDelete;


class AdminController extends Controller
{
    public function admin(Request $request)
    {
        return view('admin.admin', [
            'user' => $request->user(),
        ]);
    }

    public function login(Request $request)
    {
        return view('admin.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if(auth()->attempt($credentials))
        {
            $request->session()->regenerate();
            return redirect()->intended('admin');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        if($request->expectsJson())
        {
            return response()->json(['message' => 'Logged out successfully.'], 200);
        }
        return redirect()->route('login');
    }

    public function user(Request $request) { return $request->user(); }

    public function mediaRead(MediaRead $request, $id) { return $request->handle($id); }
    public function mediaDownload(MediaDownload $request, $id) { return $request->handle($id); }
    public function mediaUpdate(MediaUpdate $request, $id) { return $request->handle($id); }
    public function mediaDelete(MediaDelete $request, $id) { return $request->handle($id); }

    public function resourceList(ResourceList $request, $resource) { return $request->handle($resource); }
    public function resourceCreate(ResourceCreate $request, $resource) { return $request->handle($resource); }
    public function resourceRead(ResourceRead $request, $resource, $id) { return $request->handle($resource, $id); }
    public function resourceUpdate(ResourceUpdate $request, $resource, $id) { return $request->handle($resource, $id); }
    public function resourceDelete(ResourceDelete $request, $resource, $id) { return $request->handle($resource, $id); }
}
