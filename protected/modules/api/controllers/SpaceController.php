<?php
namespace humhub\modules\api\controllers;

use Yii;
use humhub\modules\api\controllers\BaseController;
use humhub\modules\space\models\Space;
use humhub\modules\user\models\User;

class SpaceController extends BaseController
{
    public $modelClass = 'humhub\modules\space\models\Space';

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'],$actions['delete'], $actions['update'], $actions['create']);
        return $actions;
    }

    // Haris Mairaj Customization
    public function actionIndex($guid = null)
    {
        $spaces;
        if ($guid)
        {
            $space = Space::find()
                ->where(['guid' => $guid])
                ->orderBy('updated_at DESC')
                ->limit(self::MAX_ROWS)
                ->asArray()
                ->one();

            $users = User::find()->active()
                    ->join('LEFT JOIN', 'space_membership', 'space_membership.user_id=user.id')
                    ->andWhere(['space_membership.status' => 3])
                    ->andWhere(['space_id' => $space['id']])
                    ->innerJoinWith('profile', true)
                    ->orderBy('updated_at DESC')
                    ->limit(self::MAX_ROWS)
                    ->asArray()
                    ->all();

            return ['space'=>$space,'users'=>$users];

        }else{
          $spaces = Space::find()
              ->orderBy('updated_at DESC')
              ->limit(self::MAX_ROWS)
              ->asArray()
              ->all();

          return ['spaces'=>$spaces];
        }


    }
}
