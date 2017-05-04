<?php

namespace Helori\LaravelCms\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

use Helori\LaravelCms\CrudUtilities;
use Helori\LaravelCms\Media;
use Image;

use Intervention\Image\ImageManager;

class MediasBaseController extends Controller
{
    protected $class_name;

    public function __construct($class_name)
    {
        $this->class_name = $class_name;
    }

    public function getMedia(Request $request, $id, $collection, $model = null)
    {
        $item = call_user_func(array($this->class_name, 'findOrFail'), $id);
        return $item->getMedia($collection);
    }

    public function getMedias(Request $request, $id, $collection, $model = null)
    {
        $item = call_user_func(array($this->class_name, 'findOrFail'), $id);
        return $item->getMedias($collection);
    }

    public function postUploadMedia(Request $request)
    {
        return $this->uploadMedia($request, $request->input('id'), false);
    }

    public function postUploadMedias(Request $request)
    {
        return $this->uploadMedia($request, $request->input('id'), true);
    }

    protected function uploadMedia(&$request, $itemId, $multiple)
    {
        $item = call_user_func(array($this->class_name, 'findOrFail'), $itemId);

        $titles = explode(',', $request->input('titles'));
        $collection = $request->input('collection');
        $width = $request->input('width');
        $height = $request->input('height');
        $modified = $request->input('modified');
        $mime = $request->input('mime');
        $format = $request->input('format');

        $i = 0;

        while($request->hasFile($collection.$i))
        {
            if($request->file($collection.$i)->isValid())
            {
                $file_path = 'storage/medias';
                // -----------------------------------------------------------
                //  Save item to be able to read its id and name_src_field
                // -----------------------------------------------------------
                $item->save();

                // -----------------------------------------------------------
                //  Get uploaded file infos
                // -----------------------------------------------------------
                $file = $request->file($collection.$i);
                $file_ext = $file->guessExtension();
                $title = $titles[$i];

                // -----------------------------------------------------------
                //  Create or update the image
                // -----------------------------------------------------------
                $media = null;
                if(!$multiple)
                    $media = $item->getMedia($collection);

                if(!isset($media) || !$media){
                    $media = new Media();
                    $media->collection = $collection;
                    $media->save();
                }
                else{
                    $old_file = public_path().'/'.$media->filepath;
                    if(is_file($old_file))
                        unlink($old_file);
                }
    
                $file_name = Str::slug($media->id.'_'.$title, '_');

                $mime = $file->getMimeType();
                $size = $file->getClientSize();

                $media->mime = $mime;
                $media->size = $size;
                $media->filename = $file_name.'.'.$file_ext;
                $media->filepath = $file_path.'/'.$file_name.'.'.$file_ext;
                $media->title = $title;
                $media->extension = $file_ext;

                // -----------------------------------------------------------
                //  Create directory if needed
                // -----------------------------------------------------------
                if(!is_dir($file_path)){
                    mkdir($file_path, 0777, true);
                }

                // -----------------------------------------------------------
                //  Move the file
                // -----------------------------------------------------------
                $file->move(public_path().'/'.$file_path, $media->filename);
                $abs_path = public_path().'/'.$media->filepath;

                $media->size = filesize($abs_path);

                // -----------------------------------------------------------
                //  Resize and Re-format if required
                // -----------------------------------------------------------
                $is_image = (strpos($mime, 'image') !== false);

                if($is_image)
                {
                    //$this->resizeImage($media, 1000);
                    //$this->optimizeImage($media);

                    $info = getimagesize($abs_path);
                    $media->type = 'image';
                    $media->width = isset($info[0]) ? $info[0] : 0;
                    $media->height = isset($info[1]) ? $info[1] : 0;
                    //$media->mime = isset($info['mime']) ? $info['mime'] : '';
                    //$media->size = filesize($abs_path);
                }

                // -----------------------------------------------------------
                //  Associate the media to the item
                // -----------------------------------------------------------
                $media->mediable()->associate($item);

                if($multiple){
                    $medias = $item->getMedias($collection);
                    foreach($medias as &$m){
                        ++$m->position;
                        $m->save();
                    }
                    $media->position = 0;
                    $media->save();
                }
                else{
                    $media->save();
                }
            }
            ++$i;
        }
        if($multiple){
            return $item->getMedias($collection);
        }
        else{
            return $item->getMedia($collection);
        }
    }

    protected function postOptimizeMedia(Request $request)
    {
        $itemId = $request->input('id');
        $mediaId = $request->input('mediaId');

        $item = call_user_func(array($this->class_name, 'findOrFail'), $itemId);
        $media = $item->medias()->findOrFail($mediaId);
        
        $this->optimizeImage($media);
        $media->save();

        return $media;
    }

