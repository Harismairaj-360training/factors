<?php

namespace humhub\modules\factors;

use yii\helpers\Url;

class Module extends \humhub\components\Module
{
  public function disable()
  {
      return parent::disable();
      // what needs to be done if module is completely disabled?
  }
  public function enable()
  {
      return parent::enable();
      // what needs to be done if module is enabled?
  }
  /**
   * @inheritdoc
   */
  public function getConfigUrl()
  {
      return Url::to([
          '/factors/config'
      ]);
  }
}
