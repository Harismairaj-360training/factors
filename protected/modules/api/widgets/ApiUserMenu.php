<?php

namespace humhub\modules\api\widgets;

use Yii;
use yii\helpers\Url;
use humhub\models\Setting;

/**
 * ApiUser Administration Menu
 *
 * @author petersmithca
 */
class ApiUserMenu extends \humhub\widgets\BaseMenu
{

    public $template = "@humhub/widgets/views/tabMenu";
    public $type = "apiUserSubNavigation";

    public function init()
    {

        $this->addItem(array(
            'label' => 'Overview',
            'url' => Url::toRoute(['/api/admin/index']),
            'sortOrder' => 100,
            'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'api' && Yii::$app->controller->id == 'admin' && Yii::$app->controller->action->id == 'index'),
        ));
        $this->addItem(array(
            'label' => 'Add new Api User',
            'url' => Url::toRoute(['/api/admin/create']),
            'sortOrder' => 200,
            'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'api' && Yii::$app->controller->id == 'admin' && Yii::$app->controller->action->id == 'create'),
        ));

        parent::init();
    }

}
