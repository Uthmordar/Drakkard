@extends('app')

@section('content')
<div class="container">
    <section class="w80 center">
        @if(Session::has('messageDash'))
            @if(Session::has('messageType') && Session::get('messageType')=="success")
            <p class='success bg-success'><span class='glyphicon glyphicon-ok' style='color:green;'></span>{{Session::get('messageDash')}}</p>
            @else
            <p class='error bg-danger'><span class='glyphicon glyphicon-remove' style='color:red;'></span>{{Session::get('messageDash')}}</p>
            @endif
        @endif
        @if(Auth::check())
            <div id='addCard'>
                {!!Form::open(['url'=>'card', 'files'=>false, 'method'=>'POST'])!!}
                    {!!Session::get('messageCardCreate')!!}
                    <div class="form-group {{ ($errors->first('url'))? 'has-error' : '' }}">
                        {!!Form::text('url', Input::old('url'), array('placeholder'=>'url to crawl', 'class'=>'form-control', 'required'))!!}
                        {!!Form::submit('Add card(s)', array('class'=>'btn btn-primary'))!!}
                        {!! ($errors->first('url'))?'<span class="bg-danger">'.$errors->first('url').'</span>': ''!!}
                    </div>
                {!!Form::close()!!}
            </div>
            {!! $cards->render() !!}
            <section id="my-cards">
                @foreach($cards as $card)
                    <article class="card {{$card->card->getQualifiedName()}} {{$card->card->getDirectParent()}}">
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
                                <h4 class="text-uppercase">{{$card->card->name}}</h4>
                            @endif
                            <span>Source: <a href="{!! $card->url !!}" class="card-source">{{$card->card->url}}</a></span>
                            <ul class='cat-list'>
                            @foreach($card->categories()->get() as $cat)
                            <li>{!! $cat->name !!}</li>
                            @endforeach
                            </ul>
                            @if(empty($card->card->image) && !$card->card->video && $card->card->description)
                            <p>{{$card->card->description}}</p>
                            @endif
                        </div>
                        @if($card->card->image && !$card->card->video)
                            @if(is_array($card->card->image))
                                <div class="image-prop image-multiple" style="background: url('{{$card->card->image[0]}}') no-repeat center;"></div>
                                <ul class='list-image'>
                                    @foreach($card->card->image as $src)
                                    <li><img src='{{$src}}'/></li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="image-prop" style="background: url('{{$card->card->image}}')no-repeat center;"></div>
                            @endif
                        @endif
                        @if($card->card->video)
                            @if(strpos($card->card->video, 'youtube'))
                                <iframe width="100%" height="233" src="{{$card->card->video}}" frameborder="0"></iframe>
                            @else
                                <video>
                                    @foreach($card->card->video as $vid)
                                        <source src='{{$vid}}'/>
                                    @endforeach
                                </video>
                            @endif
                        @endif
                    </article>
                @endforeach
            </section>
        @endif
    </section>
</div>
@endsection

@section('script')
<script type="text/javascript">
    $(function(){
       $('.link-detach-card').on('click', function(e){
           return confirm('Unfollow this card ?');
       });
   });
</script>
@endsection
