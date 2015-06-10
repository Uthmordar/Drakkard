@extends('app')

@section('content')
<div class="container">
    <h2>Drakkard dashboard</h2>
    <section class="w80 center">
        @if(Session::has('messageDash'))
            @if(Session::has('messageType') && Session::get('messageType')=="success")
            <p class='success bg-success'><span class='glyphicon glyphicon-ok' style='color:green;'></span>{{Session::get('messageDash')}}</p>
            @else
            <p class='error bg-danger'><span class='glyphicon glyphicon-remove' style='color:red;'></span>{{Session::get('messageDash')}}</p>
            @endif
        @endif
        <h3>Add a project</h3>
        @if(Auth::check())
            <div id='addCard'>
                {!!Form::open(['url'=>'card', 'files'=>false, 'method'=>'POST'])!!}
                    {!!Session::get('messageCardCreate')!!}
                    <div class="control-group">
                        {!!Form::label('url', 'Url')!!}
                        {!!Form::text('url', Input::old('url'), array('placeholder'=>'url to crawl', 'required'))!!}
                        {!!isset($errors)?'<span class="bg-danger">'.$errors->first('url').'</span>': ''!!}
                    </div>
                    {!!Form::submit('Add card(s)', array('class'=>'btn btn-primary'))!!}
                {!!Form::close()!!}
            </div>
            <section id="my-cards">
                @foreach($cards as $card)
                    <article class="card">
                        <ul class="nav-card">
                            <li>
                                <a href="{{route('detachCard', ['id'=>$card->id])}}" class="link-nav-card bg-red"><span class='glyphicon glyphicon-trash'></span></a>
                            </li>
                            <li>
                                <a href="{{route('card.show', ['id'=>$card->id])}}" class="link-nav-card bg-blue"><span class='glyphicon glyphicon-eye-open'></span></a>
                            </li>
                        </ul>
                        @if($card->card->name)
                            <h4>{{$card->card->name}}</h4>
                        @endif
                        <a href="{!! $card->url !!}" class="">{{$card->card->url}}</a>
                        <ul>
                        @foreach($card->categories()->get() as $cat)
                        <li>{!! $cat->name !!}</li>
                        @endforeach
                        </ul>
                        @if($card->card->image)
                            @if(is_array($card->card->image))
                                <div class="image-prop" style="background: url('{{$card->card->image[0]}}') no-repeat center;"></div>
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
                                <iframe width="100%" height="160" src="{{$card->card->video}}" frameborder="0"></iframe>
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
