!function(e){"use strict";var s,r,n,a,t,o,c,l,p,i={initialize:function(e,r){a=e,o=$('input[type="submit"]'),t=$("input[name=url]"),n=$("#my-cards"),s=$("input[name=_token]").val(),c=$("#notifications"),u.bindEvents(r)},bindEvents:function(e){a.submit(function(a){return a.preventDefault(),o.addClass("active"),t.parent().removeClass("has-error").children(".error-url").remove(),r=t.val(),$.ajax({type:"POST",url:"/card",data:{url:r,_token:s,returnTpl:e},success:function(e){for(o.removeClass("active"),p="success"===e.msgType?"<p class='message success bg-success'><span class='glyphicon glyphicon-ok' style='color:green;'></span>"+e.msg+"</p>":"<p class='message success bg-danger'><span class='glyphicon glyphicon-remove' style='color:red;'></span>"+e.msg+"</p>",c.html(p),l=0;e.tpl.length>0&&6>l;)n.find(".card").length>6&&n.find(".card").last().remove(),n.prepend(e.tpl.pop()),l++},error:function(e){$(".error_container").html(e.responseText),o.removeClass("active"),t.parent().addClass("has-error").append('<span class="error-url bg-danger">'+JSON.parse(e.responseText).url+"</span>")}},"json"),!1})}};e.formAjax=i;var u=i}(window);