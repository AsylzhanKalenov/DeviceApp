@extends('layouts.app1')

@section('pageTitle', 'Перемещение')
@section('content')
<script src="/js/jquery-1.4.2.min.js"></script>

<script>

    $(function(){

        show_cab();

    })

    function show_cab(){

        var id = $('#idc').val();
        $.ajax({
            url: "{{ route('api.catalog.add') }}",
            method: 'POST',
            data: {id: id, classes: 4, _token: $('input[name="_token"]').val()},
            dataType: 'json',
            success: function (data) {
                $('#idcab').html(data.str);

            }
        });

    }

</script>

<h5>Перемещение</h5>

@if (@$error!='')

<div class="alert alert-error">
    Ошибка перемещения. {{$error}}
</div>
@endif
@if (@$success!='')

<div class="alert alert-success">
    Перемещено
</div>
@endif
<form action="{{route('admin.peremesh.place_change', $catalog["id"])}}" method="post">
@csrf
    <input type="hidden" name="id" value="{{$catalog["id"]}}">

    Мед центр <select name="idc" id="idc" onchange="show_cab();">

    @foreach($center as $c)
            <option value="{{$c["id"]}}">{{$c["name"]}}</option>
    @endforeach

    </select>

    &nbsp;&nbsp;&nbsp;&nbsp;

    Кабинет

    <select name="idcab" id="idcab">

        <option value=""></option>

    </select>

    <input type="submit" value="Переместить" class="btn" style="vertical-align:middle; margin-top:-12px;">

</form>


<table class="table-striped" width="100%">

<tr class="table-striped" width="100%">
    <tr>
        <td>Классификация</td> <td>
            {{get_razdel($catalog->ids, 'name')}}
        </td>
    </tr>
    <tr>
        <td>Категория</td> <td>
            {{get_razdel($catalog->ids1, 'name')}}
        </td>
    </tr>
    <tr>
        <td>Наименование</td> <td>
            {{get_razdel($catalog->ids2, 'name')}}
        </td>
    </tr>
    <tr>
        <td>Модель</td> <td>
            {{get_razdel($catalog->ids3, 'name')}}
        </td>
    </tr>
    <tr>
        <td>Производитель</td> <td>
            {{get_razdel($catalog->ids4, 'name')}}
        </td>
    </tr>

    @foreach($options as $o)
        <tr><td align="left">{{$o["name_ru"]}} </td>
        @if($o["type_field"] == 'i11')
            @if($catalog[$o["name_en"]]!='')
                    <td><a rel="image"  href="/upload/images/{{$catalog[$o["name_en"]]}}"><img src="/upload/images/{{$catalog[$o["name_en"]]}}" width="200"></a></td></tr>
            @else
            <td>&nbsp;</td>
            @endif
        @elseif($o["type_field"] == 'i12')
            <td><a href="/upload/files/{{$catalog[$o["name_en"]]}}">Скачать</a></td></tr>
        @else
            <td>{{$catalog[$o["name_en"]]}}</td></tr>
        @endif
        @if($o["name_en"] == 'in_nom')
            <tr>
                <td>Медицинский центр владелец</td> <td>
                    {{get_center($catalog->idc1, 'name')}}
                </td>
            </tr>
            <tr>
                <td>Медицинский центр пользователь</td> <td>
                    {{get_center($catalog->idc, 'name')}}
                </td>
            </tr>
            <tr>
                <td>Кабинет</td> <td>
                    {{get_cabinet($catalog->idcab, 'nomer').'('.get_cabinet($catalog->idcab, 'deayt').')'}}
        </td>
    </tr>
        @endif

    @endforeach

</table> <br />

<br />
    @endsection
