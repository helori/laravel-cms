<?php

namespace Helori\LaravelCms\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Helori\LaravelCms\Models\BlogArticle as Article;
use Helori\LaravelCms\Requests\BlogArticleCreate as ArticleCreateRequest;
use Helori\LaravelCms\Requests\BlogArticleUpdate as ArticleUpdateRequest;


class BlogArticlesController extends Controller
{
    public function read(Request $request, $id = null)
    {
        $query = Article::with(['categories', 'images', 'image']);

        if(!is_null($id)){

            return $query->findOrFail($id);

        }else{

            $search = $request->input('text');

            if($search){
                $query->where(function($q) use($search) {
                    $q->where('title', 'like', '%'.$search.'%')
                        ->orWhere('preview', 'like', '%'.$search.'%')
                        ->orWhere('content', 'like', '%'.$search.'%');
                });
            }

            $categories = $request->input('categories', null);

            if($categories !== null){
                $categories = $categories ? explode(',', $categories) : [];
                $query->whereHas('categories', function($q) use($categories) {
                    $q->whereIn('id', $categories);
                });
            }

            //$articles = BlogCategory::whereIn('id', $categories)

            return $query->orderBy('created_at', 'desc')->paginate(5);
        }
    }

    public function delete(Request $request, $id)
    {
        $item = Article::findOrFail($id);
        $item->medias()->detach();
        $item->delete();
    }

    public function create(ArticleCreateRequest $request)
    {
        $request->modifyInput();
        
        return Article::create($request->all());
    }

    public function update(ArticleUpdateRequest $request, $id)
    {
        $request->modifyInput();

        $item = Article::findOrFail($id);
        $item->update($request->except(['categories', 'images', 'image']));
        $item->save();

        $categories = $request->input('categories');
        $item->categories()->sync(array_pluck($categories, 'id'));

        $medias = $request->input('image');
        $item->syncMedias($medias, 'image');
        
        $medias = $request->input('images');
        $item->syncMedias($medias, 'images');

        return Article::with(['images', 'image'])->findOrFail($item->id);
    }
}
