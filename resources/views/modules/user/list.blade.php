@extends('layouts.app1')


@section('content')

    <script>

        function show_personal(group){

            var id = $('#center').val();
            $.ajax({
                url: "{{ route('api.catalog.add') }}",
                method: 'POST',
                data: {id: id, classes: 4, _token: $('input[name="_token"]').val()},
                dataType: 'json',
                success: function (data) {
                    $('#ncab').html(data.str);

                }
            });

        }

    </script>

    <div class="tabbable tabs-top">

        <ul class="nav nav-tabs">

            <li class="active"><a href="{{route('admin.user.index')}}">Список пользователей</a></li>
            <li ><a href="{{route('admin.user.create')}}">Добавление пользователя</a></li>
            <li ><a href="{{route('option.list', 'operator')}}">Настройка полей</a></li>

        </ul>
        <div style="display: inline-block">
            <a class="btn" href="{{route('admin.user.index')}}">Список операторов</a>
            <a class="btn" href="{{route('admin.user.index_personal', 7)}}">Список бухгалтеров</a>
            <a class="btn" href="{{route('admin.user.index_personal', 8)}}">Список диспечеров</a>
        </div>
        <div class="tab-content">
            <a href="?export" style="float:right">Export в excel</a>
            <table class="table-striped" width="100%">

                <tr>

                    <th><a href="/admin/center/list/">Логин</a></th>
                    <th><a href="/admin/center/list/">Пароль</a></th>

                @foreach($option as $op)
                        <th><a href="/admin/center/list/">{{$op["name_ru"]}}</a></th>
                    @endforeach

                        <th>

                            <a href="/admin/operator/list/">Центр</a>

                        </th>
                    <th>
                        Действия
                    </th>

                </tr>

                @foreach($operator as $c)
                    <tr>
                        <td style="max-width: 470px;">{{$c->login}}</td>
                        <td style="max-width: 470px;">{{$c->password}}</td>

                        @foreach($option as $op)

                            <td style="max-width: 470px;">{{$c[$op["name_en"]]}}</td>

                        @endforeach

                            <td style="max-width: 470px;">{{$c->id_center}}</td>

                        <td align="right" style="max-width: 470px;">
                            <form id="delete-form-{{ $c->id }}" method="post"
                                  action="{{ route('admin.user.destroy',$c->id) }}"
                                  style="display: none">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                            </form>

                            <a href="/admin/center/show/id" title="Просмотреть"><i class="icon-circle-arrow-up"></i></a> | <a href="{{route('admin.user.edit', $c->id)}}" title="Редактировать"><i class="icon-edit"></i></a> |
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

                location.href='/admin/user/delete/'+id;

            }

        }



    </script>
@endsection