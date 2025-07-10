<?php

namespace App\Cms;

use Illuminate\Validation\Rules\Password;

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
        return [
            [
                'name' => 'user',
                'title' => 'Administrateurs',
                'singleton' => false,
                'table' => [
                    'orderBy' => 'id',
                    'orderDir' => 'asc',
                    'canCreate' => true,
                    'canUpdate' => true,
                    'canDelete' => true,
                ],
                'fields' => [
                    [
                        'label' => 'Prénom',
                        'name' => 'firstname',
                        'type' => 'text',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'label' => 'Nom',
                        'name' => 'lastname',
                        'type' => 'text',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'label' => 'Email',
                        'name' => 'email',
                        'type' => 'email',
                        'rules' => [
                            'sometimes',
                            'string',
                            'email',
                            'max:255',
                            //Rule::unique(User::class), //->ignore($user->id),
                        ],
                    ],
                    [
                        'label' => 'Mot de passe',
                        'name' => 'password',
                        'type' => 'password',
                        'rules' => [
                            Password::min(8)
                                ->letters()
                                ->mixedCase()
                                ->numbers()
                                ->symbols()
                                ->uncompromised(),
                        ]
                    ],
                ],
            ],
        ];
    }

    static public function menu():array
    {
        return [
            [
                'title' => 'General',
                'menus' => [
                    [
                        'type' => 'resource',
                        'name' => 'user',
                        'title' => 'Administrateurs',
                    ],
                ],
            ],
            [
                'title' => 'Website contents',
                'menus' => [

                ]
            ],
        ];
    }
}
