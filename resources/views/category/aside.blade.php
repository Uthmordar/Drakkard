<aside id="categories-menu">
    <div class="logo">
        <a href="{{url('/dashboard')}}"><img src="{{asset('images/logo_drakkard.png')}}"/></a>
    </div>
    <nav>
        <ul>
            <li><a href='{{route('category.index')}}'>All</a></li>
            @if(!empty($catMenu))
                @foreach($catMenu as $id=>$firstLevel)
                <li>
                    <a href="{{route('category.show', ['id'=>$id])}}" class='color-{{$firstLevel['name']}}'>{{$firstLevel['name']}}({{$firstLevel['count']}})</a>
                    @if(!empty($firstLevel['children']))
                    <ul>
                        @foreach($firstLevel['children'] as $cId=>$child)
                        <li><a href="{{route('category.show', ['id'=>$cId])}}" class='color-{{$child['name']}}'>{{$child['name']}}({{$child['count']}})</a></li>
                        @endforeach
                    </ul>
                    @endif
                </li>
                @endforeach
            @endif
        </ul>
    </nav>
</aside>