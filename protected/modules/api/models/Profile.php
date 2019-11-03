<?php

namespace humhub\modules\api\models;

use Yii;
use humhub\modules\api\models\User;

/**
 * Profile model extends humhub Profile model to implement relationships.
  *
 * @author petersmithca
 */
class Profile extends \humhub\modules\user\models\Profile
{
    
	/**
     * Identifies relationship between Profile and User
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
