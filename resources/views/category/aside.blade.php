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