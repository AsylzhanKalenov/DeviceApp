@extends('layouts.app1')

@section('content')

    <div class="tabbable tabs-top">
        <ul class="nav nav-tabs">
            <li ><a href="{{route('admin.user.index')}}">Список пользователей</a></li>
            <li ><a href="{{route('admin.user.create')}}">Добавление пользователя</a></li>
            <li class="active"><a href="/admin/center/edit">Редактирование пользователя</a></li>
            <li ><a href="{{route('option.list', ['table' =>'operator'])}}">Настройка полей</a></li>


        </ul>
        <div class="tab-content">

            <form method="post" rel="ajax_form" action="{{route('admin.user.update', $id->id)}}" enctype="multipart/form-data">
                @csrf
                {{ method_field('PATCH') }}
                <input type="hidden" name="add" value="1">
                <table class="table-striped" width="100%">
                    @foreach($option as $op)
                        <tr>
                            <td align="left">{{$op->name_ru}}{!!$op->required_fields==1?'<span style="color:red">*</span>':''!!}</td>
                            <td>
                                {!!html_entity_decode(input_view($op->id,'user','id',$id->id,''))!!}
                            </td>
                        </tr>
                    @endforeach

                        <tr>

                            <td>

                                Старый пароль <span style="color:red"></span>

                            </td>

                            <td>

                                <input type="password" id="old_password" name="old_password" style="text-align:left"  alt="" ><br />

                                <span class="error" id="err_password"></span>

                            </td>

                        </tr>
                        <tr>

                            <td>

                                Новый пароль <span style="color:red"></span>

                            </td>

                            <td>

                                <input type="password" id="password" name="password" style="text-align:left"  alt="" ><br />

                                <span class="error" id="err_password"></span>

                            </td>

                        </tr>
                        <tr>

                            <td>

                                Подтверждение пароля <span style="color:red"></span>

                            </td>

                            <td>

                                <input type="password" id="password1" name="password1" style="text-align:left"  alt="" ><br />

                                <span class="error" id="err_password"></span>

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
