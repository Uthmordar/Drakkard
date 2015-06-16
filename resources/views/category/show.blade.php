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
<script type="text/javascript">
    $(function(){
        var $this;
        $('.link-detach-card').on('click', function(e){
            return confirm('Unfollow this card ?');
        });
       
        $('.sub-image').on('click', function(e){
            $this=$(this);
            $this.parent().siblings('.image-prop').attr('style', "background: url('"+$this.children().attr('src')+"') no-repeat center;")
        });
   });
</script>
@endsection
