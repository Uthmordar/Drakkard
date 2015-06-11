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
        @endif
        <article class="big-card bg-{{$card->card->getQualifiedName()}} bg-{{$card->card->getDirectParent()}}">
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
            </ul>
            <div class="text-content">
                @if($card->card->name)
                    <h2 class="text-uppercase">{{(is_array($card->card->name))? $card->card->name[0] : $card->card->name }}</h2>
                @endif
                <span>Source: <a href="{!! (is_array($card->url))? $card->url[0] : $card->url !!}" class="card-source">{{(is_array($card->url))? $card->url[0] : $card->url}}</a></span>
                <ul class='cat-list'>
                @foreach($card->categories()->get() as $cat)
                <li><a href="{{route('category.show', ['id'=>$cat->id])}}">{!! $cat->name !!}</a></li>
                @endforeach
                </ul>
                <section>
                    <ul>
                    @foreach($card->card->properties as $p)
                        @if(!in_array($p, ['name', 'url', 'image', 'video']))
                        <li class='properties'>
                            @if(is_array($card->card->$p))
                            <span class='bold'>{{TplFilters::toNormal($p)}}</span> :
                            <ul class='no-padding'>
                                @foreach($card->card->$p as $r)
                                    @if(is_object($r))
                                        @if($r instanceof \Datetime)
                                        <li>{{$r->format("d-m-Y H:i:s")}}</li>
                                        @else
                                        <?php $subCard=$r; ?>
                                        <li class='ln-bl'>
                                            <article class="card bg-{{$subCard->getQualifiedName()}} bg-{{$subCard->getDirectParent()}}">
                                                <div class="text-content">
                                                    @if($subCard->name)
                                                        <h4 class="text-uppercase">{{(is_array($subCard->name))? $subCard->name[0] : $subCard->name }}</h4>
                                                    @endif
                                                    <span>Source: <a href="{!! (is_array($subCard->url))? $subCard->url[0] : $subCard->url !!}" class="card-source">{{(is_array($subCard->url))? $subCard->url[0] : $subCard->url}}</a></span>
                                                    @if(!$subCard->image && !$subCard->video && $subCard->description)
                                                    <p>{{$subCard->description}}</p>
                                                    @endif
                                                </div>
                                                @if($subCard->image && !$subCard->video)
                                                <section class="media-block">
                                                    @if(is_array($subCard->image))
                                                        <div class="image-prop image-multiple" style="background: url('{{$subCard->image[0]}}') no-repeat center;"></div>
                                                        <ul class='list-image'>
                                                            @foreach($subCard->image as $src)
                                                            <li><img src='{{$src}}'/></li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        <div class="image-prop" style="background: url('{{$subCard->image}}')no-repeat center;"></div>
                                                    @endif
                                                </section>
                                                @endif
                                                @if($subCard->video)
                                                <section class="media-block">
                                                    @if(strpos($subCard->video, 'youtube'))
                                                        <iframe width="100%" height="233" src="{{$subCard->video}}" frameborder="0"></iframe>
                                                    @else
                                                        <video>
                                                            @foreach($subCard->video as $vid)
                                                                <source src='{{$vid}}'/>
                                                            @endforeach
                                                        </video>
                                                    @endif
                                                </section>
                                                @endif
                                            </article>
                                        </li>
                                        @endif
                                    @else
                                    <li>{!!TplFilters::urlFormat($r)!!}</li>
                                    @endif
                                @endforeach
                            </ul>
                            @else
                            <span class='bold'>{{TplFilters::toNormal($p)}}</span> : 
                                @if(is_object($card->card->$p))
                                    @if($card->card->$p instanceof \Datetime)
                                    {{$card->card->$p->format("d-m-Y H:i:s")}}
                                    @endif
                                @else
                                {!!TplFilters::urlFormat($card->card->$p)!!}
                                @endif
                            @endif
                        </li>
                        @endif
                    @endforeach
                    </ul>
                </section>
            </div>
            @if($card->card->image && !$card->card->video)
            <section class="media-block">
                @if(is_array($card->card->image))
                    <ul class='list-image'>
                        @foreach($card->card->image as $src)
                        <li><img src='{{$src}}'/></li>
                        @endforeach
                    </ul>
                @else
                    <img src='{{$card->card->image}}' />
                @endif
            </section>
            @endif
            @if($card->card->video)
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
    </section>
</div>
<aside id="categories-menu">
    <nav>
        <ul>
            <li><a href='{{route('category.index')}}'>All</a></li>
            @foreach($catMenu as $id=>$firstLevel)
            <li>
                <a href="{{route('category.show', ['id'=>$id])}}" class='color-{{$firstLevel['name']}}'>{{$firstLevel['name']}}({{$firstLevel['count']}})</a>
                <ul>
                    @foreach($firstLevel['children'] as $cId=>$child)
                    <li><a href="{{route('category.show', ['id'=>$cId])}}" class='color-{{$child['name']}}'>{{$child['name']}}({{$child['count']}})</a></li>
                    @endforeach
                </ul>
            </li>
            @endforeach
        </ul>
    </nav>
</aside>
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
