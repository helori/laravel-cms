<?php

namespace Helori\LaravelCms\Requests;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Helori\LaravelCms\Models\Media;


trait HasMedia
{
    public function attachMedias(&$item, string $field)
    {
        $index = 0;
        $input = $field.'-'.$index;

        while($this->hasFile($input))
        {
            $file = $this->file($input);

            if($file->isValid())
            {
                $media = $this->createMediaForFile($file);
                $media->collection = $field;
                $media->position = $item->{$field}()->selectRaw('MAX(position) + 1 AS position')->first()?->position ?? 0;
                $media->save();
                $item->{$field}()->save($media);
            }

            $index++;
            $input = $field.'-'.$index;
        }
    }

    public function attachMedia(&$item, string $field)
    {
        if($this->hasFile($field))
        {
            $file = $this->file($field);
            if($file->isValid())
            {
                $item->{$field}()->delete();

                $media = $this->createMediaForFile($file);
                $media->collection = $field;
                $media->position = $item->{$field}()->selectRaw('MAX(position) + 1 AS position')->first()?->position ?? 0;
                $media->save();
                $item->{$field}()->save($media);
            }
        }
    }

    protected function createMediaForFile($file)
    {
        $directory = 'medias';

        // -----------------------------------------------------------
        //  Create the media
        // -----------------------------------------------------------
        $media = new Media();
        $media->save();

        // -----------------------------------------------------------
        //  Get uploaded file infos && Move file
        // -----------------------------------------------------------
        $file_ext = Str::lower($file->guessExtension());
        $file_name = $file->getClientOriginalName();
        $file_name = Str::beforeLast($file_name, '.');
        $file_name = Str::slug($media->id.'-'.$file_name, '-');

        // -----------------------------------------------------------
        //  Save the media
        // -----------------------------------------------------------
        $media->mime = $file->getMimeType();
        $media->size = $file->getSize();
        $media->extension = $file_ext;
        $media->filename = $file_name.'.'.$file_ext;
        $media->title = $file->getClientOriginalName();
        $media->filepath = $directory.'/'.$media->filename;
        $media->position = 0;

        $storage = Storage::disk('public');
        $media->filepath = $storage->putFileAs($directory, $file, $media->filename);

        //$file->move(public_path().'/'.$file_path, $media->filename);
        //$abs_path = public_path().'/'.$media->filepath;
        $abs_path = $storage->path($media->filepath);
        //chmod($abs_path, 0755);

        //$media->size = filesize($abs_path);
        //$media->decache = filemtime($abs_path);
        $media->size = $storage->size($media->filepath);
        $media->mime = $storage->mimeType($media->filepath);
        $media->decache = $storage->lastModified($media->filepath);

        $is_image = (strpos($media->mime, 'image') !== false);
        $is_pdf = (strpos($media->mime, 'pdf') !== false);
        $is_video = (strpos($media->mime, 'video') !== false);

        if($is_image)
        {
            $info = getimagesize($abs_path);
            $media->type = 'image';
            $media->width = isset($info[0]) ? $info[0] : 0;
            $media->height = isset($info[1]) ? $info[1] : 0;
        }
        else if($is_pdf)
        {
            $media->type = 'pdf';
        }
        else if($is_video)
        {
            $media->type = 'video';
        }

        $media->save();
        return $media;
    }
}
