<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\custom\models;

use Yii;
use \humhub\modules\user\models\User;
use \humhub\modules\space\models\Space;
use humhub\modules\user\models\GroupUser;
use humhub\modules\user\models\Group;

/**
 * This is the model class for table "space_membership".
 *
 * @property integer $space_id
 * @property integer $user_id
 * @property string $originator_user_id
 * @property integer $status
 * @property string $request_message
 * @property string $last_visit
 * @property integer $show_at_dashboard
 * @property boolean $can_leave
 * @property string $group_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer send_notifications
 */
class Membership extends \yii\db\ActiveRecord
{
    const STATUS_MEMBER = 3;

    public static function GetGroupId($name)
    {
        $groups = Group::find()->where(['name' => $name])->orderBy('name ASC')->all();
        if(empty($groups))
        {
          return false;
        }
        return $groups[0]->id;
    }

    public static function getSpaceMembers(Space $space,$group = 'member')
    {
        $query = User::find()->active();
        $query->join('LEFT JOIN', 'space_membership', 'space_membership.user_id=user.id');
        $query->andWhere(['space_membership.status' => self::STATUS_MEMBER]);
        $query->andWhere(['space_membership.group_id' => $group]);
        $query->andWhere(['space_id' => $space->id])->defaultOrder();
        return $query;
    }

}
