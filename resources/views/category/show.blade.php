@extends('app')

@section('content')
<div class="container">
    <section class="w80 center">
        @if(Session::has('messageDash'))
            @if(Session::has('messageType') && Session::get('messageType')=="success")
            <p class='message success bg-success'><span class='glyphicon glyphicon-ok' style='color:green;'></span>{{Session::get('messageDash')}}</p>
            @else
            <p class='message error bg-danger'><span class='glyphicon glyphicon-remove' style='color:red;'></span>{{Session::get('messageDash')}}</p>
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
                                <li><img src='{{$src}}'/></li>
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
