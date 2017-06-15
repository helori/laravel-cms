<?php

namespace Helori\LaravelCms\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

use Helori\LaravelCms\Traits\ApiCrud;


trait ApiField
{
    use ApiCrud;
    
    public function create(Request $request, $fieldset_id)
    {
        return $this->apiCreate($request);
    }

    public function read(Request $request, $fieldset_id, $id = 0)
    {
        return $this->apiRead($request, $id);
    }
    
    public function update(Request $request, $fieldset_id, $id)
    {
        return $this->apiUpdate($request, $id);
    }

    public function delete(Request $request, $fieldset_id, $id)
    {
        return $this->apiDelete($request, $id);
    }
}
