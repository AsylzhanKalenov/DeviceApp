@extends('layouts.app1')
@section('content')
<h4>Ремонтые работы</h4>
@if($remont)
<table class="table-striped table-hover table-border" width="100%">
    <tr>
        <th align="center">Поставщик</th>
        <th align="center">Работы</th>
        <th align="center">Статус</th>
        <th align="center">Дата начала</th>
        <th align="center">Дата окончания</th>
        <th align="center"></th>
    </tr>
@foreach($remont as $r)
        <tr>
            <td align="center" style="padding:5px;">{{get_service($r->idser, 'name')}}</td>
            <td align="center" style="padding:5px;">{{$r->rabota}}</td>
            <td align="center" style="padding:5px;">{{$r->stat}}</td>
            <td align="center" style="padding:5px;">{{$r->data1!='0000-00-00'?$r->data1:''}}</td>
            <td align="center" style="padding:5px;">{{$r->data2!='0000-00-00'?$r->data2:''}}</td>
            <td align="center" style="padding:5px;"><a href="/operator/remont/show_remont/" title="Показать"><i class="icon-circle-arrow-up"></i></a></td>
        </tr>
@endforeach

</table>
    @endif
<br />
<h4>Отправить в ремонт</h4>
<form action="/operator/remont/show/" method="post" rel="ajax_form">
    <input type="hidden" name="id" value="">
    <table class="table-striped" width="100%">
        <tr>
            <td> Поставщик <span style="color:red">*</span></td>
            <td><select name="idser" id="idser" alt="" rel="req">
                    @foreach($service as $s)
                        <option value="{{$s->id_user}}">{{$s->name}}</option>
                    @endforeach
                </select></td>
        </tr>

    @foreach($option as $o)
    @if($o->name_en=='comm' || $o->name_en=='stat' || $o->name_en=='rabota')
                <tr><td align="left">{{$o->name_ru}}{!!$o->required_fields==1?'<span style="color:red">*</span>':''!!}</td>
                <td>{!!html_entity_decode(input_view($o->id,'remont','id',$id,'',1))!!}</td></tr>
    @endif
    @endforeach
        <tr>
            <td></td>
            <td><input type="button" id="send" value="Добавить" class="btn"></td>
        </tr>
    </table>
</form>
<br />
<a href="javascript:history.go(-1)">Вернуться назад</a>
@endsection