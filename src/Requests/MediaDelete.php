<?php

namespace App\Http\Requests\Admin;

use App\Models\Media;


class MediaDelete extends AdminBase
{
    public function handle($id)
    {
        $item = Media::findOrFail($id);
        $item->deleteFiles();
        $item->delete();
    }
}
