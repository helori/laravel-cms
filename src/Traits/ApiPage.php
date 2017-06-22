<?php

namespace Helori\LaravelCms\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

use Helori\LaravelCms\Requests\Page as PageRequest;
use Helori\LaravelCms\Traits\ApiCrud;
use Helori\LaravelCms\Models\Page;


trait ApiPage
{
    use ApiCrud;
    
    public function create(PageRequest $request)
    {
        $request->modifyInput();
        $item = $this->apiCreate($request);
        return $item;
    }
    
    public function update(PageRequest $request, $id)
    {
        $request->modifyInput();

        $item = $this->apiUpdate($request, $id);

        if($request->has('collections')){
            $ids = $request->input('collections');
            $item->collections()->sync($ids);
        }

        if($request->has('tags')){
            $ids = $request->input('tags');
            $item->tags()->sync($ids);
        }

        if($request->has('image')){
            $this->updateMedias($request, 'image', $id);
        }

        if($request->has('images')){
            $this->updateMedias($request, 'images', $id);
        }

        return $this->apiRead($request, $id);
    }
}