    protected function optimizeImage(&$media)
    {
        $mimes = [
            'image/gif' => 'gif',
            'image/jpeg' => 'jpg',
            'image/pjpeg' => 'jpg',
            'image/png' => 'png',
            'image/x-png' => 'png',
        ];

        $cmds_default = [
            'gif' => 'gifsicle',
            'jpg' => 'jpegoptim',
            'png' => 'pngquant',
        ];

        $opts_default = [
            'gifsicle' => '-b -O2',
            'jpegoptim' => '-m90 --strip-all',
            'pngquant' => '--ext=.png --force',
            'optipng' => '-o5 -strip all',
        ];

        $cmds = config('laravel-crudui.images_cmds', $cmds_default);
        $opts = config('laravel-crudui.images_cmds_opts', $opts_default);        

        if(in_array($media->mime, array_keys($mimes)))
        {
            if(function_exists('exec'))
            {
                // on VPS, usually placed in /usr/bin/
                // on local machine, usually placed in /usr/local/bin/

                $type = $mimes[$media->mime];
                $cmd = $cmds[$type];
                $opt = $opts[$cmd];
                $abs_path = public_path().'/'.$media->filepath;

                $cmd_test = exec($cmd.' --version', $output, $result_code);
                
                if($result_code === 0)
                {
                    exec($cmd.' '.$opt.' '.escapeshellarg($abs_path), $output, $result_code);
                    if($result_code !== 0){
                        throw new \Exception('Unable to optimise '.$type.' image, result code : '.$result_code);
                    }
                    $media->size = filesize($abs_path);
                }
                else{
                    throw new \Exception($cmd.' version returned invalid result code : '.$result_code);
                }
            }
            else{
                throw new \Exception('"exec" command is not available. This may be due to a shared host...');
            }
        }
    }

    protected function resizeImage(&$media, $width = null, $height = null)
    {
        $abs_path = public_path().'/'.$media->filepath;

        Image::configure(['driver' => 'imagick']);
        $img = Image::make($abs_path);

        if($width){
            $img->widen($width);
        }
        else if($height){
            $img->heighten($height);
        }
        $img->save($abs_path);

        $media->width = $img->width();
        $media->height = $img->height();
        $media->size = $img->filesize();
        $media->save();
    }

    public function postDeleteMedia(Request $request)
    {
        $itemId = $request->input('id');
        $mediaId = $request->input('mediaId');

        $item = call_user_func(array($this->class_name, 'findOrFail'), $itemId);
        $media = $item->medias()->where('id', $mediaId)->first();
        $collection = $media->collection;

        if($media){
            $old_file = public_path().'/'.$media->filepath;
            if(is_file($old_file))
                unlink($old_file);
            $media->delete();
        }
        return $item->getMedias($collection);
    }

    public function postRenameMedia(Request $request)
    {
        $itemId = $request->input('id');
        $mediaId = $request->input('mediaId');
        $title = $request->input('title');

        $item = call_user_func(array($this->class_name, 'findOrFail'), $itemId);
        $media = $item->medias()->where('id', $mediaId)->first();
        $collection = $media->collection;

        if($media){
            $file_dir = dirname($media->filepath);
            $file_name = Str::slug($media->id.'_'.$title, '_').'.'.$media->extension;
            $new_path = $file_dir.'/'.$file_name;

            if(file_exists(public_path().'/'.$media->filepath)){
                rename(public_path().'/'.$media->filepath, public_path().'/'.$new_path);
            }
            
            $media->title = $title;
            $media->filename = $file_name;
            $media->filepath = $new_path;
            $media->save();
        }
        return $media;
    }

    public function postUpdateMediasPosition(Request $request)
    {
        $itemId = intVal($request->input('id'));
        $mediaId = intVal($request->input('mediaId'));

        $item = call_user_func(array($this->class_name, 'findOrFail'), $itemId);
        $media = Media::findOrFail($mediaId);
        $collection = $media->collection;
        $medias = $item->getMedias($collection);

        $oldPos = $media->position;
        $newPos = intVal($request->input('position'));

        if($oldPos != $newPos)
        {
            if($oldPos < $newPos)
                $range = [$oldPos + 1, $newPos];
            else
                $range = [$newPos, $oldPos - 1];

            // whereBetween does not exist for collections as Laravel 5.2!
            //$medias = $medias->whereBetween('position', $range);

            $rangeList = [];
            for($i=$range[0]; $i<=$range[1]; ++$i)
                $rangeList[] = $i;
            $medias = $medias->whereIn('position', $rangeList);
            
            // increment and devrement does not exist for collections as Laravel 5.2!
            if($oldPos < $newPos){
                foreach($medias as &$m){
                    --$m->position;
                    $m->save();
                }
            }else{
                foreach($medias as &$m){
                    ++$m->position;
                    $m->save();
                }
            }
            $media->position = $newPos;
            $media->save();
        }
        return $item->getMedias($collection);
    }
}
