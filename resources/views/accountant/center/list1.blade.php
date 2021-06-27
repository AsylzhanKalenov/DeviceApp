@extends('layouts.app1')


@section('content')

    <div class="tabbable tabs-top">

        <ul class="nav nav-tabs">

            <li class="active"><a href="{{route('accountant.center.index')}}">Список центров</a></li>
            <li ><a href="{{route('accountant.center.create')}}">Добавление центра</a></li>
            <li ><a href="/accountant/center/edit">Редактирование центра</a></li>
            <li ><a href="{{route('option.list', 'center')}}">Настройка полей</a></li>

        </ul>

        <div class="tab-content">
            <a onclick="export_data()" style="float:right">Export в excel</a>
            <table class="table-striped" width="100%">

                <tr>

                    @foreach($option as $op)
                        <th><a href="/accountant/center/list/">{{$op->name_ru}}</a></th>
                    @endforeach

                    <th>
                        Действия
                    </th>

                </tr>

                @foreach($center as $c)
                <tr>
                    @foreach($option as $op)

                        <td style="max-width: 470px;">{{$c[$op->name_en]}}</td>

                    @endforeach

                        <td align="right" style="max-width: 470px;">

                            <a href="/accountant/center/show/id" title="Просмотреть"><i class="icon-circle-arrow-up"></i></a> | <a href="{{route('accountant.center.edit', $c->id)}}" title="Редактировать"><i class="icon-edit"></i></a>

                        </td>
                    </tr>
                @endforeach

            </table>

            <div style="text-align:center; margin-top:10px;"> </div>

        </div>

    </div>


    <script>
        function export_data(){

            $.ajax({
                url: "{{ route('api.export') }}",
                method: 'POST',
                data: {do: 'export_center', _token: $('input[name="_token"]').val()},
                dataType: 'json',
                success: function (data) {
                    if(data.res!="success"){
                        alert('something wrong!');}
                    else {
                        window.location.href='/export/'+data.filename;
                    }
                }
            })
        }

        function dell(id)

        {

            if (confirm('Вы действительно хотите удалить элемент?'))

            {

                location.href='/admin/center/delete/'+id;

            }

        }



    </script>
@endsection