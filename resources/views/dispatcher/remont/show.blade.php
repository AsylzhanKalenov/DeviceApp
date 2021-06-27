@extends('layouts.app1')
@section('content')
    <h4>Ремонтые работы</h4>
    @if($remont1)
        <table class="table-striped table-hover table-border" width="100%">
            <tr>
                <th align="center">Поставщик</th>
                <th align="center">Работы</th>
                <th align="center">Статус</th>
                <th align="center">Дата начала</th>
                <th align="center">Дата окончания</th>
                <th align="center"></th>
            </tr>
            @foreach($remont1 as $r)
                <tr>
                    <td align="center" style="padding:5px;">{{get_service($r->idser, 'name')}}</td>
                    <td align="center" style="padding:5px;">{{$r->rabota}}</td>
                    <td align="center" style="padding:5px;">{{$r->stat}}</td>
                    <td align="center" style="padding:5px;">{{$r->data1!='0000-00-00'?$r->data1:''}}</td>
                    <td align="center" style="padding:5px;">{{$r->data2!='0000-00-00'?$r->data2:''}}</td>
                    <td align="center" style="padding:5px;"><a href="{{route('dispatcher.remont.show_rem', $r->id)}}" title="Показать"><i class="icon-circle-arrow-up"></i></a></td>
                </tr>
            @endforeach

        </table>
    @endif
    <br />
    <h4>Отправить в ремонт</h4>
    <form action="{{route('dispatcher.remont.update', $remont->id)}}" method="post" rel="ajax_form">
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
            <tr>
                <td>Техник</td>
                <td>
                    <select name="idisp" id="idisp">
                        @foreach($isp as $i)
                            <option value="{{$i->id}}">{{$i->fio}}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <td>Описание неисправности</td>
                <td>{{$remont->comm}}
                </td>
            </tr>
            <tr>
                <td></td>
                <td><input type="button" id="send" value="Добавить" class="btn"></td>
            </tr>
        </table>
    </form>
    <br />
    <a href="javascript:history.go(-1)">Вернуться назад</a>
@endsection