<?php

namespace humhub\modules\api\models;

use Yii;
use humhub\modules\api\models\User;
use humhub\modules\api\models\Comment;
use humhub\modules\api\models\Content;
use humhub\modules\api\models\Profile;

/**
 * Post model extends humhub Post model to implement relationships.
  *
 * @author petersmithca
 */
class Post extends \humhub\modules\post\models\Post
{
    /**
     * Identifies relationship between Post and User
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * Identifies relationship between Post and Comment
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['object_id' => 'id'])->andOnCondition(['{{comment}}.object_model' => 'humhub\modules\post\models\Post']);
    }

    /**
     * Identifies relationship between Post and Content
     */
    public function getContent()
    {
        return $this->hasOne(Content::className(), ['object_id' => 'id'])->andOnCondition(['{{content}}.object_model' => 'humhub\modules\post\models\Post']);
    }

    /**
     * Identifies relationship between User and Profile
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'created_by']);
    }
}
