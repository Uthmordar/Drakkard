@extends('app')

@section('content')
<div class="container">
    <section class="w80 center">
        @include('includes/messages')
        @if(Auth::check())
            @include('includes/formAddCard')
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
                <li>Followers: {{count($card->users()->get())}}</li>
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
                @if($card->card->location)
                <iframe width="100%" height="450" frameborder="0" style="border:0"
                src="https://www.google.com/maps/embed/v1/place?q={{urlencode($card->card->location)}}&key={{Config::get('services.google_api_key')}}"></iframe>
                @endif
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
                                        <li>{{$r->format("d-m-Y")}}</li>
                                        @else
                                        <?php $subCard=$r; ?>
                                        <li class='ln-bl'>
                                            <article class="card bg-{{$subCard->getQualifiedName()}} bg-{{$subCard->getDirectParent()}}">
                                                <div class="text-content">
                                                    @if($subCard->name)
                                                        <h4 class="text-uppercase">{{(is_array($subCard->name))? utf8_decode($subCard->name[0]) : utf8_decode($subCard->name) }}</h4>
                                                    @endif
                                                    <span>Source: <a href="{!! (is_array($subCard->url))? $subCard->url[0] : $subCard->url !!}" class="card-source">{{(is_array($subCard->url))? $subCard->url[0] : $subCard->url}}</a></span>
                                                    @if(!$subCard->image && !$subCard->video && $subCard->description && !$subCard->location)
                                                    <p>{{$subCard->description}}</p>
                                                    @endif
                                                </div>
                                                @if($subCard->location)
                                                <iframe width="100%" height="233" frameborder="0" style="border:0"
                                                src="https://www.google.com/maps/embed/v1/place?q={{urlencode($card->card->location)}}&key={{Config::get('services.google_api_key')}}"></iframe>
                                                @elseif($subCard->video)
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
                                                @elseif($subCard->image)
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
                                    {{$card->card->$p->format("d-m-Y")}}
                                    @else
                                    <li class='ln-bl'>
                                        <article class="card bg-{{$card->card->$p->getQualifiedName()}} bg-{{$card->card->$p->getDirectParent()}}">
                                            <div class="text-content">
                                                @if($card->card->$p->name)
                                                    <h4 class="text-uppercase">{{(is_array($card->card->$p->name))? utf8_decode($card->card->$p->name[0]) : utf8_decode($card->card->$p->name) }}</h4>
                                                @endif
                                                <span>Source: <a href="{!! (is_array($card->card->$p->url))? $card->card->$p->url[0] : $card->card->$p->url !!}" class="card-source">{{(is_array($card->card->$p->url))? $card->card->$p->url[0] : $card->card->$p->url}}</a></span>
                                                @if(!$card->card->$p->image && !$card->card->$p->video && $card->card->$p->description && !$card->card->$p->location)
                                                <p>{{$card->card->$p->description}}</p>
                                                @endif
                                            </div>
                                            @if($card->card->$p->location)
                                            <iframe width="100%" height="233" frameborder="0" style="border:0"
                                            src="https://www.google.com/maps/embed/v1/place?q={{urlencode($card->card->$p->location)}}&key={{Config::get('services.google_api_key')}}"></iframe>
                                            @elseif($card->card->$p->video)
                                            <section class="media-block">
                                                @if(strpos($card->card->$p->video, 'youtube'))
                                                    <iframe width="100%" height="233" src="{{$card->card->$p->video}}" frameborder="0"></iframe>
                                                @else
                                                    <video>
                                                        @foreach($card->card->$p->video as $vid)
                                                            <source src='{{$vid}}'/>
                                                        @endforeach
                                                    </video>
                                                @endif
                                            </section>
                                            @elseif($card->card->$p->image)
                                            <section class="media-block">
                                                @if(is_array($card->card->$p->image))
                                                    <div class="image-prop image-multiple" style="background: url('{{$card->card->$p->image[0]}}') no-repeat center;"></div>
                                                    <ul class='list-image'>
                                                        @foreach($card->card->$p->image as $src)
                                                        <li><img src='{{$src}}'/></li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <div class="image-prop" style="background: url('{{$card->card->$p->image}}')no-repeat center;"></div>
                                                @endif
                                            </section>
                                            @endif
                                        </article>
                                    </li>
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
            @if($card->card->image)
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
                    <iframe width="100%" height="450" src="{{$card->card->video}}" frameborder="0"></iframe>
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
@endsection

@section('script')
<script type='text/javascript' src='{{asset('js/min/formAddCard.js')}}'></script>
<script type="text/javascript">
$(document).ready(function(){
    $(document).on('click', '.link-detach-card', function(e){
        return confirm('Unfollow this card ?');
    });

    window.formAjax.initialize($('#formAddCard'), false);
});
</script>
@endsection
