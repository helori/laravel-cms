<?php

namespace Helori\LaravelCms\Traits;

use Illuminate\Http\Request;


trait ApiCrud
{
    protected $modelClass = null;
    protected $read_with = [];
    protected $update_except = [];

    protected function apiCreate(Request $request)
    {
        $item = new $this->modelClass();
        $item->save();
        $item->update($request->all());
    }

    protected function apiRead(Request $request, $id = 0)
    {
        if($id){
            return $this->modelClass::with($this->read_with)->findOrFail($id);
        }else{
            return $this->modelClass::with($this->read_with)->get();
        }
    }

    protected function apiUpdate(Request $request, $id)
    {
        $item = $this->apiRead($request, $id);
        $item->update($request->except($this->update_except));
        return $item;
    }

    protected function apiDelete(Request $request, $id)
    {
        $this->modelClass::destroy($id);
    }

    // -------------------------------------------------------------
    //  API
    // -------------------------------------------------------------
    public function create(Request $request)
    {
        return $this->apiCreate($request);
    }

    public function read(Request $request, $id = 0)
    {
        return $this->apiRead($request, $id);
    }
    
    public function update(Request $request, $id)
    {
        return $this->apiUpdate($request, $id);
    }

    public function delete(Request $request, $id)
    {
        return $this->apiDelete($request, $id);
    }
}
