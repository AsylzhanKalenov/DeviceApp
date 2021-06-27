@extends('layouts.app1')

@section('content')

    <div class="tabbable tabs-top">
    <ul class="nav nav-tabs">
        <li ><a href="{{route('admin.user.index')}}">Список пользователей</a></li>
        <li class="active"><a href="{{route('admin.user.create')}}">Добавление пользователя</a></li>
        <li ><a href="{{route('option.list', ['table' =>'operator'])}}">Настройка полей</a></li>
    </ul>
    <div class="tab-content">

        <form method="post" rel="ajax_form" action="{{route('admin.user.store')}}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="add" value="1">
            <table class="table-striped" width="100%">
            @foreach($option as $op)
                <tr>
                <td align="left">{{$op->name_ru}}{!!$op->required_fields==1?'<span style="color:red">*</span>':''!!}</td>
                <td>
                    {!!html_entity_decode(input_view($op->id,'user','','','',1))!!}
                </td>
                </tr>
            @endforeach

                <tr>

                    <td>

                        Логин <span style="color:red">*</span>

                    </td>

                    <td>

                        <input type="text" id="login" name="login" rel="req"  alt="" class="btn"><br />

                        <span class="error" id="err_login"></span>

                    </td>

                </tr>

                <tr>

                    <td>

                        Пароль <span style="color:red">*</span>

                    </td>

                    <td>

                        <input type="password" id="password" name="password"  alt="" rel="req"  class="btn"><br />

                        <span class="error" id="err_password"></span>

                    </td>

                </tr>

                <tr>

                    <td>

                        Центр

                    </td>

                    <td>

                        @foreach ($center as $k=>$v)

                            <p><label><input type="checkbox" alt=""  value="{{$v->id}}" name="center[]" id="center{{$v->id}}"> {{$v->name}}</label></p>

                        @endforeach
                        <br />

                        <span class="error" id="err_name[]"></span>

                    </td>

                </tr>

                <tr>
                    <td>

                        Должность

                    </td>
                    <td>

                        <select name="dolzh1" id="dolzh1">
                        <option value="1">Администраторы</option>
                        <option value="2">Операторы</option>
                        <option value="7">Бухгалтер</option>
                        <option value="8">Диспетчер</option>
                        </select>

                    </td>
                </tr>

                <tr>
                    <td>
                    </td>
                    <td>
                        <input type="submit" id="send" value="Добавить" class="btn">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
    @endsection
