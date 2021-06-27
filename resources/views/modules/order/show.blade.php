@extends('layouts.app1')


@section('content')
    <div class="tabbable tabs-top">
    <ul class="nav nav-tabs">
        <li class="active"><a href="{{route('module.order.index')}}">Список заявок</a></li>

        <li ><a href="{{route('module.order.create')}}">Добавление заявки</a></li>

        <li><a href="/admin/order/edit">Редактирование заявки</a></li>

        <li ><a href="{{route('option.list', 'order')}}">Настройка полей</a></li>

    </ul>
    <div class="tab-content">

        <table class="table-striped" width="100%">

            @foreach($option as $o)
                <tr><td align="left">{{$o->name_ru}} </td>
                @if ($o["type_field"]=='i11')
                    <td>{{$o->name_en!=''?'<a rel="image" href="/upload/images/'.$order["".$o["name_en"].""].'"><img src="/upload/images/'.$order["".$o["name_en"].""].'" width="200"></a>':''}}</td></tr>
                @elseif($o["type_field"]=='i12')
                    <td><a href="/upload/files/{{$order["".$o["name_en"].""]}}">Скачать</a></td></tr>
                @else
                    @if($o->name_en == 'map')
                    <td><a class="map iframe" href="{{str_replace('&output=embed','',$order["".$o["name_en"].""])}}&output=embed">Карта проезда</a></td></tr>
                    @else
                    <td>{{$order["".$o["name_en"].""]}}</td></tr>
                    @endif
                @endif
            @endforeach

        </table> <br />
        <br />

        <a href="javascript:history.go(-1)">Вернуться назад</a>
    </div>
</div>
    @endsection