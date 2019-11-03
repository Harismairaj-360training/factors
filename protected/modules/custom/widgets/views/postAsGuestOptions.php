<?php
\humhub\modules\custom\assets\GuestAsset::register($this);
use yii\helpers\Url;
$prefix = $modelId."_main";
$guest_alias_id = 'guest_alias_'.$prefix;
$guest_email_id = 'guest_email_'.$prefix;
?>
<?= humhub\modules\custom\widgets\Agreement::widget(['prefix'=>$prefix]); ?>
<div>
  <div class="btns">
    <!-- ?php if($isComment){ ?>
      <br>
    < ?php } ? -->
    <!-- a href="#" data-action-click="ui.modal.load" data-action-url="< ?= Url::toRoute('/user/auth/login'); ?>">Sign Up / Login</a> |-->

    <!-- ?php if($isComment){ ?>
      <a href="javascript:;" onclick="guestUsers.slide('< ?= $prefix ?>')">Comment as Guest</a>
    < ?php }else{ ?>
      <a href="javascript:;" onclick="guestUsers.slide('< ?= $prefix ?>')">Post as Guest</a>
    < ?php } ? -->
  </div>
  <div id="postAsGuestSection_<?= $prefix ?>" style="display:block;">
    <div class="guest-form form-inline">
      <div class="form-group field-guest_alias">
        <label class="control-label" for="<?= $guest_alias_id ?>"></label>
        <input type="text" name="guest_alias" class="form-control" id="<?= $guest_alias_id ?>" data-post-id="<?= $postId ?>" data-model-id="<?= $modelId ?>" data-guid="<?= $sguid ?>" data-is-comment="<?= $isComment ?>" placeholder="Name or Alias" onkeypress="guestUsers.onEnter(event,this)"/>
      </div>
      <div class="form-group field-guest_email">
        <label class="control-label" for="<?= $guest_email_id ?>"></label>
          <input type="text" name="guest_email" class="form-control" id="<?= $guest_email_id ?>" data-post-id="<?= $postId ?>" data-model-id="<?= $modelId ?>" data-guid="<?= $sguid ?>" data-is-comment="<?= $isComment ?>" placeholder="E-mail" onkeydown="guestUsers.onEnter(event,this)"/>
      </div>
    </div>
    <small class="text-muted">
      <label class="btns">
        <input type="checkbox" name="agreementCheckbox" id="agreementCheckbox_<?= $prefix; ?>" style="display:inline" data-post-id="<?= $postId ?>" data-model-id="<?= $modelId ?>" data-guid="<?= $sguid ?>" data-is-comment="<?= $isComment ?>" onkeydown="guestUsers.onEnter(event,this)">
        &nbsp;&nbsp;I agree with the <a href="javascript:;" onclick="guestAgreement.modal(true,'<?= $prefix ?>');">Terms And Use</a>
         and <a href="<?php echo STORE_URL."privacy-policy/"; ?>" target="_blank">Privacy Policy</a>
      </label>
    </small>
  </div>
</div>
