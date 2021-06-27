@extends('layouts.app1')

@section('content')

    <ul class="nav nav-tabs">
        <li class="active"><a href="{{route('accountant.peremesh.list_oper')}}">Переместить оборудование</a></li>
        <li ><a href="{{route('accountant.peremesh.list_pere')}}">История перемещение</a></li>
    </ul>

    <script>
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

        function get_ser() {

            var user = $('#user').val();
            var data1 = $('#data1').val();
            var data2 = $('#data2').val();

            $.ajax({
                url: "{{ route('admin.log.search') }}",
                method: 'POST',
                data: {user: user, data1: data1, data2: data2, _token: $('input[name="_token"]').val()},
                dataType: 'json',
                success: function (data) {
                    $('#log_table tbody').html(data.str);
                    $('.pagination ul').html(data.pag);
                    $('#user1').val(user);
                    $('#data11').val(data1);
                    $('#data22').val(data2);
                }
            })
        }
        function get_ser_page(num) {

            var center = $('#center').val();
            var ncab = $('#ncab').val();
            var ser = $('#ser').val();
            var name = $('#name').val();


            $.ajax({
                url: "{{ route('accountant.peremesh.list_oper_search') }}",
                method: 'POST',
                data: {center: center, ncab: ncab, num: num, ser: ser, name: name, _token: $('input[name="_token"]').val()},
                dataType: 'json',
                success: function (data) {
                    $('.table-striped tbody').html(data.str);
                    $('.pagination ul').html(data.pag);
                }
            })

        }


    </script>

    <form name="form" method="post" action="{{route('accountant.peremesh.list_oper_search')}}" rel="ajax_form">
        @csrf

        <input type="hidden" name="search" value="1">

        <table width="100%">

            <tr>

                <td style="padding:5px;">

                    <strong>Штрих код</strong><br />

                    <input type="text" name="ser" id="ser" value="{{isset($ser)?$ser:''}}" style="padding:2px; font-size:12px; height:auto; width:150px;">

                </td>

                <td style="padding:5px;">

                    <strong>Название</strong><br />

                    <input type="text" name="name" id="name" value="{{isset($name)?$name:''}}" style="padding:2px; font-size:12px; height:auto; width:150px;">

                </td>

                <td style="padding:5px;">

                    <strong>Мед. центр</strong><br />

                    <select name="center" id="center" style="width:150px;padding:2px; font-size:12px; height:auto;" onchange="show_cab()">

                        <option value=""></option>
                        @foreach($center as $c)
                            <option value="{{$c->id}}" {{isset($id_center)?($id_center==$c->id?'selected':''):''}}>{{$c->name}}</option>
                        @endforeach

                    </select>

                </td>

                <td style="padding:5px;">

                    <strong>Номер кабинета</strong><br />

                    <select name="ncab[]" multiple="multiple" size="4" id="ncab" style="width:150px;padding:2px; font-size:12px; height:auto;" >

                        <option value=""></option>

                        @foreach($cabinet1 as $c)

                            <option value="{{$c->id}}" {{isset($isncab)?(in_array($c->id, $isncab)?'selected':''):''}}>{{$c->nomer.'('.$c->deayt.')'}}</option>

                        @endforeach
                    </select>

                </td>



                <td style="padding:5px; display:none">

                    <strong>Мед. направление</strong><br />

                    <select name="deayt" id="deayt" style="width:150px;padding:2px; font-size:12px; height:auto;" >

                        <option value="clear"></option>

                        @foreach($cabinet1 as $ca)
                            <option value="{{$ca->deayt}}" {{isset($isdeayt)?(in_array($c->deayt, $isdeayt)?'selected':''):''}}>{{$ca->deayt}}</option>
                        @endforeach


                    </select>

                </td>

            </tr>



            <tr>

                <td style="padding:5px;">



                </td>

                <td style="padding:5px;">

                </td>

                <td style="padding:5px;">



                </td>



                <td style="padding:5px;">

                    <input type="submit" value="Поиск" class="btn" style="width:100px">

                    <input type="reset" value="Очистить" class="btn" style="width:100px">



                </td>



            </tr>

        </table>



    </form>


    <table class="table-striped table-hover table-border" width="100%">

        <thead>
        <tr>

            <th width="60" align="center" style="white-space:nowrap">Штрих код</th>

            <th width="150" style="white-space:nowrap" align="center">Мед. центр</th>

            <th align="center">Кабинет</th>

            <th align="center" style="white-space:nowrap">Наименование</th>

            <th align="center" style="white-space:nowrap">Модель</th>

            @foreach($option as $o)
                <th align="center" style="white-space:nowrap">{{$o->name_ru}}</th>
            @endforeach

            <th align="center" width="70">Действия</th></tr>
        </thead>
        <tbody>

        @foreach($catalog as $c)
            <tr>

                <td align="center" style="padding:5px; padding-left:10px;">{{$c->id}}</td>

                <td align="center" style="padding:5px;">{{get_center($c->idc, 'name')}}</td>

                <td align="center" style="padding:5px;">{{get_cabinet($c->idcab, 'nomer').'('.get_cabinet($c->idcab, 'deayt').')'}}</td>

                <td align="center" style="padding:5px;">{{get_razdel($c->ids2, 'name')}}</td>

                <td align="center" style="padding:5px;">{{get_razdel($c->ids3, 'name')}}</td>
                @foreach($option as $o)
                    <td align="center" style="padding:5px;">{{$c[$o->name_en]}}</td>
                @endforeach

                    <td align="center" style="padding:5px; text-align:center"><a href="{{route('accountant.peremesh.show_oper', $c["id"])}}" title="Переместить"><i class="icon-circle-arrow-up"></i></a>

                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="pagination" style="text-align:center"><ul>
            {!!$pag!!}

        </ul></div>
@endsection