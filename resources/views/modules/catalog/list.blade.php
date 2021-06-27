@extends('layouts.app1')

@section('content')

<div class="tabbable tabs-top">

    <ul class="nav nav-tabs">

        <li class="active"><a href="{{route('admin.catalog.index')}}">Список оборудования</a></li>

        <li ><a href="{{route('admin.catalog.create')}}">Добавление оборудования</a></li>

        <li><a href="/admin/catalog/edit">Редактирование оборудования</a></li>

        <li ><a href="{{route('option.list', 'catalog')}}">Настройка полей</a></li>

    </ul>

    <div class="tab-content">
        <a href="javascript:export_data()" style="float:right">Export в excel</a>

        <script src="/js/jquery-1.4.2.min.js"></script>

        <script>

            function export_data(){

                var center = $('#center').val();
                var ncab = $('#ncab').val();
                var name_cat = $('#name_cat').val();
                var ser_num = $('#ser_num').val();


                $.ajax({
                    url: "{{ route('api.export') }}",
                    method: 'POST',
                    data: {do: 'export_catalog',center: center, ncab: ncab, name_cat: name_cat, ser_num: ser_num, _token: $('input[name="_token"]').val()},
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

            $(function(){

                $('input[type=reset]').click(function(){

                    $('form input[type=text]').removeAttr("value");

                    $('form input[type=checkbox]').removeAttr("checked");

                    $('form select').val("clear");

                    show_cab();

                    $('form').submit();

                })

            })

            function get_ser_page(num) {

                var center = $('#center').val();
                var ncab = $('#ncab').val();

                $.ajax({
                    url: "{{ route('admin.catalog.list') }}",
                    method: 'POST',
                    data: {center: center, ncab: ncab, num: num, _token: $('input[name="_token"]').val()},
                    dataType: 'json',
                    success: function (data) {
                        $('.table-striped tbody').html(data.str);
                        $('.pagination ul').html(data.pag);
                    }
                })
            }

            function show_cab(){

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

            function delet(id){

                if (confirm("Вы действительно хотите удалить оборудование?"))

                {

                    location.href='/admin/catalog/delete/'+id;

                }

            }

        </script>
        <form name="form" action="{{route('admin.catalog.list')}}" method="POST">
            @csrf
            <strong>Мед. центр</strong>

            <select name="center" id="center" style="width:150px;padding:2px; font-size:12px; height:auto; vertical-align:middle" onchange="show_cab()">

                <option value="">Выберите центр</option>

                @foreach($center as $c)
                    <option value="{{$c->id}}" {{isset($id_center)?($id_center==$c->id?'selected':''):''}}>{{$c->name}}</option>
                @endforeach

            </select>

            &nbsp;&nbsp;&nbsp;<strong>Номер кабинета</strong>

            <select name="ncab[]" id="ncab" multiple="multiple" size="4"  style="width:195px;padding:2px; font-size:12px; height:auto; vertical-align:middle" >

                <option value=""></option>
                @foreach($cabinet1 as $c)

                    <option value="{{$c->id}}" {{isset($isncab)?(in_array($c->id, $isncab)?'selected':''):''}}>{{$c->nomer.'('.$c->deayt.')'}}</option>

                @endforeach


            </select>
            <strong>Наименование</strong>
            <input type="text" id="name_cat" name="name_cat" value="{{@$name}}">
            <strong>Сер.№</strong>
            <input type="text" id="ser_num" name="ser_num" value="{{@$ser_num}}">

{{--            <strong>Мед. направление</strong>--}}

{{--            <select name="deayt[]" id="deayt" multiple="multiple" size="4" style="width:150px;padding:2px; font-size:12px; vertical-align:middle; height:auto;" >--}}

{{--                <option value=""></option>--}}

{{--                @foreach($catalog1 as $ca)--}}
{{--                    <option value="{{$ca->deayt}}" {{isset($isdeayt)?(in_array($c->deayt, $isdeayt)?'selected':''):''}}>{{$ca->deayt}}</option>--}}
{{--                @endforeach--}}
{{--            </select>--}}

            <input type="submit" value="Поиск" class="btn" style="width:100px; height:24px; padding:0px 12px;">

            <input type="reset" value="Очистить" class="btn" style="width:100px; height:24px; padding:0px 12px;">

        </form>

        <table class="table-striped" width="100%">
            <thead>

            <tr>

                <th width="60"> <a href="/admin/catalog/list/" style="white-space:nowrap">Штрих код</a> </th>

                <th width="150"> <a href="/admin/catalog/list/" style="white-space:nowrap">Центр</a> </th>

                <th> <a href="/admin/catalog/list/" style="white-space:nowrap">Кабинет</a> </th>

                <th> <a href="/admin/catalog/list/" style="white-space:nowrap">Наименование</a> </th>

                <th> <a href="/admin/catalog/list/" style="white-space:nowrap">Модель</a> </th>

                @foreach($option as $o)
                    <th><a href="/admin/catalog/list/" style=" white-space:nowrap">{{$o->name_ru}}</a></th>
                @endforeach

                <th> Действия </th>

            </tr>
            </thead>

            <tbody>
            @foreach($catalog as $ca)
                <tr>

                    <td align="center" style="text-align:left">{{$ca->id}}</td>

                    <td align="center">{{get_center($ca->idc, 'name')}}</td>

                    <td align="center">{{get_cabinet($ca->idcab, 'nomer').'('.get_cabinet($ca->idcab, 'deayt').')'}}</td>

                    <td align="center">{{get_razdel($ca->ids2, 'name')}}</td>

                    <td align="center">{{get_razdel($ca->ids3, 'name')}}</td>
                    @foreach($option as $o)
                        <td>{{$ca[$o->name_en]}}</td>
                    @endforeach

                    <td align="right" width="70">
                        <form id="delete-form-{{ $ca->id }}" method="post"
                              action="{{ route('admin.catalog.destroy',$ca->id) }}"
                              style="display: none">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                        </form>

                        <a href="{{route('admin.catalog.show', $ca->id)}}" title="Просмотреть"><i class="icon-circle-arrow-up"></i></a> | <a href="{{route('admin.catalog.edit', $ca->id)}}" title="Редактировать"><i class="icon-edit"></i></a> |
                        <a href="" onclick="
                                if(confirm('Are you sure, You Want to delete this?'))
                                {
                                event.preventDefault();
                                document.getElementById('delete-form-{{ $ca->id }}').submit();
                                }
                                else{
                                event.preventDefault();
                                }" title="Удалить"><i class="icon-minus-sign"></i></a>

                    </td>
                </tr>

        @endforeach
            </tbody>
        </table>
        <div class="pagination" style="text-align:center"><ul>
                {!!$pag!!}
        </ul></div>
    </div>
</div>

<script>

    function dell(id)

    {

        if (confirm('Вы действительно хотите удалить элемент?'))

        {

            location.href='/admin/catalog/delete/'+id;

        }

    }

</script>
@endsection
