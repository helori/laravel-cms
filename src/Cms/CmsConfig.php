<?php

namespace App\Cms;


class CmsConfig
{
    static public function config():array
    {
        return [
            'resources' => self::resources(),
            'menu' => self::menu(),
        ];
    }

    static public function resources():array
    {
        return [];
    }

    static public function menu():array
    {
        return [];
    }

    static public function adminMiddlewares():array
    {
        return [
            
        ];
    }
}
