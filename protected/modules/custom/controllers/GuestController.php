<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2016 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\custom\controllers;

use Yii;
use yii\helpers\Url;
use humhub\components\Controller;
use humhub\modules\user\models\forms\Registration;
use humhub\modules\custom\models\forms\GuestLogin;
use humhub\modules\custom\models\forms\GuestRegister;

class GuestController extends Controller
{
    private function getUserIdBefore($email,$alias)
    {
      //  Register User or Just Get Id if already Registered
      $model = new GuestRegister;
      $fullname = explode(" ",$alias);
      $model->username = $fullname[0]."-".$email."-guest";
      $model->email = $fullname[0]."-".$email."-guest";
      $model->firstname = $fullname[0];
      $model->lastname = (!isset($fullname[1])?".":$fullname[1]);
      $model->usergroup = "Guests";
      $userInfo = $model->registerInHumhub();
      $userId = (int) $userInfo->User->id;

      //  Auto login
      $gUser = \humhub\modules\user\models\User::findOne(['id' => $userId]);
      Yii::$app->user->switchIdentity($gUser);

      return $userId;
    }

    public function actionPost()
    {
      $request = Yii::$app->request;

      $message = $request->post('message');
      $guid = $request->post('guid');
      $guest_alias = $request->post('guest_alias');
      $guest_email = $request->post('guest_email');

      $userId = $this->getUserIdBefore($guest_email,$guest_alias);
      $space = \humhub\modules\space\models\Space::findOne(['guid' => $guid]);
      $post = new \humhub\modules\post\models\Post();

      if($space)
      {
        $space->addMember($userId);

        $membership = \humhub\modules\space\models\Membership::findOne(['space_id' => $space->id, 'user_id' => $userId]);
        $membership['group_id'] = 'member';
        $membership->save();

        $post->message = $message;
        $post->created_by = $userId;
        $post->updated_by = $userId;
        $post->content->created_by = $userId;
        $post->content->updated_by = $userId;
        $post->content->visibility = 1;
        $post->content->contentcontainer_id = $space->contentcontainer_id;
        $post->save();
      }

      Yii::$app->user->logout();
      return \Yii::createObject([
        'class' => 'yii\web\Response',
        'format' => \yii\web\Response::FORMAT_JSON,
        'statusCode' => 200,
        'data' => $post
      ]);
    }

    public function actionComment()
    {
      $request = Yii::$app->request;

      $contentId = (int) $request->post('contentId');
      $postId = (int) $request->post('postId');
      $guid = $request->post('guid');
      $message = $request->post('message');
      $guest_alias = $request->post('guest_alias');
      $guest_email = $request->post('guest_email');
      $userId = $this->getUserIdBefore($guest_email,$guest_alias);

      $comment = new \humhub\modules\comment\models\Comment();
      $comment->message = $message;
      $comment->object_id = $contentId;
      $comment->created_by = $userId;
      $comment->updated_by = $userId;
      $comment->object_model = 'humhub\modules\post\models\Post';
      $comment->save();

      Yii::$app->user->logout();
      return \Yii::createObject([
        'class' => 'yii\web\Response',
        'format' => \yii\web\Response::FORMAT_JSON,
        'statusCode' => 200,
        'data' => $comment
      ]);
    }
}
?>
