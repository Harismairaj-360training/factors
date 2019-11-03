<?php
namespace humhub\modules\custom\widgets;

use Yii;
use yii\base\Widget;
use humhub\modules\space\models\Membership;

/**
 * LoginForm is the model behind the login form.
 */
class SubmitGuestComment extends Widget
{
  public $space;
  const USERGROUP_MEMBER = 'member';

  public function run()
  {
    $request = Yii::$app->request;
    $user_id = Yii::$app->user->id;
    $post_id = $request->get('comment');
    if($post_id == 0)
    {
      $post_id = 'new_post';
    }

    if(!empty($post_id))
    {
      $message = Yii::$app->session->getFlash('autocomment.'.Yii::$app->user->identity->email);
      $title = Yii::$app->session->getFlash('autotitle.'.Yii::$app->user->identity->email);

      if(!empty($message))
      {
        if($post_id == 'new_post')
        {
          $this->space->addMember($user_id);

          $membership = Membership::findOne(['space_id' => $this->space->id, 'user_id' => $user_id]);
          $membership['group_id'] = self::USERGROUP_MEMBER;
          $membership->save();

          $post = new \humhub\modules\post\models\Post();
          if(!empty($title))
          {
            $post->title = $title;
          }
          $post->message = $message;
          $post->created_by = $user_id;
          $post->updated_by = $user_id;
          $post->content->created_by = $user_id;
          $post->content->updated_by = $user_id;
          $post->content->visibility = 1;
          $post->content->contentcontainer_id = $this->space->contentcontainer_id;
          $post->save();
        }
        else
        {
          $comment = new \humhub\modules\comment\models\Comment();
          $comment->message = $message;
          $comment->object_id = (int) $post_id;
          $comment->created_by = (int) $user_id;
          $comment->updated_by = (int) $user_id;
          $comment->object_model = 'humhub\modules\post\models\Post';
          $comment->save();
        }
        return $message;
      }
    }
  }
}
