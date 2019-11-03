<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2016 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\custom\widgets;

use Yii;

/**
 * This widget is used include the comments functionality to a wall entry.
 *
 * Normally it shows a excerpt of all comments, but provides the functionality
 * to show all comments.
 *
 * @since 0.5
 */
class GuestPost extends \yii\base\Widget
{

    /**
     * Content Object
     */
    public $space;

    /**
     * Executes the widget.
     */
    public function run()
    {
        $postId = 0;
        $modelId = 0;
        return $this->render('guestPost', [
            'contentContainer' => $this->space,
            'guid' => $this->space->guid,
            'sguid' => $this->space->url,
            'postId' => $postId,
            'modelId' => $modelId
        ]);
    }

}
