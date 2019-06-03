<?php

return [
    'components' => [
        'urlManager' => [
            'showScriptName' => false,
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'rules' => [
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
                ]
            ]
        ]
    ],
    'params' => [
        'allowedLanguages' => ['en']
    ]
];
