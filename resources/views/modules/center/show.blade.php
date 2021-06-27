@extends('layouts.app1')


@section('content')

<div class="tabbable tabs-top">
    <ul class="nav nav-tabs">
        <li ><a href="/admin/center/list">Список центров</a></li>
        <li ><a href="/admin/center/add">Добавление центра</a></li>
        <li ><a href="/admin/center/edit/">Редактирование центра</a></li>
        <li ><a href="/admin/center/option">Настройка полей</a></li>

    </ul>
    <div class="tab-content">

        <table class="table-striped" width="100%">

<!--            --><?//
//            foreach ($options as $k=>$v)
//            {
//                echo ' <tr><td align="left">'.$v["name_ru"].' </td>';
//                if ($v["type_field"]=='i11')
//                {
//
//                    echo '<td>'.($center["".$v["name_en"].""]!=''?'<a rel="image" href="/upload/images/'.$center["".$v["name_en"].""].'"><img src="/upload/images/'.$center["".$v["name_en"].""].'" width="200"></a>':'').'</td></tr>';
//                }
//                else if ($v["type_field"]=='i12')
//                {
//                    echo '<td><a href="/upload/files/'.$center["".$v["name_en"].""].'">Скачать</a></td></tr>';
//                }
//                else
//                {
//                    if ($v["name_en"]=='map')
//                    {
//                        echo '<td><a class="map iframe" href="'.str_replace('&output=embed','',$center["".$v["name_en"].""]).'&output=embed">Карта проезда</a></td></tr>';
//                    }
//                    else
//                        echo '<td>'.$center["".$v["name_en"].""].'</td></tr>';
//                }
//            }
//            ?>

        </table> <br />
        <br />

        <a href="javascript:history.go(-1)">Вернуться назад</a>
    </div>
</div>

    @endsection
