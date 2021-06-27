@extends('layouts.app1')


@section('content')
    <div class="tabbable tabs-top">

        <ul class="nav nav-tabs">
            <li ><a href="{{route('module.order.index')}}">Список заявок</a></li>

            <li class="active"><a href="{{route('module.order.create')}}">Добавление заявки</a></li>

            <li><a href="/admin/order/edit">Редактирование заявки</a></li>

            <li ><a href="{{route('option.list', 'order')}}">Настройка полей</a></li>

        </ul>
        <div class="tab-content">
            <form method="post" rel="ajax_form" action="{{route('module.order.store')}}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="add" value="1">
                <table class="table-striped" width="100%">
                    <tr><td>
                            Медицинский центр<span style="color:red">*</span>
                        </td>
                        <td>
                            <select name="center" id="center">
                    @foreach($center as $c)
                            <option value="{{$c->id}}">{{$c->name}}</option>
                    @endforeach
                            </select>
                        </td>
                    </tr>

                    @foreach($option as $op)
                        <tr>
                            <td align="left">{{$op->name_ru}}{!!$op->required_fields==1?'<span style="color:red">*</span>':''!!}</td>
                            <td>
                                {!!html_entity_decode(input_view($op->id,'order','','','',1))!!}
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td>
                        </td>
                        <td>
                            <input type="button" id="send" value="Добавить" class="btn">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>

        @endsection