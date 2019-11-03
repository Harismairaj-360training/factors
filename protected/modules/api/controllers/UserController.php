<?php
namespace humhub\modules\api\controllers;

use Yii;
use humhub\modules\api\controllers\BaseController;
use humhub\modules\api\models\User;
use humhub\modules\user\models\forms\AccountLogin;

class UserController extends BaseController
{
    public $modelClass = 'humhub\modules\api\models\User';

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
     * Allows searching users by user name, returning a list of matches.
     * @param string $search
     * @return mixed
     */
    public function actionSearch($search)
    {
        $request = Yii::$app->request;
        if (!$request->isGet) {
            return;
        }
        $query = User::find();

        if ($search) {
             $query->where(['like', 'username', $search]);
        }

        return $query->all();
    }

    /**
     * Login end point, which will return a user matching the passes uername and password
     * including walls
     * @param string $username
     * @param string $password
     * @return mixed
     */
    public function actionLogin($username, $password) {
        $auth = new AccountLogin();
        $auth->username = $username;
        $auth->password = $password;
        try {
            if ($auth->login()) {
                return $auth->getUser();
            } else {
                return false;
            }
        } catch(Exception $e) {
            return false;
        }
    }

    /**
     * Overrides Index functionality to return a list of users
     * will include the user's profile if the optional `eager` paramter is true
     * @param boolean $eager
     * @return mixed
     */
    public function actionIndex($eager = false, $id = null){

		if ($id) {
        $users = User::find()
  	        ->innerJoinWith('profile', $eager)
            ->where(['id' => $id])
            ->asArray()
            ->all();
		}else{
  			$users = User::find()
  					->innerJoinWith('profile', $eager)
  					->asArray()
  					->all();
		}
        return $users;
    }

    /**
     * Overrides View functionality to return `eager` results
     * for user profile
     * @param integer $id
     * @return mixed
     */
    public function actionView($id){
        $user = User::find()
            ->innerJoinWith('profile')
            ->asArray()
            ->one();
        return "$user";
    }
}
