<?php

namespace Helori\LaravelCms\Requests;


class ResourceDelete extends ResourceCreate
{
    public function handle($resourceName, $id)
    {
        $classname = $this->getResourceClass($resourceName);
        $item = $classname::findOrFail($id);
        $item->delete();
    }
}
