@extends('layouts.app1')
@section('content')
    <div class="tabbable tabs-top">
        <ul class="nav nav-tabs">
            <li class="active"><a href="{{route('dispatcher.remont.index')}}">Заявки</a></li>
            <li><a href="{{route('dispatcher.remont.index')}}">Архив</a></li>
        </ul>


        <div class="tab-content">
            @if(isset($res))
            @if($res)
                <div class="alert alert-success">
                    Данные сохранились!
                </div>
            @else
                <div class="alert alert-error">
                    Ошибка при сохранение
                </div>
            @endif
            @endif

            @if($remont["stat"] == 'Архив')
                <form action="{{route('dispatcher.remont.update', $remont["id"])}}" method="post" rel="ajax_form">
                    @csrf
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
                                {!!html_entity_decode($remont["comm1"])!!}

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
                <form action="{{route('dispatcher.remont.update', $remont["id"])}}" method="post" rel="ajax_form">
                    @csrf
                    {{ method_field('PATCH') }}
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
                                <textarea class="form-control" id="comm1" name="comm1">{{$remont["comm1"]}}</textarea>
                            </td>
                        </tr>
                        <tr >
                            <td>
                                Исполнитель
                            </td>
                            <td>
                                <select name="idisp" id="idisp" alt="" rel="req">
                                    @foreach($isps as $is)
                                        <option value="{{$is["id"]}}" {{($is["id"]==$remont["idisp"]?'selected':'')}}>{{$is["fio"]}}</option>
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
                <h3>История</h3>
                @if(isset($remont1))
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
            <a href="javascript:history.go(-1)">Вернуться назад</a>
        </div>
    </div>



    <script src="{{ asset('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
    <script>
        CKEDITOR.replace( 'comm1' );
    </script>

@endsection