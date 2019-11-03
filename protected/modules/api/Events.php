<?php
namespace humhub\modules\api;

use Yii;
use yii\helpers\Url;

/**
 * Events provides callbacks for all defined module events.
 * 
 * @author petersmithca
 */

class Events extends \yii\base\Object
{
    /**
     * Adds an api administration item to the admin menu.
     *
     * @param type $event
     */
    public static function onAdminMenuInit(\yii\base\Event $event)
    {
        $event->sender->addItem([
            'label' => 'Humhub Api',
            'url' => Url::toRoute('/api/admin/index'),
            'group' => 'settings',
            'icon' => '<i class="fa fa-object-group"></i>',
            'isActive' => Yii::$app->controller->module && Yii::$app->controller->module->id == 'api' && Yii::$app->controller->id == 'admin',
            'sortOrder' => 650
        ]);
    }
}