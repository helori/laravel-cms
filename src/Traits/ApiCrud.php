<?php

namespace Helori\LaravelCms\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


trait ApiCrud
{
    protected $modelClass = null;
    protected $read_with = [];
    protected $update_except = [];
    protected $where = [];
    protected $sortable = false;
    protected $sortable_nested = false;

    protected function apiCreate(Request $request)
    {
        if($this->sortable){
            if($this->sortable_nested){
                $this->modelClass::where('parent_id', 0)->increment('position');
            }else{
                $this->modelClass::increment('position');
            }
        }

        $item = new $this->modelClass();
        foreach($this->where as $field => $value){
            $item->{$field} = $value;
        }
        if($this->sortable_nested){
            $item->parent_id = 0;
        }
        $item->position = 0;
        $item->save();
        $item->update($request->all());

        return $item;
    }

    protected function apiRead(Request $request, $id = 0)
    {
        if($id){
            return $this->modelClass::with($this->read_with)->where($this->where)->findOrFail($id);
        }else{
            if($this->sortable){
                if($this->sortable_nested){
                    $this->where['parent_id'] = 0;
                }
                return $this->modelClass::with($this->read_with)->where($this->where)->orderBy('position')->get();
            }
            return $this->modelClass::with($this->read_with)->where($this->where)->get();
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
        if($this->sortable){
            $item = $this->modelClass::findOrFail($id);    
            if($this->sortable_nested){
                $this->modelClass::where('parent_id', $item->parent_id)->where('position', '>', $item->position)->decrement('position');
            }else{
                $this->modelClass::where('position', '>', $item->position)->decrement('position');
            }
        }
        $this->modelClass::destroy($id);
    }

    protected function apiPosition(Request $request)
    {
        $sourceId = $request->input('sourceId');
        $parentId = $request->input('parentId');
        $position = $request->input('position');
        
        $sourceItem = $this->modelClass::findOrFail($sourceId);

        if($sourceItem->parent_id == $parentId)
        {
            if($sourceItem->position < $position)
            {
                $range = [$sourceItem->position + 1, $position];
                $this->modelClass::where('parent_id', $parentId)->whereBetween('position', $range)->decrement('position');
            }
            else if($sourceItem->position > $position)
            {
                $range = [$position, $sourceItem->position - 1];
                $this->modelClass::where('parent_id', $parentId)->whereBetween('position', $range)->increment('position');
            }
            $sourceItem->position = $position;
        }
        else
        {
            $this->modelClass::where('parent_id', $sourceItem->parent_id)->where('position', '>', $sourceItem->position)->decrement('position');
            $this->modelClass::where('parent_id', $parentId)->where('position', '>=', $position)->increment('position');

            $sourceItem->parent_id = $parentId;
            $sourceItem->position = $position;
        }
        $sourceItem->save();
    }

    protected function updateMedias($request, $collection, $mediable_id)
    {
        $medias = $request->input($collection);
        $medias = array_pluck($medias, 'id');

        DB::table('mediables')->where([
            'mediable_id' => $mediable_id,
            'mediable_type' => $this->modelClass,
            'collection' => $collection
        ])->delete();

        foreach($medias as $media_id){
            DB::table('mediables')->insert([
                'mediable_id' => $mediable_id,
                'mediable_type' => $this->modelClass,
                'collection' => $collection,
                'media_id' => $media_id
            ]);
        }
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

    public function position(Request $request)
    {
        return $this->apiPosition($request);
    }
}
