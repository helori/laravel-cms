<?php

namespace Helori\LaravelCms\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Helori\LaravelCms\Models\Menu;
use Helori\LaravelCms\Requests\MenuCreate as MenuCreateRequest;
use Helori\LaravelCms\Requests\MenuUpdate as MenuUpdateRequest;


class MenusController extends Controller
{
    public function read(Request $request, $id = null)
    {
        $query = Menu::withCount('pages');

        if(!is_null($id)){
            return $query->findOrFail($id);
        }else{
            return $query->orderBy('position', 'asc')->get();
        }
    }

    public function delete(Request $request, $id)
    {
        $item = Menu::findOrFail($id);
        foreach($item->pages as $page){
            $page->menu_id = null;
            $page->save();
        }
        Menu::destroy($id);
    }

    public function create(MenuCreateRequest $request)
    {
        $request->modifyInput();
        return Menu::create($request->all());
    }

    public function update(MenuUpdateRequest $request, $id)
    {
        $request->modifyInput();
        
        $item = Menu::findOrFail($id);
        $item->update($request->except(['pages_count']));
        $item->save();

        return $item;
    }
}
