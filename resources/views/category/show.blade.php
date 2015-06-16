@extends('app')

@section('content')
<div class="container">
    <section class="w80 center">
        @include('includes/messages')
        @if(Auth::check())
            @include('includes/formAddCard')
        @endif
        {!! $cards->render() !!}
        <section id="my-cards">
            @foreach($cards as $card)
                 <article class="card bg-{{$card->card->getQualifiedName()}} bg-{{$card->card->getDirectParent()}}">
                    <ul class="nav-card">
                        @if(Auth::check() && $card->users()->find(Auth::user()->id))
                        <li>
                            <a href="{{route('detachCard', ['id'=>$card->id])}}" class="link-nav-card bg-red"><span class='glyphicon glyphicon-trash'></span></a>
                        </li>
                        @endif
                        @if(Auth::check() && !$card->users()->find(Auth::user()->id))
                        <li>
                            <a href="{{route('attachCard', ['id'=>$card->id])}}" class="link-nav-card bg-green"><span class='glyphicon glyphicon-plus'></span></a>
                        </li>
                        @endif
                        <li>
                            <a href="{{route('card.show', ['id'=>$card->id])}}" class="link-nav-card bg-blue"><span class='glyphicon glyphicon-eye-open'></span></a>
                        </li>
                        <li><span class='link-nav-card bg-blue'><span class='iterator'>{{count($card->users()->get())}}</span></span></li>
                    </ul>
                    {!! CardTplGenerator::generateCardContent($card, ['header-class'=>['text-content'], 'cat-ul-class'=>['cat-list'], 'url-class'=>['card-source']]) !!}
                </article>
            @endforeach
        </section>
    </section>
</div>
@endsection

@section('script')
<script type='text/javascript' src='{{asset('js/min/formAddCard.js')}}'></script>
<script type="text/javascript">
$(document).ready(function(){
    var $this, video, $modal=$('#myModal'), $modalBody=$modal.find('.modal-body');
    $(document).on('click', '.link-detach-card', function(e){
        return confirm('Unfollow this card ?');
    });

    $(document).on('click', '.sub-image', function(e){
        $this=$(this);
        $this.parent().siblings('.image-prop').attr('style', "background: url('"+$this.children().attr('src')+"') no-repeat center;background-size: cover;")
    });
    $(document).on('click', '.video-block', function(e){
        $this=$(this);
        video=$this.children('.video-vignette').attr('data-vid');
        $modalBody.html("<iframe width='100%' height='400' src='"+video+"' frameborder='0'></iframe>");
        $modal.modal('show');
    });
    window.formAjax.initialize($('#formAddCard'), false);
});
</script>
@endsection
