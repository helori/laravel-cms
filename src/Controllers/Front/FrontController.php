<?php

namespace Helori\LaravelCms\Controllers\Front;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Helori\LaravelCms\Controllers\Controller;
use Helori\LaravelCms\Models\Page;
use Helori\LaravelCms\Models\Post;


class FrontController extends Controller
{
    public function __construct(Request $request)
    {
        $this->data['pages'] = Page::where([
            'published' => true,
            'parent_id' => 0
        ])->get();
    }

    public function home(Request $request)
    {
        return view('home', $this->data);
    }

    public function page(Request $request, $slug)
    {
        $page = $this->data['page'] = Page::where([
            'published' => true,
            'slug' => $slug,
        ])->first();

        if(!$page){
            abort(404);
        }

        $collections = $this->data['collections'] = $page->collections()->with(['posts' => function($query){
            $query->where(['published' => true])->orderBy('position');
        }])->where([
            
        ])->paginate(3);

        return view('page', $this->data);
    }

    public function post(Request $request, $pageSlug, $slug)
    {
        $post = $this->data['post'] = Post::with('collection')->where([
            'published' => true,
            'slug' => $slug,
        ])->first();

        if(!$post){
            abort(404);
        }

        $page = $this->data['page'] = Page::where([
            'published' => true,
            'slug' => $pageSlug
        ])->firstOrFail();

        return view('post', $this->data);
    }

    public function blog(Request $request, $alias = null)
    {
        if($alias === null){

            $articles = Article::where([
                'published' => true,
            ])->paginate(3);

            $categories = Category::where([
                'published' => true,
            ])->get();

            // ---------- SEO Start ----------
            /*Seo::set('title');
            Seo::set('description', "Des articles détaillés pour comprendre l’énergie de demain et la révolution du véhicule électrique. L’électromobilité solaire n’aura plus de secret pour vous.");
            Seo::set('image', count($articles) > 0 ? $articles[0]->mediaPath('cover') : '');

            Seo::set('breadcrumblist', [
                ['title' => 'Accueil', 'url' => route('home')],
                ['title' => 'Articles', 'url' => route('blog')],
            ]);*/
            // ---------- SEO End ----------

            $this->data['categories'] = $categories;
            $this->data['articles'] = $articles;
            return view('blog', $this->data);
        }
        else{
            $article = Article::where([
                'published' => true,
                'alias' => $alias,
            ])->first();

            // ---------- SEO Start ----------
            /*Seo::set('title', $article->title.' - See You Sun');
            Seo::set('description', $article->preview);
            Seo::set('image', $article->mediaPath('cover'));

            Seo::set('breadcrumblist', [
                ['title' => 'Accueil', 'url' => route('home')],
                ['title' => 'Articles', 'url' => route('blog')],
                ['title' => $article->title, 'url' => route('blog', ['alias' => $article->alias])],
            ]);*/
            // ---------- SEO End ----------

            $this->data['article'] = $article;
            
            return view('blog-article', $this->data);
        }
    }
}


