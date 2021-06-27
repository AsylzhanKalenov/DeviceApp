@extends('layouts.app1')


@section('content')
    <div class="tabbable tabs-top">

    <ul class="nav nav-tabs">
        <li class="active"><a href="{{route('admin.cabinet.index')}}">Список кабинетов</a></li>

        <li ><a href="{{route('admin.cabinet.create')}}">Добавление кабинета</a></li>



        <li ><a href="{{route('option.list', 'cabinet')}}">Настройка полей</a></li>

    </ul>

    <div class="tab-content">
        <script src="/js/jquery-1.4.2.min.js"></script>
        <a onclick="export_data()" style="float:right">Export в excel</a>

        <script>

            function export_data(){

                var center = $('#center').val();
                var ncab = $('#ncab').val();
                var deayt = $('#deayt').val();

                $.ajax({
                    url: "{{ route('api.export') }}",
                    method: 'POST',
                    data: {do: 'export_cabinet',center: center, ncab: ncab, deayt: deayt, _token: $('input[name="_token"]').val()},
                    dataType: 'json',
                    success: function (data) {
                        if(data.res!="success"){
                            alert('something wrong!');}
                        else {
                            window.open('/export/'+data.filename)
                        }
                    }
                })
            }

            $(function(){

                show_cab();

                $('input[type=reset]').click(function(){

                    $('form input[type=text]').removeAttr("value");

                    $('form select').val("clear");

                    show_cab();

                    $('form').submit();

                })

            })

            function show_cab(){

                $.post('/operator/search',{id:$('#center').val(),center:'1'},function(data){

                    $('#ncab').html(data);

                })

                $.post('/operator/search',{id:$('#center').val(),center:'2'},function(data){

                    $('#deayt').html(data);

                })

            }

        </script>
        <form name="form" action="{{route('admin.cabinet.list')}}" method="POST">
        @csrf
            <strong>Мед. центр</strong>

            <select name="center" id="center" style="width:150px;padding:2px; font-size:12px; height:auto; vertical-align:middle" onchange="show_cab()">

                <option value="">Выберите центр</option>

                @foreach($center as $c)
                    <option value="{{$c->id}}" {{isset($id_center)?($id_center==$c->id?'selected':''):''}}>{{$c->name}}</option>
                @endforeach

            </select>

            &nbsp;&nbsp;&nbsp;<strong>Номер кабинета</strong>

            <select name="ncab[]" id="ncab" multiple="multiple" size="4"  style="width:115px;padding:2px; font-size:12px; height:auto; vertical-align:middle" >

                <option value="clear"></option>

                @foreach($cabinet1 as $ca)
                    <option value="{{$ca->nomer}}" {{isset($isncab)?(in_array($ca->nomer, $isncab)?'selected':''):''}}>{{$ca->nomer}}</option>
                @endforeach

            </select>

            <strong>Мед. направление</strong>

            <select name="deayt[]" id="deayt" multiple="multiple" size="4" style="width:150px;padding:2px; font-size:12px; vertical-align:middle; height:auto;" >

                <option value=""></option>

                @foreach($cabinet1 as $ca)
                    <option value="{{$ca->deayt}}" {{isset($isdeayt)?(in_array($c->deayt, $isdeayt)?'selected':''):''}}>{{$ca->deayt}}</option>
                @endforeach
            </select>

            <input type="submit" value="Поиск" class="btn" style="width:100px; height:24px; padding:0px 12px;">

            <input type="reset" value="Очистить" class="btn" style="width:100px; height:24px; padding:0px 12px;">

        </form>
        <table class="table-striped" width="100%">

            <tr>

                <th>

                    <a href="/admin/cabinet/list/">Центр</a>

                </th>

                @foreach($option as $o)
                    <th><a href="/admin/cabinet/list/" style=" white-space:nowrap">{{$o->name_ru}}</a></th>
                    @endforeach

                <th>

                    Действия

                </th>

            </tr>

            @foreach($cabinet as $ca)
                <tr>

                    <td align="center">

                        {{$ca->cename}}

                    </td>

                @foreach($option as $o)
                        <td>{{$ca[$o->name_en]}}</td>
                @endforeach

                    <td align="right" width="70">
                        <form id="delete-form-{{ $ca->id }}" method="post"
                              action="{{ route('admin.cabinet.destroy',$ca->id) }}"
                              style="display: none">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                        </form>

                        <a href="{{route('admin.cabinet.show', $ca->id)}}" title="Просмотреть"><i class="icon-circle-arrow-up"></i></a> | <a href="{{route('admin.cabinet.edit', $ca->id)}}" title="Редактировать"><i class="icon-edit"></i></a> |
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

        </table>
    </div>
</div>
<script>

    function dell(id)

    {

        if (confirm('Вы действительно хотите удалить элемент?'))

        {

            location.href='/admin/cabinet/delete/'+id;

        }

    }



</script>
    @endsection
