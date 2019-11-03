<?php
namespace humhub\modules\api\controllers;

use Yii;
use humhub\modules\api\controllers\BaseController;
use humhub\modules\notification\models\Notification;

/**
 * NotificationController implements an interface and actions for listing notifications.
  *
 * @author petersmithca
 */
class NotificationController extends BaseController
{
    public $modelClass = 'humhub\modules\notification\models\Notification';

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['delete'], $actions['update'], $actions['view'], $actions['create'], $actions['index']);
        return $actions;
    }

    /**
     * Overrides Index functionality to return only unseeen notifications
     * @return mixed
     */
    public function actionIndex(){
        $notifications = Notification::find()->where(['seen' => 0])->limit(self::MAX_ROWS)->all();
        return $notifications;
    }

    /**
     * Overrides View functionality
     * @param integer $id
     * @return mixed
     */
    public function actionView($id){
        $notifications = Notification::find()
                         ->where(['seen' => 0, 'user_id' => $id])
                         ->limit(self::MAX_ROWS)
                         ->all();
        return $notifications;
    }
}