<?php
namespace humhub\modules\api;

return [
    'id' => 'api',
    'class' => 'humhub\modules\api\Module',
    'namespace' => 'humhub\modules\api',
    'controllerNamespace' => 'humhub\modules\api',
    'urlManagerRules' => [
        ['class' => 'humhub\modules\space\components\UrlRule'],
      	'GET api/user' => 'api/user',
      	'GET api/user/<id:\d+>' => 'api/user/view',
      	'GET api/user/search/<search:.+>' => 'api/user/search',
    		'POST api/quickstart/enrol-user/' => 'api/quickstart/enrol-user',
        'POST api/quickstart/experts/' => 'api/quickstart/experts',
        'POST api/membership/active/' => 'api/membership/active',
    		'POST api/quickstart/echo/' => 'api/quickstart/echo',
        'POST api/message/new/' => 'api/message/new',
        'POST api/clipp/data/' => 'api/clipp/data',
        'POST api/clipp/posting/' => 'api/clipp/posting',
        'GET api/user/login/<username:.+>/<password:.+>' => 'api/user/login',

        'GET api/profile' => 'api/profile',
      	'GET api/profile/<id:\d+>' => 'api/profile/view',

      	'GET api/space' => 'api/space',
    		'GET api/space/<id:\d+>' => 'api/space/view',
    		'POST api/space' => 'api/space/create',

      	'GET api/post' => 'api/post',
      	'GET api/post/<id:\d+>' => 'api/post/view',
        'POST api/post' => 'api/post/create',
        'POST api/post' => 'api/post/exist',
        'DELETE api/post/<id:\d+>' => 'api/post/delete',
        'PUT,PATCH api/post/<id:\d+>' => 'api/post/update',

      	'GET api/comment' => 'api/comment',
        'POST api/comment' => 'api/comment/create',
      	'GET api/comment/<id:\d+>' => 'api/comment/view',
      	'api/comment/post/<id:\d+>' => 'api/comment/post',
      	'DELETE api/comment/<id:\d+>' => 'api/comment/delete',
        'PUT,PATCH api/comment/<id:\d+>' => 'api/comment/update',

        'GET api/notification' => 'api/notification',
        'GET api/notification/<id:\d+>' => 'api/notification/view'
    ],
    'events' => [
      [
          'class' => \humhub\modules\admin\widgets\AdminMenu::className(),
          'event' => \humhub\modules\admin\widgets\AdminMenu::EVENT_INIT,
          'callback' => [
              'humhub\modules\api\Events',
              'onAdminMenuInit'
          ]
      ]
    ]
];
?>
