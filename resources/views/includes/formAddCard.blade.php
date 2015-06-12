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