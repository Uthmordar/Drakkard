@extends('app')

@section('content')
<div class="container">
    <section class="w80 center">
        @include('includes/messages')
        @if(Auth::check())
            @include('includes/formAddCard')
            {!! $cards->render() !!}
            <section id="my-cards">
                @foreach($cards as $card)
                    <article class='card bg-{{$card->card->getQualifiedName()}} bg-{{$card->card->getDirectParent()}}'>
                        <ul class='nav-card'>
                            <li>
                                <a href='{{route('detachCard', ['id'=>$card->id])}}' class='link-nav-card bg-red'><span class='glyphicon glyphicon-trash'></span></a>
                            </li>
                            <li>
                                <a href='{{route('card.show', ['id'=>$card->id])}}' class='link-nav-card bg-blue'><span class='glyphicon glyphicon-eye-open'></span></a>
                            </li>
                        </ul>
                        {!! CardTplGenerator::generateCardContent($card, ['header-class'=>['text-content'], 'cat-ul-class'=>['cat-list'], 'url-class'=>['card-source']]) !!}
                    </article>
                @endforeach
            </section>
        @endif
    </section>
</div>
<div class="error_container"></div>
@endsection

@section('script')
<script type="text/javascript">
(function(ctx){
    "use strict";
    var token, url, $list, $form, $url, $submit, $notifications, i, tpl;

    var formAjax={
        initialize: function(form){
            $form=form;
            $submit=$('input[type="submit"]');
            $url=$('input[name=url]');
            $list=$('#my-cards');
            token=$('input[name=_token]').val();
            $notifications=$('#notifications');
            self.bindEvents();
        },
        bindEvents: function(){
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
                        "returnTpl": true
                    },
                    success : function(data){
                        $submit.removeClass('active');
                        $notifications.html(data.msg);
                        for(i=0; i<data.tpl.length; i++){
                            $list.prepend(data.tpl[i]);
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

$(document).ready(function(){
    var $this;
    $('.link-detach-card').on('click', function(e){
        return confirm('Unfollow this card ?');
    });

    $('.sub-image').on('click', function(e){
        $this=$(this);
        $this.parent().siblings('.image-prop').attr('style', "background: url('"+$this.children().attr('src')+"') no-repeat center;")
    });
    window.formAjax.initialize($('#formAddCard'));
});
</script>
@endsection
