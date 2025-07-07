<?php

namespace Helori\LaravelCms\Requests;

use App\Http\Requests\BaseRequest;
use Illuminate\Support\Str;
use App\Cms\CmsConfig;


class AdminBase extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    protected function getResourceConfig(string $resourceName): array
    {
        $resourcesConfig = collect(CmsConfig::resources());

        return $resourcesConfig->first(function($resource) use($resourceName) {
            return $resource['name'] === $resourceName;
        });
    }

    protected function getResourceFields(string $resourceName): array
    {
        return $this->getResourceConfig($resourceName)['fields'];
    }

    protected function getResourceClass(string $resourceName): string
    {
        return 'App\\Models\\'.Str::studly($resourceName);
    }

    protected function getResourceApiClass(string $resourceName): string
    {
        return 'App\\Http\\Resources\\'.Str::studly($resourceName);
    }
}
