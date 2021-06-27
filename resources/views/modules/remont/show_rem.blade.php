@extends('layouts.app1')
@section('content')
<div class="tabbable tabs-top">

    <ul class="nav nav-tabs">
        <li ><a href="{{route('module.remont.index')}}">Подать заявку</a></li>

        <li ><a href="{{route('module.remont.added')}}">Заявки</a></li>

        <li ><a href="{{route('module.remont.archive')}}">Архив</a></li>



    </ul>
    <div class="tab-content">


@if($remont["stat"] == 'Архив')
        <form action="/service/remont/show_remont/{{$remont["id"]}}" method="post" rel="ajax_form">
            <input type="hidden" name="id" value="{{$remont["id"]}}">
            <table class="table-striped" width="100%">

                <tr >
                    <td>
                        Вид работ <span style="color:red">*</span>
                    </td>
                    <td>

                        {{$type["name"]}}
                    </td>
                </tr>
                <tr >
                    <td>
                        Описание неисправностей
                    </td>
                    <td>
                        {{nl2br($remont["comm"])}}
                    </td>
                </tr>
                <tr >
                    <td>
                        Описание проведенных работ
                    </td>
                    <td>
                        {{$remont["comm1"]}}
                    </td>
                </tr>
                <tr >
                    <td>
                        Исполнитель
                    </td>
                    <td>
                        {{$isp["name"]}}

                    </td>
                </tr>
                <tr >
                    <td>
                        Дата подачи заявки
                    </td>
                    <td>
                        {{nl2br($remont["data1"])}}

                    </td>
                </tr>
                <tr >
                    <td>
                        Дата начала сервисного обслуживания
                    </td>
                    <td>
                        {{($remont["data2"]!='0000-00-00'?nl2br($remont["data2"]):'')}}
                    </td>
                </tr>
                <tr >
                    <td>
                        Дата окончания ремонта
                    </td>
                    <td>
                        {{($remont["data3"]!='0000-00-00'?nl2br($remont["data3"]):'')}}
                    </td>
                </tr>
                <tr >
                    <td>
                        Статус
                    </td>
                    <td>
                        Архив
                    </td>
                </tr>

            </table>
        </form>
    @else
        <form action="/service/remont/show_remont/{{$remont["id"]}}" method="post" rel="ajax_form">
            <input type="hidden" name="id" value="{{$remont["id"]}}">
            <table class="table-striped" width="100%">

                <tr >
                    <td>
                        Вид работ <span style="color:red">*</span>
                    </td>
                    <td>
                        <select name="idtype" id="idtype" alt="" rel="req">
                    @foreach($types as $t)
                                <option value="{{$t["id"]}}" {{($t["id"]==$remont["idtype"]?'selected':'')}}>{{$t["name"]}}</option>
                    @endforeach
                        <option value="other">Другое</option>
                        </select>
                        <div id="type_other" style="display:none">
                            <input type="text" name="other_idtype" id="other_idtype">
                        </div>
                    </td>
                </tr>
                <tr >
                    <td>
                        Описание неисправностей
                    </td>
                    <td>
                        {{nl2br($remont["comm"])}}
                    </td>
                </tr>
                <tr >
                    <td>
                        Описание проведенных работ
                    </td>
                    <td>
                        <div>
                            <input type="hidden" id="comm1" name="comm1"
                                   value="{{$remont["comm1"]}}">
                            <input type="hidden" id="FCKeditor1___Config" value="">
                            <iframe id="FCKeditor1___Frame"
                                    src="/incom/modules/editor/editor/fckeditor.html?InstanceName=comm1&Toolbar=Default"
                                    width="700" height="400" frameborder="no" scrolling="no"></iframe></div>
                    </td>
                </tr>
                <tr >
                    <td>
                        Исполнитель
                    </td>
                    <td>
                        <select name="idisp" id="idisp" alt="" rel="req">
                    @foreach($isps as $is)
                        <option value="{{$is["id"]}}" {{($is["id"]==$remont["idisp"]?'selected':'')}}>{{$is["name"]}}</option>
                    @endforeach
                        <option value="other">Другое</option>
                        </select>
                        <div id="isp_other" style="{{sizeof($isps)==0?'':'display:none'}}">
                            <input type="text" name="other_idisp" id="other_idisp">
                        </div>
                    </td>
                </tr>
                <tr >
                    <td>
                        Дата подачи заявки
                    </td>
                    <td>
                        {{nl2br($remont["data1"])}}
                    </td>
                </tr>
                <tr >
                    <td>
                        Дата начала сервисного обслуживания
                    </td>
                    <td>
                        {{($remont["data2"]!='0000-00-00'?nl2br($remont["data2"]):'')}}

                    </td>
                </tr>
                <tr >
                    <td>
                        Дата окончания ремонта
                    </td>
                    <td>
                        {{($remont["data3"]!='0000-00-00'?nl2br($remont["data3"]):'')}}
                    </td>
                </tr>
                <tr >
                    <td>
                        Статус
                    </td>
                    <td>
                        <select name="stat" id="stat" alt="" rel="req">

                            <option value="Поданная заявка" {{('Поданная заявка'==$remont["stat"]?'selected':'')}}>Поданная заявка</option>
                            <option value="В ремонте" {{('В ремонте'==$remont["stat"]?'selected':'')}}>В ремонте</option>
                            <option value="Ждем деталь" {{('Ждем деталь'==$remont["stat"]?'selected':'')}}>Ждем деталь</option>
                            <option value="Ждем специалиста" {{('Ждем специалиста'==$remont["stat"]?'selected':'')}}>Ждем специалиста</option>
                            <option value="Отремонтировано" {{('Отремонтировано'==$remont["stat"]?'selected':'')}}>Отремонтировано</option>
                            <option value="Дефектовано" {{('Дефектовано'==$remont["stat"]?'selected':'')}}>Дефектовано</option>

                        </select>

                    </td>
                </tr>
                <tr>
                    <td>
                    </td>
                    <td>
                        <input type="button" id="send" value="Сохранить" class="btn">
                    </td>
                </tr>
            </table>
        </form> <br />

@endif

    <br />

    <a href="javascript:history.go(-1)">Вернуться назад</a>

</div>
</div>

    @endsection