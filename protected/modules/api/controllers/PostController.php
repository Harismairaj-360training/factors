<?php
namespace humhub\modules\api\controllers;

use Yii;
use humhub\modules\api\controllers\BaseController;
use humhub\modules\api\models\Post;
use yii\web\BadRequestHttpException;

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

class PostController extends BaseController
{
    public $modelClass = 'humhub\modules\api\models\Post';

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'], $actions['view'], $actions['delete'], $actions['update'], $actions['create']);
        return $actions;
    }

    /**
     * Overrides Index functionality to sort posts and limit result set.  Returns `eager` results
     * including user and comments if eager parameter is set to true.  Returns only posts for a particular
     * space if space_id is set
     * @param boolean $eager
     * @param int $space_id
     * @return mixed
     */
    public function actionIndex($eager = false, $contentcontainer_id = null){

        if ($contentcontainer_id){
            $posts = Post::find()
                //->innerJoinWith('user', $eager)
                ->joinWith('comments', $eager)
                ->joinWith('content', false)
                //->where(['{{content}}.space_id' => $space_id])
                ->where(['{{content}}.contentcontainer_id' => $contentcontainer_id])
                ->orderBy('updated_at DESC')
                ->limit(self::MAX_ROWS)
                ->asArray()
                ->all();
        } else {
            $posts = Post::find()
                //->innerJoinWith('user', $eager)
                ->joinWith('comments', $eager)
                ->orderBy('updated_at DESC')
                ->limit(self::MAX_ROWS)
                ->asArray()
                ->all();
        }
        return $posts;
    }

    /**
     * Overrides View functionality to return `eager` results
     * including user and comments
     * @param integer $id
     * @return mixed
     */
    public function actionView($id){
        $posts = Post::find()
            ->innerJoinWith('user')
            ->joinWith('comments')
            ->where(['{{post}}.id' => $id])
            ->asArray()
            ->one();
        return $posts;
    }

    /**
     * Overrides Delete functionality to use humhub models, which handle associated classes
     * including walls and comments
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id){
        $post = \humhub\modules\post\models\Post::find()
            ->where(['id' => $id])
            ->one();
        $post->delete();
    }

    /**
     * Overrides Update functionality.  Will only update message body.  Message to be included as POST body as JSON
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id){
        $post = \humhub\modules\post\models\Post::find()->where(['id' => $id])->one();
        if (!Yii::$app->request->getBodyParam('message')) {
            throw new BadRequestHttpException('`message` is required.');
        }
        $message = Yii::$app->request->getBodyParam('message');
        $post->message = $message;
        $post->save();
        return \Yii::createObject([
            'class' => 'yii\web\Response',
            'format' => \yii\web\Response::FORMAT_JSON,
            'statusCode' => 200,
            'data' => $post,
        ]);
    }

    /**
     * Overrides Create functionality. message, containerGuid, containerClass, visibility, and user_id to be included as POST body as JSON
     * @return mixed
     */
    public function actionCreate(){
        $post = new \humhub\modules\post\models\Post();
        $request = Yii::$app->request;

        if (!$request->post()["message"]) {
            throw new BadRequestHttpException('`message` is required.');
        }
        if (!$request->post()["user_id"]) {
            throw new BadRequestHttpException('`user_id` is required.');
        }
        if (!$request->post()["containerGuid"]) {
            throw new BadRequestHttpException('`containerGuid` is required.');
        }
        if (!$request->post()["containerClass"]) {
            throw new BadRequestHttpException('`containerClass` is required and must be humhub\modules\space\models\\');
        }
        if (!$request->post()["visibility"] == null) {
            throw new BadRequestHttpException('`visibility` is required and must be 0.');
        }

        $containerGuid = $request->post()["containerGuid"];
        $message = $request->post()["message"];
        $user_id = (int) $request->post()["user_id"];
        $post->message = $message;
        $post->created_by = $user_id;
        $post->updated_by = $user_id;
        $post->save();
        $space = \humhub\modules\space\models\Space::findOne(['guid' => $containerGuid]);
        \humhub\modules\content\widgets\WallCreateContentForm::create($post, $space);
        return \Yii::createObject([
            'class' => 'yii\web\Response',
            'format' => \yii\web\Response::FORMAT_JSON,
            'statusCode' => 200,
            'data' => $post,
        ]);
    }

    public function actionExist()
    {
        prepareRequest();
        $request = Yii::$app->request;
        
        if (!isset($request->post()["contentcontainer_id"])) {
            throw new BadRequestHttpException('contentcontainer_id is required.');
        }
        if (!isset($request->post()["clipp_uid"])) {
            throw new BadRequestHttpException('clipp_uid is required.');
        }

        $contentcontainer_id = $request->post()["contentcontainer_id"];
        $clipp_uid = $request->post()["clipp_uid"];

        $posts = Post::find()
            ->where(['clipp_uid'=>$clipp_uid])
            ->joinWith('content', false)
            ->where(['{{content}}.contentcontainer_id' => $contentcontainer_id])
            ->orderBy('updated_at DESC')
            ->limit(self::MAX_ROWS)
            ->asArray()
            ->all();

        return \Yii::createObject([
            'class' => 'yii\web\Response',
            'format' => \yii\web\Response::FORMAT_JSON,
            'statusCode' => 200,
            'data' => (count($posts) != 0),
        ]);
    }

}
