@extends('layouts.app1')


@section('content')

    <div class="tabbable tabs-top">

        <ul class="nav nav-tabs">

            <li class="active"><a href="{{route('module.order.index')}}">Список заявок</a></li>

            <li><a href="/admin/order/edit">Редактирование заявки</a></li>

        </ul>
        <script>
            function export_data(){
                $.ajax({
                    url: "{{ route('api.export') }}",
                    method: 'POST',
                    data: {do: 'export_order', _token: $('input[name="_token"]').val()},
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
        </script>

        <div class="tab-content">
            <a onclick="export_data()" style="float:right">Export в excel</a>
            <table class="table-striped" width="100%">

                <tr>
                    @foreach($option as $o)
                        <th><a href="/admin/order/list/'">{{$o->name_ru}}</a></th>
                    @endforeach
                    <th>
                        Действия
                    </th>
                </tr>
                @foreach($order as $or)
                    <tr>
                        @foreach($option as $o)
                            <td>{{$or[$o->name_en]}}</td>
                        @endforeach

                        <td align="right" width="70">
                            <form id="delete-form-{{ $or->id }}" method="post"
                                  action="{{ route('module.order.destroy',$or->id) }}"
                                  style="display: none">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                            </form>

                            <a href="{{route('module.order.show', $or->id)}}" title="Просмотреть"><i class="icon-circle-arrow-up"></i></a> | <a href="{{route('module.order.edit', $or->id)}}" title="Редактировать"><i class="icon-edit"></i></a> |
                            <a href="" onclick="
                                    if(confirm('Are you sure, You Want to delete this?'))
                                    {
                                    event.preventDefault();
                                    document.getElementById('delete-form-{{ $or->id }}').submit();
                                    }
                                    else{
                                    event.preventDefault();
                                    }" title="Удалить"><i class="icon-minus-sign"></i></a>

                        </td>
                    </tr>
                @endforeach
            </table>
        </div>

    </div>
@endsection