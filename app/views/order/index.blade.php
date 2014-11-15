@extends('layouts.master')
 
@section('content')
@section('head')
  <script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
{{HTML::script('js/chosen.jquery.js')}}
@stop

<h2>ORDENES</h2>
  <div class="widget">
     <div class="widget-content-white glossed">
     <div class="padded">
@if(Session::has('notice'))
<div class="alert alert-success fade in" role="alert">
  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
  <h4>{{ Session::get('notice') }}</h4>
</div>
@endif
<div id="containment-wrapper">
@foreach($coords as $coord)
<div id='draggable' value='{{$coord->table_id}}' onclick="editar({{ $coord->table_id}})" class="img-circle" style="left:{{$coord->x_pos}}px; top:{{$coord->y_pos}}px;">
{{ HTML::image('images/table.png') }}
@if($coord->table['taken'] == true)
  <div class='indicators'><h3><span class="label label-success">{{$coord->table['number']}}</span></h3>
  </div>
@else
  <div class='indicators'><h3><span class="label label-false">{{$coord->table['number']}}</span></h3>
  </div>
@endif
</div>
@endforeach
</div>
<hr>
<div id="result">
</div>
</div>
</div>
</div>
<script>

function editar(idtable){      
$.get("edi/"+ idtable, 
            function(data){
              $('#result').html("");
                if (data.success == false){
                  $('#result').load('http://localhost/restapp-rest/public/index.php/orders/create/'+idtable);
                }
                else
              $.each(data, function(i,order){
                    $('#result').load('http://localhost/restapp-rest/public/index.php/orders/edit/'+order.id);
              });
            });                         
}
$('.img-circle').on('click', function() {
$('.img-circle').removeClass('active');
    $(this).addClass('active');
});
</script>
@stop