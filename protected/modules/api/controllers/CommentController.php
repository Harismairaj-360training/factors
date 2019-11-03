<?php
namespace humhub\modules\api\controllers;

use Yii;
use humhub\modules\api\controllers\BaseController;
use humhub\modules\api\models\Comment;
use yii\web\BadRequestHttpException;

/**
 * CommentController implements an interface and actions for CRUD for the cooment table.
  *
 * @author petersmithca
 */

class CommentController extends BaseController
{
    public $modelClass = 'humhub\modules\api\models\Comment';

    /**
     * Returns all comments for a particular post.
     * @param integer $id
     * @return mixed
     */
    public function actionPost($id)
    {
        $request = Yii::$app->request;
        if (!$request->isGet) {
            return;
        }
        $query = Comment::find();

        if ($id) {
             $query->where(['object_id' => $id])->orderBy('updated_at DESC');
        }
        return $query->all();
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['update'], $actions['create'], $actions['index'], $actions['delete']);
        return $actions;
    }

    /**
     * Overrides Index functionality to sort comments and limit result set
     * @return mixed
     */
    public function actionIndex(){
        $comment = Comment::find()->orderBy('updated_at DESC')->limit(self::MAX_ROWS)->all();
        return $comment;
    }

    /**
     * Overrides Update functionality.  Will only update message body.  Message to be included as POST body as JSON
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id){
        $comment = \humhub\modules\comment\models\Comment::find()->where(['id' => $id])->one();
        if (!Yii::$app->request->getBodyParam('message')) {
            throw new BadRequestHttpException('`message` is required.');
        }
        $message = Yii::$app->request->getBodyParam('message');
        $comment->message = $message;
        $comment->save();
        return \Yii::createObject([
	        'class' => 'yii\web\Response',
	        'format' => \yii\web\Response::FORMAT_JSON,
	        'statusCode' => 200,
	        'data' => $comment,
	    ]);
    }

    /**
     * Overrides Create functionality. message, post_id, and user_id to be included as POST body as JSON
     * @return mixed
     */
    public function actionCreate(){
        $comment = new \humhub\modules\comment\models\Comment();
        if (!Yii::$app->request->getBodyParam('message')) {
            throw new BadRequestHttpException('`message` is required.');
        }
        if (!Yii::$app->request->getBodyParam('post_id')) {
            throw new BadRequestHttpException('`post_id` is required.');
        }
        if (!Yii::$app->request->getBodyParam('user_id')) {
            throw new BadRequestHttpException('`user_id` is required.');
        }
        $message = Yii::$app->request->getBodyParam('message');
        $post_id = (int) Yii::$app->request->getBodyParam('post_id');
        $user_id = (int) Yii::$app->request->getBodyParam('user_id');
        $comment->message = $message;
        $comment->object_id = $post_id;
        $comment->created_by = $user_id;
        $comment->updated_by = $user_id;
        $comment->object_model = 'humhub\modules\post\models\Post';
        $comment->save();
        return \Yii::createObject([
	        'class' => 'yii\web\Response',
	        'format' => \yii\web\Response::FORMAT_JSON,
	        'statusCode' => 200,
	        'data' => $comment,
	    ]);
    }

    /**
     * Overrides Delete functionality to use humhub models, which handle associated classes
     * including walls
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id){
        $post = \humhub\modules\comment\models\Comment::find()
            ->where(['id' => $id])
            ->one();
        $post->delete();
    }
}
