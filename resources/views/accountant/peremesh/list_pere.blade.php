@extends('layouts.app1')

@section('content')

    <ul class="nav nav-tabs">

        <li ><a href="{{route('accountant.peremesh.list_oper')}}">Переместить оборудование</a></li>

        <li class="active"><a href="{{route('accountant.peremesh.list_pere')}}">История перемещение</a></li>

    </ul>

    <script>
        $(function(){

            $('input[type=reset]').click(function(){

                $('form input[type=text]').removeAttr("value");

                $('form select option:selected').removeAttr("selected");

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

        function get_ser_page(num) {

            var center = $('#center').val();
            var ncab = $('#ncab').val();
            var data1 = $('#data1').val();
            var data2 = $('#data2').val();

            $.ajax({
                url: "{{ route('accountant.peremesh.list_pere_search')}}",
                method: 'POST',
                data: {center: center, data1: data1, data2: data2, ncab: ncab, num: num, _token: $('input[name="_token"]').val()},
                dataType: 'json',
                success: function (data) {
                    $('.table-striped tbody').html(data.str);
                    $('.pagination ul').html(data.pag);
                }
            })

        }
    </script>

    <form name="form" method="post" action="{{route('accountant.peremesh.list_pere_search')}}" rel="ajax_form">
        @csrf
        <input type="hidden" name="search" value="1">

        <table width="100%">

            <tr>

                <td>
                    <strong>Дата</strong><br />

                    <input type="text" name="data1" id="data1" alt="data1" value="{{@$data1}}" style="width:100px"> <br />

                    <input type="text" name="data2" id="data2" alt="data2" value="{{@$data2}}" style="width:100px">
                </td>

                <td style="padding:5px;">

                    <strong>Название</strong><br />

                    <input type="text" name="name" id="name" value="{{@$name}}" style="padding:2px; font-size:12px; height:auto; width:150px;">

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

                        @foreach($cabinet1 as $ca)
                            <option value="{{$ca->nomer}}" {{isset($isncab)?(in_array($ca->nomer, $isncab)?'selected':''):''}}>{{$ca->nomer}}</option>
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

            <th width="150" style="white-space:nowrap" align="center">Мед. центр до</th>

            <th align="center">Кабинет до</th>

            <th width="150" style="white-space:nowrap" align="center">Мед. центр после</th>

            <th align="center">Кабинет после</th>

            <th align="center" style="white-space:nowrap">Наименование оборудование</th>

            <th align="center" style="white-space:nowrap">Дата перемещение</th>

            <th align="center">Пользователь</th>

        </tr>
        </thead>
        <tbody>
        @foreach($pere as $c)

            <tr>

                <td align="center" style="padding:5px;">{{$c->ce1name}}</td>

                <td align="center" style="padding:5px;">{{get_cabinet($c->pidcab1, 'nomer').'('.get_cabinet($c->pidcab1, 'deayt').')'}}</td>

                <td align="center" style="padding:5px;">{{$c->cename}}</td>

                <td align="center" style="padding:5px;">{{get_cabinet($c->pidcab, 'nomer').'('.get_cabinet($c->pidcab, 'deayt').')'}}</td>

                <td align="center" style="padding:5px;">{{get_razdel($c->ids2, 'name')}}</td>

                <td align="center" style="padding:5px;">{{$c->pdate}}</td>

                <td align="center" style="padding:5px;">{{$c->id_user!=0?\App\User::get_user($c->id_user)->fio:''}}</td>

            </tr>

        @endforeach
        </tbody>
    </table>
    <div class="pagination" style="text-align:center"><ul>
            {!!$pag!!}

        </ul></div>
@endsection