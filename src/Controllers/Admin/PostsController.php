<?php

namespace Helori\LaravelCms\Controllers\Admin;

use Illuminate\Http\Request;

use Helori\LaravelCms\Controllers\Controller;
use Helori\LaravelCms\Traits\ApiPost;
use Helori\LaravelCms\Models\Post;
use Helori\LaravelCms\Models\Collection;


class PostsController extends Controller
{
	use ApiPost;

    public function __construct(Request $request)
    {
        $this->modelClass = Post::class;
        $this->where = [
            'collection_id' => $request->route()->parameter('collectionId')
        ];
        $this->read_with = [];
        $this->update_except = ['image', 'images'];
        $this->sortable = true;
        $this->sortable_nested = false;
    }

    // -------------------------------------------------------------
    //  Views
    // -------------------------------------------------------------
    public function post(Request $request, $collection_id)
    {
        $this->data['collection_id'] = $collection_id;
        $this->data['collection'] = Collection::findOrFail($collection_id);
        return view('laravel-cms::admin.post', $this->data);
    }

    public function edit(Request $request, $collection_id, $id)
    {
        $this->data['collection_id'] = $collection_id;
        $this->data['collection'] = Collection::findOrFail($collection_id);
        $this->data['id'] = $id;
        return view('laravel-cms::admin.post-edit', $this->data);
    }
}
