@extends('layouts.app1')


@section('content')
<div class="tabbable tabs-top">
    <ul class="nav nav-tabs">
        <li class="active"><a href="{{route('operator.cabinet.list')}}">Список кабинетов</a></li>
    </ul>
    <div class="tab-content">

        <table class="table-striped" width="100%">
            <td>Медицинский центр</td> <td>
            {{$center->name}}
            </td>
            @foreach($option as $k=>$v)
                <tr><td align="left">{{$v["name_ru"]}} </td>
            @if($v["type_field"] == 'i11')
                <td>{!! ($cabinet["".$v["name_en"].""]!=''?'<a rel="image" href="/upload/images/'.$cabinet["".$v["name_en"].""].'"><img src="/upload/images/'.$cabinet["".$v["name_en"].""].'" width="200"></a>':'')!!}</td></tr>
            @elseif($v["type_field"]=='i12')
                <td><a href="/upload/files/{{$cabinet["".$v["name_en"].""]}}">Скачать</a></td></tr>
            @else
                <td>{{$cabinet["".$v["name_en"].""]}}</td></tr>
            @endif
            @endforeach

        </table> <br />
        <br />

        <a href="javascript:history.go(-1)">Вернуться назад</a>
    </div>
</div>
@endsection
