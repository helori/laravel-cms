<?php

namespace Helori\LaravelCrudui;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Str;
use Image;


class CrudUtilities
{
    public static function fillItem($request, $item, $fields)
    {
        foreach($fields as $field)
        {
            if($field['type'] == 'text' || $field['type'] == 'url' || $field['type'] == 'textarea' || $field['type'] == 'editor' || $field['type'] == 'email')
                $item->{$field['name']} = $request->input($field['name']);
            else if($field['type'] == 'select')
                $item->{$field['name']} = $request->input($field['name']);
            else if($field['type'] == 'checkbox')
                $item->{$field['name']} = $request->has($field['name']) && $request->input($field['name']) == 'true';
            else if($field['type'] == 'date' || $field['type'] == 'datetime'){
                if($request->input($field['name'])){
                    $item->{$field['name']} = $request->input($field['name']);
                }
            }
            else if($field['type'] == 'password' && $request->input($field['name']) != '')
                $item->{$field['name']} = bcrypt($request->input($field['name']));
            else if($field['type'] == 'file')
                self::setFile($request, $item, $field['name'], isset($field['name_src_field']) ? $field['name_src_field'] : 'id');
            else if($field['type'] == 'image')
            {
                self::setFile($request, $item, $field['name'], isset($field['name_src_field']) ? $field['name_src_field'] : 'id');
            }
            else if($field['type'] == 'image-advanced')
            {
                // done with ajax for upload progress
                // check uploadImage()
            }
            else if($field['type'] == 'json')
                $item->{$field['name']} = json_decode($request->input($field['name']), true);
            else if($field['type'] == 'multicheck'){
                $item->{$field['name']} = json_decode($request->input($field['name']), true);
            }
        }
       
        $item->save();

        foreach($fields as $field)
        {
            if($field['type'] == 'multi-check')
            {
                $ids = json_decode($request->input($field['name']), true);
                $item->{$field['name']}()->sync($ids);
            }
        }
    }
    public static function updateAlias(&$request, &$item, &$fields)
    {
        foreach($fields as $field){
            if($field['type'] == 'alias'){
                if(is_array($field['src'])){
                    $aliasParts = [];
                    foreach($field['src'] as $src){
                        $aliasParts[] = Str::slug($item->$src, '-');
                    }
                    $item->alias = implode('-', $aliasParts);
                }
                else{
                    $item->alias = Str::slug($item->{$field['src']}, '-');
                }
                $item->save();
                return;
            }
        }
    }

    protected static function setFile(&$request, &$item, $field_name, $name_src_field)
    {
        if($request->hasFile($field_name) && $request->file($field_name)->isValid())
        {
            $file_path = 'uploads';
            if(!is_dir($file_path))
                mkdir($file_path, 0777, true);

            if(!$item->id)
                $item->save();

            if(isset($item->{$field_name}['path'])) {
                $old_file = public_path().'/'.$item->{$field_name}['path'];
                if(is_file($old_file)){
                    unlink($old_file);
                }    
            }

            $file = $request->file($field_name);
            $file_ext = $file->guessExtension();
            //$file_ext = 'jpg';
            $file_name = $item->id.'_'.($name_src_field != 'id' ? Str::slug($item->$name_src_field, '_').'_' : '').$field_name.'.'.$file_ext;

            $result = [];
            $result['mime'] = $file->getMimeType();
            $result['size'] = $file->getClientSize();
            $result['ext'] = $file_ext;
            $result['path'] = $file_path.'/'.$file_name;

            $file->move(public_path().'/'.$file_path, $file_name);

            if(strpos($result['mime'], 'image') !== false)
            {
                $abs_path = public_path().'/'.$file_path.'/'.$file_name;
                $img = Image::make($abs_path)->resize(900, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->resize(null, 900, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })/*->encode('jpg', 100)*/->save($abs_path);

                $result['mime'] = $img->mime();
                $result['size'] = filesize($abs_path);
                $result['width'] = $img->width();
                $result['height'] = $img->height();

                // ----------------------------------------------------
                //  Optimize image.
                //  Use package ps/image-optimizer instead of writing command lines ?
                //  WILL NOT WORK ON OVH MUTUALISE ANYWAY !!!
                // ----------------------------------------------------
                if(false)
                {
                    $png_exe = 'optipng';
                    $img_path = 'images/_test.png';
                    $img_path_abs = base_path($img_path);
                    
                    $mime_info = finfo_open(FILEINFO_MIME_TYPE);
                    $mime = finfo_file($mime_info, $img_path);
                    if($mime == 'image/png'){
                        $cmd = $png_exe.' '.$img_path_abs;
                        passthru($png_exe);
                    }
                }
                // ----------------------------------------------------
            }

            $item->$field_name = $result;
        }
    }
}
