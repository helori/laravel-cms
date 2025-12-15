<?php

namespace Helori\LaravelCms\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use Helori\LaravelCms\Requests\Locale;
use Helori\LaravelCms\Requests\Locales;

use Helori\LaravelCms\Requests\ResourceCreate;
use Helori\LaravelCms\Requests\ResourceList;
use Helori\LaravelCms\Requests\ResourceRead;
use Helori\LaravelCms\Requests\ResourceUpdate;
use Helori\LaravelCms\Requests\ResourceDelete;
use Helori\LaravelCms\Requests\ResourceReorder;

use Helori\LaravelCms\Requests\ResourceMediaList;
use Helori\LaravelCms\Requests\ResourceMediaCreate;
use Helori\LaravelCms\Requests\ResourceMediaRead;
use Helori\LaravelCms\Requests\ResourceMediaDownload;
use Helori\LaravelCms\Requests\ResourceMediaUpdate;
use Helori\LaravelCms\Requests\ResourceMediaDelete;;
use Helori\LaravelCms\Requests\ResourceMediaReorder;

use App\Cms\CmsConfig;


class CmsController extends Controller
{
    public function admin(Request $request)
    {
        return view('laravel-cms::admin', [
            'user' => $request->user(),
            'locale' => app()->getLocale(),
            'config' => CmsConfig::config(),
        ]);
    }

    public function login(Request $request)
    {
        return view('laravel-cms::login', [
            'locale' => app()->getLocale(),
            'config' => CmsConfig::config(),
        ]);
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
    public function locales(Locales $request) { return $request->handle(); }
    public function locale(Locale $request) { return $request->handle(); }

    public function resourceList(ResourceList $request, $resource) { return $request->handle($resource); }
    public function resourceCreate(ResourceCreate $request, $resource) { return $request->handle($resource); }
    public function resourceRead(ResourceRead $request, $resource, $id) { return $request->handle($resource, $id); }
    public function resourceUpdate(ResourceUpdate $request, $resource, $id) { return $request->handle($resource, $id); }
    public function resourceDelete(ResourceDelete $request, $resource, $id) { return $request->handle($resource, $id); }
    public function resourceReorder(ResourceReorder $request, $resource) { return $request->handle($resource); }

    public function resourceMediaList(ResourceMediaList $request, $resource, $resourceId) { return $request->handle($resource, $resourceId); }
    public function resourceMediaCreate(ResourceMediaCreate $request, $resource, $resourceId) { return $request->handle($resource, $resourceId); }
    public function resourceMediaRead(ResourceMediaRead $request, $resource, $resourceId, $mediaId) { return $request->handle($resource, $resourceId, $mediaId); }
    public function resourceMediaDownload(ResourceMediaDownload $request, $resource, $resourceId, $mediaId) { return $request->handle($resource, $resourceId, $mediaId); }
    public function resourceMediaUpdate(ResourceMediaUpdate $request, $resource, $resourceId, $mediaId) { return $request->handle($resource, $resourceId, $mediaId); }
    public function resourceMediaDelete(ResourceMediaDelete $request, $resource, $resourceId, $mediaId) { return $request->handle($resource, $resourceId, $mediaId); }
    public function resourceMediaReorder(ResourceMediaReorder $request, $resource, $resourceId) { return $request->handle($resource, $resourceId); }
}
