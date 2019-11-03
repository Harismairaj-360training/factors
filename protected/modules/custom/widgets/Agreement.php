<?php

namespace humhub\modules\custom\widgets;

/**
 * This widget is used include the comments functionality to a wall entry.
 *
 * Normally it shows a excerpt of all comments, but provides the functionality
 * to show all comments.
 *
 * @package humhub.modules_core.comment
 * @since 0.5
 */
class Agreement extends \yii\base\Widget
{
    public $prefix;
    public function run()
    {
        return $this->render('agreement',['prefix'=>$this->prefix]);
    }
}
