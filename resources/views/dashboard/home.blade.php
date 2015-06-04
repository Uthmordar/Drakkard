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
        @endif
    </section>
</div>
@endsection
