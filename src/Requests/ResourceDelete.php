<?php

namespace App\Http\Requests\Admin;


class ResourceDelete extends ResourceCreate
{
    public function handle($resourceName, $id)
    {
        $classname = $this->getResourceClass($resourceName);
        $item = $classname::findOrFail($id);
        $item->delete();
    }
}
