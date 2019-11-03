<?php

namespace humhub\modules\custom\widgets;

use Yii;
use yii\base\Widget;

class GuestComment extends Widget
{

    /**
     * Object derived from HActiveRecordContent
     *
     * @var type
     */
    public $object = null;

    public function run()
    {
      return $this->render('guestComment',[
        'object' => $this->object
      ]);
    }

}

?>
