!function(s){"use strict";var e,n,r,a,t,c,l,o,i,p={initialize:function(s,n){a=s,c=$('input[type="submit"]'),t=$("input[name=url]"),r=$("#my-cards"),e=$("input[name=_token]").val(),l=$("#notifications"),u.bindEvents(n)},bindEvents:function(s){a.submit(function(a){return a.preventDefault(),c.addClass("active"),t.parent().removeClass("has-error").children(".error-url").remove(),n=t.val(),$.ajax({type:"POST",url:"/card",data:{url:n,_token:e,returnTpl:s},success:function(s){for(c.removeClass("active"),i="success"===s.msgType?"<p class='message success bg-success'><span class='glyphicon glyphicon-ok' style='color:green;'></span>"+s.msg+"</p>":"<p class='message success bg-danger'><span class='glyphicon glyphicon-remove' style='color:red;'></span>"+s.msg+"</p>",l.html(i),o=0;s.tpl.length>0&&6>o;)r.find(".card").length>=6&&r.find(".card").first().remove(),r.append(s.tpl.shift()),o++},error:function(s){c.removeClass("active"),t.parent().addClass("has-error").append('<span class="error-url bg-danger">'+JSON.parse(s.responseText).url+"</span>")}},"json"),!1})}};s.formAjax=p;var u=p}(window);