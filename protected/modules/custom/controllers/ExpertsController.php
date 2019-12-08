<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2016 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\custom\controllers;

use Yii;
use humhub\modules\custom\components\ContentContainerController;
use humhub\modules\stream\actions\ContentContainerStream;
use humhub\modules\user\models\User;
use humhub\modules\user\widgets\UserListBox;

/**
 * ProfileController is responsible for all user profiles.
 * Also the following functions are implemented here.
 *
 * @author Luke
 * @package humhub.modules_core.user.controllers
 * @since 0.5
 */
class ExpertsController extends ContentContainerController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'acl' => [
                'class' => \humhub\components\behaviors\AccessControl::className(),
                'guestAllowedActions' => ['index', 'stream', 'about']
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return array(
            'stream' => array(
                'class' => ContentContainerStream::className(),
                'mode' => ContentContainerStream::MODE_NORMAL,
                'contentContainer' => $this->contentContainer
            ),
        );
    }

    /**
     * User profile home
     *
     * @todo Allow change of default action
     * @return string the response
     */
    public function actionIndex()
    {
        return $this->actionHome();
    }

    public function actionHome()
    {
        return $this->render('@humhub/modules/user/views/profile/home', ['user' => $this->contentContainer]);
    }

    public function actionAbout()
    {
        if (!$this->contentContainer->permissionManager->can(new \humhub\modules\user\permissions\ViewAboutPage())) {
            throw new \yii\web\HttpException(403, 'Forbidden');
        }

        return $this->render('@humhub/modules/user/views/profile/about', ['user' => $this->contentContainer]);
    }

}

?>
