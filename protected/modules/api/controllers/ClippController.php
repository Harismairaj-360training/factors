<?php
namespace humhub\modules\api\controllers;

use Yii;
use yii\helpers\Html;
use yii\web\HttpException;
use humhub\components\Controller;
use humhub\modules\space\models\Membership;
use humhub\modules\user\models\forms\Login;

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

class ClippController extends BaseController
{
    public $modelClass = 'humhub\modules\post\models\Post';

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'], $actions['view'], $actions['update'], $actions['create']);
        return $actions;
    }

    /**
     * Get CLIPP Data
     */
    public function actionData()
    {
        //  not in use yet
        prepareRequest();
        $postData = Yii::$app->request->post();
        $endpoint = CLIPP_URL.'/api/content';
        $postData = array(
          "tag"=>"AWS",
          "pageSize"=>2,
          "pageNumber"=>1,
          "searchBy"=>""
        );
        $postData = json_encode($postData);

        $headers = array(
            'Content-Type: application/json'
        );

        $curl = curl_init($endpoint);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_VERBOSE, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt ($curl, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
        $response = curl_exec($curl);

        curl_close($curl);

        return array('result' => ($response));
    }

    public function actionPosting()
    {
      prepareRequest();
      $request = Yii::$app->request;

      if (!$request->post()["message"]) {
          throw new BadRequestHttpException('`message` is required.');
      }
      if (!$request->post()["containerGuid"]) {
          throw new BadRequestHttpException('`containerGuid` is required.');
      }
      if (!$request->post()["containerClass"]) {
          throw new BadRequestHttpException('`containerClass` is required and must be humhub\modules\space\models\\');
      }

      $canSetActivity = true;
      if(!empty(Yii::$app->user->identity->id))
      {
        $canSetActivity = (Yii::$app->user->identity->id == CLIPP_USER_ID);
      }

      $userId = (int) CLIPP_USER_ID;

      $space = \humhub\modules\space\models\Space::findOne(['guid' => $request->post()["containerGuid"]]);
      $space->addMember($userId);
      $membership = Membership::findOne(['space_id' => $space->id, 'user_id' => $userId]);
      $membership['group_id'] = 'admin';
      $membership->save();

      $post = new \humhub\modules\post\models\Post();
      $post->message = $request->post()["message"];
      $post->html_message = $request->post()["html_message"];
      $post->clipp_uid = $request->post()["clipp_uid"];
      $post->created_by = $userId;
      $post->updated_by = $userId;
      $post->content->created_by = $userId;
      $post->content->updated_by = $userId;
      $post->content->visibility = 1;
      if($canSetActivity)
      {
        $post->content->contentcontainer_id = $space->contentcontainer_id;
      }

      if($post->save())
      {
        return \humhub\modules\content\widgets\WallCreateContentForm::create($post, $space);
      }
    }
}
