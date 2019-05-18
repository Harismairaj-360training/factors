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
                    'pattern' => 'directory/directory/spaces/',
                    'route' => 'directory/directory/spaces',
                    'suffix' => '/',
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
