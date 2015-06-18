<aside id="advice-menu">
    <h4>Content you might like</h4>
    <nav>
        <ul>
            @if(!empty($adviceList))
                @foreach($adviceList as $advice=>$content)
                <h5>{{$advice}}</h5>
                <ul>
                    @foreach($content as $card)
                    <li>
                        <article class='mini-card bg-{{$card->card->getQualifiedName()}} bg-{{$card->card->getDirectParent()}}'>
                            <h6>{{$card->card->name}}</h6>
                            <a href='{{ route('card.show', ['id'=>$card->id]) }}' class='link-nav-card bg-blue'><span class='glyphicon glyphicon-eye-open'></span></a>
                            <a class='cat' href='{{route('category.show', ['id'=>CatAccessor::getCat($card->card->getQualifiedName())->id])}}'>{{$card->card->getQualifiedName()}}</a>
                        </article>
                    </li>
                    @endforeach
                </ul>
                @endforeach
            @endif
        </ul>
    </nav>
</aside>