<?php

namespace Helori\LaravelCms\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Helori\LaravelCms\Models\Page;
use Helori\LaravelCms\Requests\PageCreate as PageCreateRequest;
use Helori\LaravelCms\Requests\PageUpdate as PageUpdateRequest;


class PagesController extends Controller
{
    public function read(Request $request, $id = null)
    {
        $query = Page::with(['menu', 'images', 'image']);

        if(!is_null($id)){

            return $query->findOrFail($id);

        }else{

            return $query->orderBy('position', 'asc')->get();
        }
    }

    public function delete(Request $request, $id)
    {
        $item = Page::findOrFail($id);
        $item->medias()->detach();
        $item->delete();
    }

    public function create(PageCreateRequest $request)
    {
        $request->modifyInput();
        
        return Page::create($request->all());
    }

    public function update(PageUpdateRequest $request, $id)
    {
        $request->modifyInput();

        $item = Page::findOrFail($id);
        $item->update($request->except(['menu', 'images', 'image']));
        $item->save();

        $medias = $request->input('image');
        $item->syncMedias($medias, 'image');
        
        $medias = $request->input('images');
        $item->syncMedias($medias, 'images');

        return Page::with(['images', 'image'])->findOrFail($item->id);
    }
}
