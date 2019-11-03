<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\cfiles\permissions;

use Yii;
use humhub\modules\space\models\Space;

/**
 * WriteAccess Permission
 */
class WriteAccess extends \humhub\libs\BasePermission
{

    /**
     * @inheritdoc
     */
    public $defaultAllowedGroups = [
        Space::USERGROUP_OWNER,
        Space::USERGROUP_ADMIN,
        Space::USERGROUP_MODERATOR,
        Space::USERGROUP_MEMBER,
    ];

    /**
     * @inheritdoc
     */
    protected $fixedGroups = [
        Space::USERGROUP_USER,
        Space::USERGROUP_GUEST
    ];

    /**
     * @inheritdoc
     */
    protected $moduleId = 'cfiles';

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return Yii::t('CfilesModule.permissions', 'Add files');
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return Yii::t('CfilesModule.permissions', 'Allows the user to upload new files and create folders');
    }

}
