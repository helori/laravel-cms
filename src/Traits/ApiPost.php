<?php

namespace Helori\LaravelCms\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

use Helori\LaravelCms\Requests\Post as PostRequest;
use Helori\LaravelCms\Traits\ApiCrud;
use Helori\LaravelCms\Models\Post;


trait ApiPost
{
    use ApiCrud;
    
    public function create(PostRequest $request, $collection_id)
    {
        $request->modifyInput();
        $item = $this->apiCreate($request);
        return $item;
    }

    public function delete(Request $request, $collection_id, $id)
    {
        return $this->apiDelete($request, $id);
    }

    public function read(Request $request, $collection_id, $id = 0)
    {
        return $this->apiRead($request, $id);
    }
    
    public function update(PostRequest $request, $collection_id, $id)
    {
        $request->modifyInput();

        $item = $this->apiUpdate($request, $id);

        if($request->has('image')){
            $this->updateMedias($request, 'image', $id);
        }

        if($request->has('images')){
            $this->updateMedias($request, 'images', $id);
        }

        return $this->apiRead($request, $id);
    }
}
