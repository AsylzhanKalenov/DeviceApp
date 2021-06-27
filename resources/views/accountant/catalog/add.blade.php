@extends('layouts.app1')

@section('content')
<div class="tabbable tabs-top">
<ul class="nav nav-tabs">

    <li ><a href="{{route('accountant.catalog.index')}}">Список оборудования</a></li>
    <li class="active"><a href="{{route('accountant.catalog.create')}}">Добавление оборудования</a></li>
    <li ><a href="/accountant/catalog/edit">Редактирование оборудования</a></li>

</ul>
    <div class="tab-content">

<script>

    $(function(){
        show_class1();
        show_cab();
    })

    function show_class1(){

        var id = $('#classe').val();
        $.ajax({
            url: "{{ route('api.catalog.add') }}",
            method: 'POST',
            data: {id: id, classes: 1, _token: $('input[name="_token"]').val()},
            dataType: 'json',
            success: function (data) {
                $('#classe1').html(data.str);

                $('#classe1').change();

                $('#class').val($('#classe option:selected').html());
        }
    });

    }
    function show_class2(){


        var id = $('#classe1').val();
        $.ajax({
            url: "{{ route('api.catalog.add') }}",
            method: 'POST',
            data: {id: id, classes: 2, _token: $('input[name="_token"]').val()},
            dataType: 'json',
            success: function (data) {
                $('#classe2').html(data.str);

                $('#classe2').change();

                $('#class1').val($('#classe1 option:selected').html());
            }
        });

    }

    function show_class3(){


        var id = $('#classe2').val();
        $.ajax({
            url: "{{ route('api.catalog.add') }}",
            method: 'POST',
            data: {id: id, classes: 3, _token: $('input[name="_token"]').val()},
            dataType: 'json',
            success: function (data) {
                $('#class2').val($('#classe2 option:selected').html());

                $('#classe3').html(data.str);

                $('#classe3').change();

            }
        });

    }
    function show_class4(){



        var id = $('#classe3').val();
        $.ajax({
            url: "{{ route('api.catalog.add') }}",
            method: 'POST',
            data: {id: id, classes: 5, _token: $('input[name="_token"]').val()},
            dataType: 'json',
            success: function (data) {
                $('#class3').val($('#classe3 option:selected').html());

                $('#classe4').html(data.str);

                $('#classe4').change();

            }
        });



    }

    function show_class5(){
        $('#class4').val($('#classe4 option:selected').html());
    }

    function show_cab(){


        var id = $('#ids').val();
        $.ajax({
            url: "{{ route('api.catalog.add') }}",
            method: 'POST',
            data: {id: id, classes: 4, _token: $('input[name="_token"]').val()},
            dataType: 'json',
            success: function (data) {
                $('#ncab').html(data.str);

                $('#ncab').change();
            }
        });

    }

    function add_name_sel(){
        if ($('#name_sel').val()!='')
        {
            $('input[name=name]').val($('#name_sel').val());
        }
    }

    function add_model_sel(){
        if ($('#model_sel').val()!='')
        {
            $('input[name=model]').val($('#model_sel').val());
        }
    }

</script>
        <form method="post" rel="ajax_form" action="{{route('module.catalog.store')}}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="add" value="1">
            <table class="table-striped" width="100%">
                <tr>
                    <td>
                        Классификация <span style="color:red">*</span>
                    </td>
                    <td>
                        <input type="text" alt="" rel="req" value="" size="30" id="class" name="class">
                        <br />

                        <select name="classe" id="classe" onchange="show_class1()" alt="" >

                            @foreach($razdel as $r)
                                <option value="{{$r->id}}" >{{$r->name}}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        Категория <span style="color:red">*</span>
                    </td>
                    <td>
                        <input type="text" alt="" rel="req" value="" size="30" id="class1" name="class1">
                        <br />
                        <select name="classe1" id="classe1" onchange="show_class2()" alt="" >
                            <option value="">Выберите классификацию</option>

                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        Наименование <span style="color:red"></span>
                    </td>
                    <td>
                        <input type="text" alt="" rel="req" value="" size="30" id="class2" name="class2">
                        <br />
                        <select name="classe2" id="classe2" onchange="show_class3()" alt="" rel="">
                            <option value="">Выберите категорию</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        Модель <span style="color:red"></span>
                    </td>
                    <td>
                        <input type="text" alt="" rel="req" value="" size="30" id="class3" name="class3">
                        <br />
                        <select name="classe3" id="classe3"  onchange="show_class4()" alt="" rel="">
                            <option value="">Выберите наименование</option>

                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        Производитель <span style="color:red"></span>
                    </td>
                    <td>
                        <input type="text" alt="" rel="req" value="" size="30" id="class4" name="class4">
                        <br />
                        <select name="classe4" id="classe4"  alt="" rel=""  onchange="show_class5()">
                            <option value="">Выберите модель</option>
                        </select>
                    </td>
                </tr>

                @foreach($option as $o)

                @if($o->name_en != 'name' && $o->name_en != 'model' && $o->name_en != 'proizv')
                        <tr><td align="left">{{$o->name_ru}}{!!$o->required_fields==1?'<span style="color:red">*</span>':''!!}</td>

                @if($o->name_en=='photo')
                    <td><iframe src="{{route('module.photo', 'file')}}" frameborder="0" style="width:100%; height:400px;"></iframe>
                @else
                    <td>{!!html_entity_decode(input_view($o->id,'catalog','','','',1))!!}
                @endif
                    </td></tr>
                @endif
                @if($o->name_en=='in_nom')
                        <tr>
                            <td>
                                Медицинский центр владелец <span style="color:red">*</span>
                            </td>
                            <td>
                                <select name="ids1" id="ids1">
                                    @foreach($center as $c)
                                        <option value="{{$c->id}}">{{$c->name}}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Медицинский центр пользователь<span style="color:red">*</span>
                            </td>
                            <td>
                                <select name="ids" id="ids" onchange="show_cab()">
                                    @foreach($center as $c)
                                        <option value="{{$c->id}}">{{$c->name}}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Кабинет <span style="color:red">*</span>
                            </td>
                            <td>
                                <select name="ncab" id="ncab" >
                                    @foreach($cabinet as $ca)
                                        <option value="{{$ca->id}}">{{$ca->deayt.' '.$ca->nomer}}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                @endif

                @endforeach


                <tr>
                    <td>
                    </td>
                    <td>
                        <input type="hidden" id="files"  name="photo" value="">
                        <input type="submit" id="send" value="Добавить" class="btn">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
    @endsection
