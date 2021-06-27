
@extends('layouts.app1')


@section('content')
<div class="tabbable tabs-top">
    <ul class="nav nav-tabs">
        <li ><a href="{{route('admin.catalog.index')}}">Список оборудования</a></li>

        <li ><a href="{{route('admin.catalog.create')}}">Добавление оборудования</a></li>

        <li><a href="/admin/catalog/edit">Редактирование оборудования</a></li>

        <li ><a href="{{route('option.list', 'catalog')}}">Настройка полей</a></li>

    </ul>
    <div class="tab-content">

        <table class="table-striped" width="100%">
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

            @php
                $arr_img = ['png', 'jpg', 'JPG', 'jpeg', 'gif'];
            @endphp
            @foreach($option as $o)
                <tr><td align="left">{{$o->name_ru}} </td>
                @if ($o["type_field"]=='i11')
                    @if($o["name_en"]=='photo')
                    <td>
                    @if($catalog["".$o["name_en"].""]!='')
                    @foreach(explode_api(';',$catalog["".$o["name_en"].""]) as $v1)
                    @if($v1!='')
                    <a rel="image" href="{{asset('/upload/images/'.$v1)}}" target="_blank"><img src="{{asset('upload/images/'.$v1)}}" width="200"></a>
                    @endif
                    @endforeach
                    @endif
                    </td></tr>
                    @else
                    @if($catalog[$o["name_en"]]!='')
                        <td><a rel="image" href="{{asset('/upload/images/'.$catalog["".$o["name_en"].""])}}" target="_blank"><img src="{{asset('/upload/images/'.$catalog["".$o["name_en"].""])}}" width="200"></a></td></tr>
                    @endif
                    @endif
                @elseif($o["type_field"]=='i12')
                    @if(in_array(explode_api('.',$catalog["".$o["name_en"].""])[sizeof(explode_api('.',$catalog["".$o["name_en"].""]))-1],$arr_img))
                        <td><a rel="image" href="{{asset('/upload/files/'.$catalog["".$o["name_en"].""])}}" target="_blank"><img src="{{asset('/upload/files/'.$catalog["".$o["name_en"].""])}}" width="200"></a></td></tr>
                    @else
                        <td>{{($catalog["".$o["name_en"].""]!=''?'<a href="'.asset('/upload/files/'.$catalog["".$o["name_en"].""]).'" target="_blank">Скачать ('.(sprintf('%01.2f', @filesize(asset('/upload/files/'.$catalog["".$o["name_en"].""])) / 1048576)).' Мб)</a>':'')}}</td></tr>
                    @endif
                @else
                    <td>{{$catalog["".$o["name_en"].""]}}</td></tr>
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

        <a href="javascript:history.go(-1)">Вернуться назад</a>
    </div>
</div>
    @endsection