@extends('layouts.app1')
@section('content')
<script>



    $(function(){



        show_cab();



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



</script>

<div class="tabbable tabs-top">

    <ul class="nav nav-tabs">

        <li class="active"><a href="{{route('operator.remont.added')}}">Заявки</a></li>

        <li ><a href="{{route('operator.remont.add_order')}}">Новая заявка</a></li>

        <li><a href="{{route('operator.remont.archive')}}">Архив</a></li>



    </ul>

    <div class="tab-content">

        <form name="form" method="get" action="{{route('operator.remont.added')}}" rel="ajax_form">

            <input type="hidden" name="search" value="1">

            <table width="100%">

                <tr>

                    <td style="padding:5px;">

                        <strong>Штрих код</strong><br />

                        <input type="text" name="ser" id="ser" value="<?=@$_SESSION["ser"]?>" style="padding:2px; font-size:12px; height:auto; width:150px;">

                    </td>

                    <td style="padding:5px;">

                        <strong>Номер</strong><br />

                        <input type="text" name="in_nomer" id="in_nomer" value="{{isset($in_nom)?$in_nom:''}}" style="padding:2px; font-size:12px; height:auto; width:150px;">

                    </td>

                    <td style="padding:5px;">

                        <strong>Мед. центр</strong><br />

                        <select name="center" id="center" style="width:150px;padding:2px; font-size:12px; height:auto;" onchange="show_cab()">
                            <option value=""></option>
                            @foreach($center as $c)
                                <option value="{{$c->id}}" {{isset($sel_center)?($sel_center==$c->id?'selected':''):''}}>{{$c->name}}</option>
                            @endforeach
                        </select>

                    </td>

                    <td style="padding:5px;">

                        <strong>Номер кабинета</strong><br />

                        <select name="ncab[]" multiple="multiple" size="4" id="ncab" style="width:150px;padding:2px; font-size:12px; height:auto;" >

                            <option value=""></option>
                            @foreach($cabinet1 as $ca)
                                <option value="{{$ca->nomer}}" {{isset($sel_cab)?(in_array($ca->nomer, $sel_cab)?'selected':''):''}}>{{$ca->nomer}}</option>
                            @endforeach

                        </select>

                    </td>



                    <td style="padding:5px; display:none">

                        <strong>Мед. направление</strong><br />

                        <select name="deayt" id="deayt" style="width:150px;padding:2px; font-size:12px; height:auto;" >

                            <option value=""></option>
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

                        <input type="submit" value="Поиcк" class="btn" style="width:100px">

                        <input type="reset" value="Очиcтить" class="btn" style="width:100px">



                    </td>



                </tr>

            </table>



        </form>

        <table class="table-striped table-hover table-border" width="100%">

            <tr>

                <th width="60" align="center" style="white-space:nowrap">Штрих код</th>

                <th width="60" align="center" style="white-space:nowrap">Дата</th>

                <th width="150" style="white-space:nowrap" align="center">Мед. центр</th>

                <th align="center">Кабинет</th>

                <th align="center" style="white-space:nowrap">Наименование</th>

                <th align="center" style="white-space:nowrap">Модель</th>

                @foreach($option as $o)
                    <th align="center" style="white-space:nowrap">{{$o->name_ru}}</th>
                @endforeach

                <th align="center" width="70">Статус</th>
                <th align="center" width="70">Дейcтвия</th></tr>

            @foreach($catalog as $c)

                <tr>

                    <td align="center" style="padding:5px; padding-left:10px;">{{$c->cid}}</td>
                    <td align="center" style="padding:5px; padding-left:10px;">{{$c->rdata}}</td>


                    <td align="center" style="padding:5px;">{{get_center($c->idc, 'name')}}</td>

                    <td align="center" style="padding:5px;">{{get_cabinet($c->idcab, 'nomer').'('.get_cabinet($c->idcab, 'deayt').')'}}</td>

                    <td align="center" style="padding:5px;">{{get_razdel($c->ids2, 'name')}}</td>

                    <td align="center" style="padding:5px;">{{get_razdel($c->ids3, 'name')}}</td>
                    @foreach($option as $o)
                        <td align="center" style="padding:5px;">{{$c['c'.$o->name_en]}}</td>
                    @endforeach

                    <td align="center" style="padding:5px;">{{$c->rstat}}</td>
                    <td align="right" width="70">


                        <a href="{{route('operator.remont.show_added', $c->rid)}}" title="Просмотреть"><i class="icon-circle-arrow-up"></i></a> | <a href="{{route('operator.remont.print', $c->rid)}}" title="Печать"><i class="icon-print"></i></a>

                    </td>
                </tr>

            @endforeach
        </table>

    </div></div>
        @endsection
