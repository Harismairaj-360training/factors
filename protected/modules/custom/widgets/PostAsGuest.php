<?php

namespace humhub\modules\custom\widgets;

class PostAsGuest extends \yii\base\Widget
{
    public $postId;
    public $modelId;
    public $sguid;
    public $isComment;
    public function run()
    {
        return $this->render('postAsGuestOptions',array('postId'=>$this->postId,'modelId'=>$this->modelId,'sguid'=>$this->sguid,'isComment'=>$this->isComment));
    }
}
