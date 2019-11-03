<?php

namespace humhub\modules\api\models;

use Yii;
use humhub\modules\api\models\Post;

/**
 * Content model extends humhub Content model to implement relationships.
  *
 * @author petersmithca
 */
class Content extends \humhub\modules\content\models\Content
{
    /**
     * Identifies relationship between Content and Post
     */
    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['id' => 'object_id']);
    }
}
