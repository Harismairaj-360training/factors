<?php
namespace humhub\modules\api\controllers;

use Yii;
use yii\rest\ActiveController;
use humhub\modules\post\models\Post;
use yii\filters\auth\QueryParamAuth;
use humhub\modules\api\models\ApiUser;
use yii\web\UnauthorizedHttpException;
use yii\filters\VerbFilter;

/**
 * BaseController implements authentication and sets up json parse for child classes.
  *
 * @author petersmithca
 */
class BaseController extends ActiveController
{
    
    const MAX_ROWS = 1000;

    /**
     * @inheritdoc
     */
    public function beforeAction($action) {
        Yii::$app->request->parsers =  [
          'application/json' => 'yii\web\JsonParser',
        ];
        Yii::$app->request->enableCsrfValidation = false;
        if (!parent::beforeAction($action)) {
            return false;
        }
        $req = Yii::$app->request;
        parse_str($req->queryString);
        if (!isset($access_token)) {
            throw new UnauthorizedHttpException('Access unavailable without access_token.', 401);
        }
        if (ApiUser::findIdentityByAccessToken($access_token)) {
            return true;
        } 
        throw new UnauthorizedHttpException('You are requesting with an invalid credential.', 401);
    }
}