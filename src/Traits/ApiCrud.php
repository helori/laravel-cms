<?php

namespace Helori\LaravelCms\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Helori\LaravelCms\Models\Page;


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
        if($this->sortable){
            $item->position = 0;
        }
        $item->save();
        $item->update($request->all());

        return $item;
    }

    protected function apiRead(Request $request, $id = 0)
    {
        if($id){
            return $this->modelClass::with($this->read_with)->where($this->where)->findOrFail($id);
        }else{
            
            // All eloquent methods are not available !
            // Check Scout docs (only where, paginate and some few others are ok)
            // => We have a problem loading relations using "with", and sorting with "orderBy"

            if(false && $request->has('search') && $request->input('search') !== ''){
                $query = $this->modelClass::search($request->input('search'))->with($this->read_with);
            }else{
                $query = $this->modelClass::with($this->read_with);
            }
            if($this->sortable){
                if($this->sortable_nested){
                    $this->where['parent_id'] = 0;
                }
                $query->orderBy('position');
            }
            return $query->where($this->where)->paginate(100);
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

    protected function apiSearchable(Request $request)
    {
        $this->modelClass::all()->searchable();
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

    public function searchable(Request $request)
    {
        return $this->apiSearchable($request);
    }
}
