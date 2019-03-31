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
      <div class="container">
        <h2>ExpertConnect - Mentoring & Discussions</h2>

        <p>You can join 360factors IT mentoring & discussions for<br>
          Free Ask Question, Give Answer, Discuss IT<br>
          Problems, Learn and Grow</p>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-md-12 text-center">
          <br><br>
          <h2>Governer Risk and Compliance Industries</h2>
          <hr>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <div class="media">
            <div class="media-left">
              <img src="<?= $this->theme->getBaseUrl(); ?>/img/paper.png" />
            </div>
            <div class="media-body">
              <h4>Banking and Financial</h4>
              <p>All the GRC tools you need integrated in one solution</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="media">
            <div class="media-left">
                <img src="<?= $this->theme->getBaseUrl(); ?>/img/arrow.png" />
            </div>
            <div class="media-body">
              <h4>Power & Utilities</h4>
              <p>Pick the modules you need for your business</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="media">
            <div class="media-left">
              <img src="<?= $this->theme->getBaseUrl(); ?>/img/paper2.png" />
            </div>
            <div class="media-body">
              <h4>Real Estate</h4>
              <p>Automation and an intuitive interface ensure easy to use</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="media">
            <div class="media-left">
              <img src="<?= $this->theme->getBaseUrl(); ?>/img/cloud.png" />
            </div>
            <div class="media-body">
              <h4>Oil & Gas</h4>
              <p>The cloud based solution can be integrated with your business in a matter of days</p>
            </div>
          </div>
        </div>
      </div>
      <br><br>
    </div>
  </div>
<?php } ?>

<div class="container">
    <div class="row">
        <div class="col-md-3">
            <?= Menu::widget(); ?>
        </div>
        <div class="col-md-9">
            <?= $content; ?>
        </div>
    </div>
</div>
