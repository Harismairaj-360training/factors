<?php

return [
    'components' => [
        'urlManager' => [
            'showScriptName' => false,
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'rules' => [
                'experts/<uguid:.+>' => 'custom/experts/index',
                'about/<uguid:.+>' => 'custom/experts/about',
                [
                    'pattern' => '/',
                    'route' => 'directory/directory/spaces',
                    'suffix' => '/',
                    'normalizer' => [
                        'collapseSlashes' => true
                    ]
                ],
                [
                    'pattern' => 'experts',
                    'route' => 'directory/directory/members',
                    'suffix' => '',
                    'normalizer' => [
                        'collapseSlashes' => true
                    ]
                ],
                [
                    'pattern' => 'topics',
                    'route' => 'directory/directory/spaces',
                    'suffix' => '',
                    'normalizer' => [
                        'collapseSlashes' => true
                    ]
                ],
                'custom/guest/login/<postId:\d+>/<contentId:\d+>/<sguid:.+>' => 'custom/guest/login',
                'custom/guest/login/<postId:\d+>/<contentId:\d+>' => 'custom/guest/login',
                'custom/guest/register/<postId:\d+>/<contentId:\d+>/<sguid:.+>' => 'custom/guest/register',
                'custom/guest/register/<postId:\d+>/<contentId:\d+>' => 'custom/guest/register',
            ]
        ]
    ],
    'params' => [
        'allowedLanguages' => ['en']
    ]
];
