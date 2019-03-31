<?php

use humhub\modules\factors\widgets\Menu;
use humhub\widgets\FooterMenu;

\humhub\assets\JqueryKnobAsset::register($this);

$isCustomer = !empty(Yii::$app->user->identity->username);
$isAdmin = Yii::$app->user->isAdmin();
?>

<?php if(true/*!$isCustomer && !$isAdmin*/){ ?>
  <div>
    <div class="banner" style="background-image:url('<?= $this->theme->getBaseUrl(); ?>/img/banner.jpg')">

    </div>
  </div>
<?php } ?>

<div class="container">
    <div class="row">
        <div class="col-md-3">
            <?= Menu::widget(); ?>
        </div>
        <div class="col-md-9 directory">
            <?= $content; ?>
        </div>
    </div>
</div>
