<?php
namespace humhub\modules\api\controllers;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\HttpException;
use humhub\components\Controller;
use humhub\modules\user\models\User;
use humhub\modules\space\models\Space;
use humhub\modules\space\models\Membership;

function object_to_array($object)
{
    if($object == null)
    {
        return null;
    } else if (is_object($object)) {
        return array_map(__FUNCTION__, get_object_vars($object));
    } else if (is_array($object)) {
        return array_map(__FUNCTION__, $object);
    } else {
        return $object;
    }
}

function prepareRequest()
{
    $request = Yii::$app->request;
    if ($request->isPost && empty($request->getBodyParams())) {
        $request->setBodyParams(object_to_array(json_decode($request->getRawBody())));
    }
}

class MembershipController extends BaseController
{
    public $modelClass = 'humhub\modules\space\models\Membership';

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'], $actions['view']);
        return $actions;
    }

    /**
     * Creates a new Message
     */
    public function actionActive()
    {
        prepareRequest();
        $postData = Yii::$app->request->post();
        $status = true;
        $message = "success";
        $result = array("userId"=>"","topicMembership"=>array());
        if (empty($postData["userId"]))
        {
          $status = false;
          $message = "userId is required and data type should be string.";
        }
        else
        {
          $result["userId"] = $postData["userId"];
          if (!isset($postData["topics"]))
          {
            $status = false;
            $message = "topics is required and data type should be an array.";
          }
          else
          {
            $user = User::find()->where(['email' => $result["userId"]])->one();
            if(!empty($user->id))
            {
              $spaces = Space::findAll(['name' => $postData["topics"]]);
              $topicMembership = array();
              foreach($spaces as $space)
              {
                $membership = Membership::findOne(['space_id' => $space->id, 'user_id' => $user->id]);
                $data = array("name"=>"","url"=>"","isMember"=>false);
                $data["name"] = $space->name;
                $data["url"] = Url::base(true).'/s/'.$space->url;
                if(!empty($membership->status))
                {
                  $data["isMember"] = true;
                }
                array_push($topicMembership,$data);
              }
              $result["topicMembership"] = $topicMembership;
            }
            else
            {
              $status = false;
              $message = "User is not exist.";
            }
          }
        }

        return array('status'=>$status,'message'=>$message,'result'=>$result);
    }

}
