<?php

namespace Helori\LaravelCms\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Helori\LaravelCms\Models\BlogCategory as Category;
use Helori\LaravelCms\Requests\BlogCategoryCreate as CategoryCreateRequest;
use Helori\LaravelCms\Requests\BlogCategoryUpdate as CategoryUpdateRequest;


class BlogCategoriesController extends Controller
{
    public function read(Request $request, $id = null)
    {
        $query = Category::with(['articles' => function($query){
            $query->select('id');
        }]);

        if(!is_null($id)){
            return $query->findOrFail($id);
        }else{
            return $query->orderBy('title', 'asc')->get();
        }
    }

    public function delete(Request $request, $id)
    {
        Category::destroy($id);
    }

    public function create(CategoryCreateRequest $request)
    {
        return Category::create($request->all());
    }

    public function update(CategoryUpdateRequest $request, $id)
    {
        $item = Category::findOrFail($id);
        $item->update($request->except(['articles']));
        $item->save();
        return $item;
    }
}
