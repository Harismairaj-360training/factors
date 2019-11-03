<?php

namespace humhub\modules\api\models;

use Yii;
use humhub\modules\api\models\Post;
use humhub\modules\api\models\Profile;

/**
 * User model extends humhub User model to implement relationships.
  *
 * @author petersmithca
 */
class User extends \humhub\modules\user\models\User
{
    
	/**
     * Identifies relationship between User and Post
     */
    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['created_by' => 'id']);
    }

    /**
     * Identifies relationship between User and Profile
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
    }
}
