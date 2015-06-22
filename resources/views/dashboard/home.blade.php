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
                                <a href='{{route('detachCard', ['id'=>$card->id])}}' class='link-nav-card bg-red link-detach-card'><span class='glyphicon glyphicon-trash'></span></a>
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
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="error_container"></div>
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
    window.formAjax.initialize($('#formAddCard'), true);
});
</script>
@endsection
