var guestUsers;
humhub.module('guests', function(module, require, $) {

  guestUsers = {
    que:{
      submissions:{},
      add:function(prefix)
      {
        guestUsers.que.submissions[prefix] = prefix;
      },
      remove:function(prefix)
      {
        if(typeof guestUsers.que.submissions[prefix] != "undefined")
        {
          delete guestUsers.que.submissions[prefix];
        }
      }
    },
    onEnter:function(event,trg)
    {
      if (event.keyCode == 13 || event.which == 13)
      {
        event.preventDefault();
        var isComment = $(trg).attr("data-is-comment");
        var postId = $(trg).attr("data-post-id");
        var modelId = $(trg).attr("data-model-id");
        var guid = $(trg).attr("data-guid");
        if(isComment)
        {
          $("#commentSubmitBtn_"+modelId).click();
        }
        else
        {
          $("#postSubmitBtn_"+modelId).click();
        }
      }
    },
    getData:function(modelId,isPost)
    {
      $(".custom-error").remove();

      var result = {
        validate:false,
        errors:0,
        msg:"",
        alias:"",
        email:"",
        agreed:false
      };

      var msgElm = $("#newCommentForm_humhubmodulespostmodelsPost_"+modelId);

      result.msg = String($("#newCommentForm_humhubmodulespostmodelsPost_"+modelId+"_input").val());
      if(result.msg.length < 1)
      {
        result.errors++;
        msgElm.after("<div class='custom-error'>This is required.</div>");
      }

      var aliasElm = $("#guest_alias_"+modelId+"_main");
      result.alias = $.trim(aliasElm.val());
      result.alias = String(result.alias).replace(/[^.a-zA-Z0-9 ]/g, "");
      if(result.alias.length < 2)
      {
        result.errors++;
        aliasElm.after("<div class='custom-error'>This is required.</div>");
      }

      var emailElm = $("#guest_email_"+modelId+"_main");
      result.email = $.trim(emailElm.val());
      if(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(result.email) == false)
      {
        result.errors++;
        emailElm.after("<div class='custom-error'>Valid email is required.</div>");
      }

      var agreeElm = $("#agreementCheckbox_"+modelId+"_main");
      result.agreed = agreeElm.prop("checked");
      if(!result.agreed)
      {
        result.errors++;
        agreeElm.parent().after("<div class='custom-error'>Agreement is required.</div>");
      }

      result.validate = (result.errors < 1);
      return result;
    },
    toggleLoader:function(show,modelId)
    {
      var notInQue = (typeof guestUsers.que.submissions[modelId] == "undefined");
      if(show)
      {
        guestUsers.que.add(modelId);
        $("#newCommentForm_humhubmodulespostmodelsPost_"+modelId+"_input").parent().addClass("loading on-elm");
      }
      else
      {
        guestUsers.que.remove(modelId);
        $("#newCommentForm_humhubmodulespostmodelsPost_"+modelId+"_input").parent().removeClass("loading on-elm");
      }
      return notInQue;
    },
    onSuccess:function(url,modelId,isPost)
    {
      window.location.href = url+"?id="+modelId+"&p="+isPost;
    },
    focusAt:function(isComment,modelId)
    {
      var trgElm;
      if(isComment)
      {
        trgElm = $("#comment_"+modelId);
      }
      else
      {
        trgElm = $(".wall_humhubmodulespostmodelsPost_"+modelId);
      }

      $('html, body').animate({
        scrollTop: trgElm.offset().top-300
      }, 500);
      trgElm.fadeOut(100);
      trgElm.fadeIn(2000);
    },
    submitPost:function(modelId,guid)
    {
      if(guestUsers.toggleLoader(true,modelId))
      {
        var data = this.getData(modelId,true);
        if(data.validate)
        {
          $.ajax({
            'type': 'POST',
            'url': window.location.origin+BASE_URL+"custom/guest/post",
            'data':{
              'guid': guid,
              'message':data.msg,
              'guest_alias':data.alias,
              'guest_email':data.email
            },
            'success': function(payload)
            {
              if(typeof payload.id != "undefined")
              {
                guestUsers.onSuccess(window.location.href,payload.id,true);
              }
              else
              {
                guestUsers.toggleLoader(false,modelId);
              }
            },
            'error': function(xhr, error)
            {
              console.log(error);
              guestUsers.toggleLoader(false,modelId);
            }
          });
        }
        else
        {
          guestUsers.toggleLoader(false,modelId);
        }
      }
    },
    submitComment:function(postId,modelId,guid)
    {
      if(guestUsers.toggleLoader(true,modelId))
      {
        var data = this.getData(modelId,false);
        if(data.validate)
        {
          $.ajax({
            'type': 'POST',
            'url': window.location.origin+BASE_URL+"custom/guest/comment",
            'data':{
              'postId':postId,
              'contentId':modelId,
              'guid':guid,
              'message':data.msg,
              'guest_alias':data.alias,
              'guest_email':data.email
            },
            'success': function(payload)
            {
              if(typeof payload.id != "undefined")
              {
                //guestUsers.onSuccess(window.location.origin+BASE_URL+"questions/"+postId,payload.id,false);
                guestUsers.onSuccess(window.location.href+"?contentId="+postId,payload.id,false);
              }
              else
              {
                guestUsers.toggleLoader(false,modelId);
              }
            },
            'error': function(xhr, error)
            {
              console.log(error);
              guestUsers.toggleLoader(false,modelId);
            }
          });
        }
        else
        {
          guestUsers.toggleLoader(false,modelId);
        }
      }
    },
    post:function(modelId,guid)
    {
      this.submitPost(modelId,guid);
      this.slide(modelId+"_main",true);
    },
    comment:function(postId,modelId,guid)
    {
      this.submitComment(postId,modelId,guid);
      this.slide(modelId+"_main",true);
    },
    slide:function(prefix,isDown)
    {
      isDown = isDown || false;
      var $trgSection = $("#postAsGuestSection_"+prefix);
      var isClosed = ($trgSection.css("display") == "none");
      if(isDown)
      {
        $trgSection.slideDown(200);
        $('html, body').animate({
          scrollTop: $("#guest_alias_"+prefix).offset().top-500
        }, 500);
      }
      else
      {
        $trgSection.slideToggle(200);
        if(isClosed)
        {
          $('html, body').animate({
            scrollTop: $("#guest_alias_"+prefix).offset().top-500
          }, 500);
        }
      }

      if(isClosed)
      {
        $("#guest_alias_"+prefix).focus();
      }
    }
  };

});
