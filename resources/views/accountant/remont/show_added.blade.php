@extends('layouts.app1')
@section('content')

<div class="tabbable tabs-top">

    <ul class="nav nav-tabs">

        <li class="active"><a href="{{route('accountant.remont.index')}}">Заявки</a></li>

        <li><a href="{{route('accountant.remont.index')}}">Архив</a></li>

    </ul>

    <div class="tab-content">

        <form action="/operator/remont/show_added/{{$remont["id"]}}" method="post" rel="ajax_form">
            <input type="hidden" name="id" value="{{$remont["id"]}}">
            <table class="table-striped" width="100%">
            <tr >
                <td>
                    Вид работ <span style="color:red">*</span>
                </td>
                <td>
            {{isset($type->name)?$type->name:''}}
                </td>
            </tr>
            <tr >
                <td>
                    Описание неисправностей
                </td>
                <td>
            {{nl2br($remont->comm)}}
                </td>
            </tr>
            <tr >
                <td>
                    Описание проведенных работ
                </td>
                <td>
            {{$remont->comm1}}
                </td>
            </tr>
            <tr >
                <td>
                    Исполнитель
                </td>
                <td>
            {{isset($isp->fio)?$isp->fio:''}}
                </td>
            </tr>
            <tr >
                <td>
                    Дата подачи заявки
                </td>
                <td>
            {{nl2br($remont->data1)}}
                </td>
            </tr>
            <tr >
                <td>
                    Дата начала сервисного обслуживания
                </td>
                <td>
            {{$remont["data2"]!='0000-00-00'?nl2br($remont["data2"]):''}}
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
                <option value="Работу принял">Работу принял</option>
            </select>
                </td>
            </tr>
            <tr>
                <td>
                </td>
                <td>
            @if ($remont["stat"]=='Отремонтировано' || $remont["stat"]=='Дефектовано')
            <input type="button" id="send" value="Сохранить" class="btn">
            @endif
                </td>
            </tr>
            </table>
        </form> <br />
        <br />
        <a href="javascript:history.go(-1)">Вернуться назад</a>

    </div>
</div>

    @endsection