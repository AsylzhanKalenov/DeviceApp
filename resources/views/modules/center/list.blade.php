@extends('layouts.app1')


@section('content')

    <div class="tabbable tabs-top">

        <ul class="nav nav-tabs">

            <li class="active"><a href="{{route('center.index')}}">Список центров</a></li>
            <li ><a href="{{route('center.create')}}">Добавление центра</a></li>
            <li ><a href="/admin/center/edit">Редактирование центра</a></li>
            <li ><a href="{{route('option.list', ['table' =>'center'])}}">Настройка полей</a></li>

        </ul>

        <div class="tab-content">
            <a href="?export" style="float:right">Export в excel</a>
            <table class="table-striped" width="100%">

                <tr>


                    <th><a href="/admin/center/list/">Наименование</a></th>
                    <th><a href="/admin/center/list/">Логотип</a></th>
                    <th><a href="/admin/center/list/">Фактический адрес</a></th>
                    <th><a href="/admin/center/list/">Юридический адрес</a></th>
                    <th><a href="/admin/center/list/">Телефоны</a></th>
                    <th><a href="/admin/center/list/">Реквизиты</a></th>
                    <th>
                        Действия
                    </th>

                </tr>

                @foreach($center as $c)
                    <tr>
                    <td>{{$c->name}}</td>
                    <td>{{$c->logo}}</td>
                    <td>{{$c->fact}}</td>
                    <td>{{$c->uadr}}</td>
                    <td>{{$c->phones}}</td>
                    <td>{{$c->req}}</td>
                    <td align="right" width="70">
                        <form id="delete-form-{{ $c->id }}" method="post"
                              action="{{ route('center.destroy',$c->id) }}"
                              style="display: none">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                        </form>

                        <a href="/admin/center/show/id" title="Просмотреть"><i class="icon-circle-arrow-up"></i></a> | <a href="/admin/center/edit/id" title="Редактировать"><i class="icon-edit"></i></a> |
                        <a href="" onclick="
                                if(confirm('Are you sure, You Want to delete this?'))
                                {
                                event.preventDefault();
                                document.getElementById('delete-form-{{ $c->id }}').submit();
                                }
                                else{
                                event.preventDefault();
                                }" title="Удалить"><i class="icon-minus-sign"></i></a>

                    </td>
                    </tr>
                    @endforeach

            </table>

            <div style="text-align:center; margin-top:10px;"> </div>

        </div>

    </div>


    <script>

        function dell(id)

        {

            if (confirm('Вы действительно хотите удалить элемент?'))

            {

                location.href='/admin/center/delete/'+id;

            }

        }



    </script>
    @endsection