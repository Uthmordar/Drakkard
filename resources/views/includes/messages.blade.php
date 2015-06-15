<section id='notifications'>
@if(Session::has('messageDash'))
    @if(Session::has('messageType') && Session::get('messageType')=="success")
    <p class='message success bg-success'><span class='glyphicon glyphicon-ok' style='color:green;'></span>{{Session::get('messageDash')}}</p>
    @else
    <p class='message error bg-danger'><span class='glyphicon glyphicon-remove' style='color:red;'></span>{{Session::get('messageDash')}}</p>
    @endif
@endif
</section>