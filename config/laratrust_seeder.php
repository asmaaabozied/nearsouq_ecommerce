<?php

return [
    'role_structure' => [
        'super_admin' => [
            'categories' => 'c,r,u,d',
            'users' => 'c,r,u,d',
            'admins' => 'c,r,u,d',
            // 'employees' => 'c,r,u,d',
            'roles' => 'c,r,u,d',
            'tags'=> 'c,r,u,d',
            'geographies'=> 'c,r,u,d',
//            'games'=> 'c,r,u,d',
            'consultations'=> 'c,r,u,d',
            'news'=> 'c,r,u,d',
            'sliders'=>'c,r,u,d',
            'contactusmassages'=> 'c,r,u,d',
            'pages'=> 'c,r,u,d',

//            'positions'=> 'c,r,u,d',


        ],
        'admin' => [
            'categories' => 'c,r',
            'users' => 'c,r',
            'admins' => 'c,r',
            // 'employees' => 'c,r',
            'roles' => 'c,r',
            'subscriptions'=> 'c,r',
            'tags'=> 'c,r',
            'geographies'=> 'c,r',
            'news'=> 'c,r,u',
'consultations'=> 'c,r,u',
            'pages'=> 'c,r,u',
            'contactusmassages'=> 'c,r,u',
            'pages'=> 'c,r,u',

//            'positions'=> 'c,r,u,d',


        ]
    ],
    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ]
];
