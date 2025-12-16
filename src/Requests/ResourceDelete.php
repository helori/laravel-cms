<?php

namespace Helori\LaravelCms\Requests;


class ResourceDelete extends AdminBase
{
    public function handle($resourceName, $id)
    {
        $this->queryForResource($resourceName)
            ->findOrFail($id)
            ->delete();
    }
}
