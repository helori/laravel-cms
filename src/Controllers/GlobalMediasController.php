<?php

namespace Helori\LaravelCms\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Route;

use Helori\LaravelCms\Media;
use Image;


class GlobalMediasController extends Controller
{
    protected $data = [];

    public function __construct()
    {
        $this->layout_view = config('laravel-crudui.global_medias.layout');
        $this->route_url = config('laravel-crudui.global_medias.route_url');
        //$this->layout_view = 'laravel-crudui::layout';
        
        $this->data['layout_view'] = $this->layout_view;
        $this->data['route_url'] = $this->route_url;
    }

    public function index(Request $request)
    {
        return view('laravel-crudui::global-medias', $this->data);
    }

    public function readAll(Request $request)
    {
        return Media::where('mediable_type', 'GlobalMedia')->latest()->get();
    }

    public function upload(Request $request)
    {
        if($request->hasFile('file') && $request->file('file')->isValid())
        {
            $file_path = 'storage/medias';
            if(!is_dir($file_path))
                mkdir($file_path, 0777, true);
            
            // -----------------------------------------------------------
            //  Create the media
            // -----------------------------------------------------------
            $media = new Media;
            $media->mediable_type = 'GlobalMedia';
            $media->save();

            // -----------------------------------------------------------
            //  Get uploaded file infos && Move file
            // -----------------------------------------------------------
            $file = $request->file('file');
            $file_ext = $file->guessExtension();
            //$file_name = $file->getClientOriginalName();
            $file_name = $request->input('title', 'tmp_name');
            $file_name = Str::slug($media->id.'_'.$file_name, '_');

            // -----------------------------------------------------------
            //  Save the media
            // -----------------------------------------------------------
            $media->mime = $file->getMimeType();
            $media->size = $file->getClientSize();
            $media->extension = $file_ext;
            $media->filename = $file_name.'.'.$file_ext;
            $media->title = $file_name;
            $media->filepath = $file_path.'/'.$file_name.'.'.$file_ext;
            //$media->width = $img->width();
            //$media->height = $img->height();
            //$media->size = filesize($abs_path);
            $media->save();

            $file->move(public_path().'/'.$file_path, $file_name.'.'.$file_ext);
        }
    }

    public function delete(Request $request)
    {
        $id = $request->input('id', 0);
        $media = Media::findOrFail($id);
        $path = public_path().'/'.$media->filepath;
        if(is_file($path))
            unlink($path);
        $media->delete();
    }

    public function download(Request $request, $id)
    {
        $media = Media::findOrFail($id);
        $path = public_path().'/'.$media->filepath;
        return response()->download($path, $media->filename);
    }

    public function update(Request $request)
    {
        $id = $request->input('id', 0);
        $media = Media::findOrFail($id);
        $media->title = $request->input('title', 'tmp_name');
        $media->save();
    }
}
