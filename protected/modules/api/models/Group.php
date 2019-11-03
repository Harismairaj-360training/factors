<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2016 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\api\models;

use Yii;
use humhub\components\ActiveRecord;
use humhub\modules\space\models\Space;
use humhub\modules\user\models\GroupUser;

/**
 * This is the model class for table "group".
 *
 * @property integer $id
 * @property integer $space_id
 * @property string $name
 * @property string $description
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class Group extends ActiveRecord
{
    /**
     * Returns all member user of this group as ActiveQuery
     *
     * @return ActiveQuery
     */
    public function getUsers()
    {
        $query = User::find();
        $query->leftJoin('group_user', 'group_user.user_id=user.id AND group_user.group_id=:groupId', [
            ':groupId' => $this->id
        ]);
        $query->andWhere(['IS NOT', 'group_user.id', new \yii\db\Expression('NULL')]);
        $query->multiple = true;
        return $query;
    }

    public static function getDirectoryGroup($name)
    {
        return self::find()->where(['name' => $name, 'show_at_directory' => '1'])->orderBy('name ASC')->all();
    }

    public static function findGroup($uId,$label)
    {
      $groupUser = GroupUser::findOne(['user_id' => $uId]);
      if(!empty($groupUser->group_id))
      {
        $groups = self::find()->where(['id' => $groupUser->group_id])->orderBy('name ASC')->all();
        return strtolower($groups[0]->name) != strtolower($label);
      }
      return true;
    }

    public static function getAllGroups($name)
    {
        $groups = self::find()->where(['name' => $name])->orderBy('name ASC')->all();
        return $groups;
    }

    public static function setUserGroup($uId,$gId)
    {
        //  Delete Last UserGroup
        $oldGroupUser = GroupUser::findOne(['user_id' => $uId]);
        $oldGroupUser->delete();

        //  Insert New UserGroup
        $newGroupUser = new GroupUser();
        $newGroupUser->user_id = $uId;
        $newGroupUser->group_id = $gId;
        $newGroupUser->created_at = new \yii\db\Expression('NOW()');
        $newGroupUser->created_by = 1;
        $newGroupUser->is_group_manager = false;
        $newGroupUser->save();
        return true;
    }

}
