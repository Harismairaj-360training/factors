<?php

namespace humhub\modules\api\models;

use Yii;
use humhub\modules\api\models\Post;

/**
 * Comment model extends humhub Comment model to implement relationships.
  *
 * @author petersmithca
 */
class Comment extends \humhub\modules\comment\models\Comment
{
    /**
     * Identifies relationship between Comment and Post
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'object_id'])->where('{{comment}}.object_model = :post_type', [':post_type' => 'humhub\modules\post\models\Post']);
    }
}
