var guestAgreement;
humhub.module('agreement', function(module, require, $){

  guestAgreement = {
    init:function(prefix)
    {
      var dataForm = $(".modal-dialog.guest .modal-body form");
      dataForm.keypress(function(event)
      {
        if (event.keyCode == 13 || event.which == 13)
        {
          event.preventDefault();
          $("#beforeAgreed_"+prefix).click();
        }
      });
      $("#beforeAgreed_"+prefix).click(function(e)
      {
        $(".modal-dialog.guest").addClass("loading");
        $(this).attr("disabled","disabled");
        var dataPrefix = $(this).attr("data-prefix");
        e.preventDefault();
        guestAgreement.submitForm(dataPrefix);
      });
    },
    modal:function(cond,prefix)
    {
      if(cond)
      {
        $("#agreementModal_"+prefix).show();
      }
      else
      {
        $(".modal-dialog.guest").removeClass("loading");
        $("#beforeAgreed_"+prefix).removeAttr("disabled");
        $("#agreementModal_"+prefix).hide();
      }
    },
    submitForm:function(prefix)
    {
      if(!$("#agreementCheckbox_"+prefix).prop("checked"))
      {
        guestAgreement.modal(true,prefix);
        return;
      }
      $("#afterAgreed_"+prefix).click();
    },
    accept:function(prefix)
    {
      guestAgreement.modal(false,prefix);
      $("#agreementCheckbox_"+prefix).prop("checked","checked");
      $("#beforeAgreed_"+prefix).click();
    }
  };
  
});
