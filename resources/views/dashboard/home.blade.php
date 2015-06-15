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
                    <article class="card bg-{{$card->card->getQualifiedName()}} bg-{{$card->card->getDirectParent()}}">
                        <ul class="nav-card">
                            <li>
                                <a href="{{route('detachCard', ['id'=>$card->id])}}" class="link-nav-card bg-red"><span class='glyphicon glyphicon-trash'></span></a>
                            </li>
                            <li>
                                <a href="{{route('card.show', ['id'=>$card->id])}}" class="link-nav-card bg-blue"><span class='glyphicon glyphicon-eye-open'></span></a>
                            </li>
                        </ul>
                        <div class="text-content">
                            @if($card->card->name)
                                <h4 class="text-uppercase">{{(is_array($card->card->name))? $card->card->name[0] : $card->card->name }}</h4>
                            @endif
                            <span>Source: <a href="{!! (is_array($card->url))? $card->url[0] : $card->url !!}" class="card-source">{{(is_array($card->url))? $card->url[0] : $card->url}}</a></span>
                            <ul class='cat-list'>
                            @foreach($card->categories()->get() as $cat)
                            <li><a href="{{route('category.show', ['id'=>$cat->id])}}">{!! $cat->name !!}</a></li>
                            @endforeach
                            </ul>
                            @if(!$card->card->image && !$card->card->video && $card->card->description && !$card->card->location)
                            <p>{{$card->card->description}}</p>
                            @endif
                        </div>
                        @if($card->card->location)
                        <iframe width="100%" height="233" frameborder="0" style="border:0"
                        src="https://www.google.com/maps/embed/v1/place?q={{urlencode($card->card->location)}}&key={{Config::get('services.google_api_key')}}"></iframe>
                        @endif
                        @if($card->card->image && !$card->card->video && !$card->card->location)
                        <section class="media-block">
                            @if(is_array($card->card->image))
                                <div class="image-prop image-multiple" style="background: url('{{$card->card->image[0]}}') no-repeat center;"></div>
                                <ul class='list-image'>
                                    @foreach($card->card->image as $src)
                                    <li class='sub-image'><img src='{{$src}}'/></li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="image-prop" style="background: url('{{$card->card->image}}')no-repeat center;"></div>
                            @endif
                        </section>
                        @endif
                        @if($card->card->video && !$card->card->location)
                        <section class="media-block">
                            @if(strpos($card->card->video, 'youtube'))
                                <iframe width="100%" height="233" src="{{$card->card->video}}" frameborder="0"></iframe>
                            @else
                                <video>
                                    @foreach($card->card->video as $vid)
                                        <source src='{{$vid}}'/>
                                    @endforeach
                                </video>
                            @endif
                        </section>
                        @endif
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
    var token, url, $list, $form, $url, $submit, $notifications;

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
                        "_token": token
                    },
                    success : function(data){
                        $submit.removeClass('active');
                        $notifications.html(data.msg);
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
