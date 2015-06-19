(function(ctx){
    "use strict";
    var token, url, $list, $form, $url, $submit, $notifications, i, msg;

    var formAjax={
        initialize: function(form, returnTpl){
            $form=form;
            $submit=$('input[type="submit"]');
            $url=$('input[name=url]');
            $list=$('#my-cards');
            token=$('input[name=_token]').val();
            $notifications=$('#notifications');
            self.bindEvents(returnTpl);
        },
        bindEvents: function(returnTpl){
            $form.submit(function(e){
                e.preventDefault();
                $submit.addClass('active');
                $url.parent().removeClass('has-error').children('.error-url').remove();
                url=$url.val();

                $.ajax({
                    type: "POST",
                    url : "/card",
                    data : {
                        "url": url,
                        "_token": token,
                        "returnTpl": returnTpl
                    },
                    success : function(data){
                        $submit.removeClass('active');
                        if(data.msgType==="success"){
                            msg="<p class='message success bg-success'><span class='glyphicon glyphicon-ok' style='color:green;'></span>" + data.msg + "</p>";
                        }else{
                            msg="<p class='message success bg-danger'><span class='glyphicon glyphicon-remove' style='color:red;'></span>" + data.msg + "</p>";
                        }
                        $notifications.html(msg);
                        i=0;
                        while(data.tpl.length>0 && i<6){
                            if($list.find('.card').length>6){
                                $list.find('.card').last().remove();
                            }
                            $list.prepend(data.tpl.pop());
                            i++;
                        }
                    },
                    error: function(error){
                        $('.error_container').html(error.responseText);
                        $submit.removeClass('active');
                        $url.parent().addClass('has-error').append('<span class="error-url bg-danger">'+ JSON.parse(error.responseText).url + '</span>');
                    }
                },"json");
                return false;
            });
        }
    };

    ctx.formAjax=formAjax;
    var self=formAjax;
})(window);