@extends('layouts.app1')
@section('content')
    <div class="tabbable tabs-top">

        <ul class="nav nav-tabs">

            <li ><a href="{{route('module.remont.index')}}">Подать заявку</a></li>

            <li class="active"><a href="{{route('module.remont.add_order')}}">Новая заявка</a></li>

            <li ><a href="{{route('module.remont.added')}}">Заявки</a></li>

            <li><a href="{{route('module.remont.archive')}}">Архив</a></li>

        </ul>

        @if(isset($success) && $success!='')
            <div class="alert alert-success">
                {{$success}}
            </div>
        @endif

        <script>
            function show_cab(){

                var id = $('#idcen').val();
                $.ajax({
                    url: "{{ route('api.order.add') }}",
                    method: 'POST',
                    data: {id: id, classes: 1, _token: $('input[name="_token"]').val()},
                    dataType: 'json',
                    success: function (data) {
                        $('#idcab').html(data.str);

                        $('#idcab').change();

                    }
                });
            }
            function show_cat(){

                var id = $('#idcab').val();
                $.ajax({
                    url: "{{ route('api.order.add') }}",
                    method: 'POST',
                    data: {id: id, classes: 2, _token: $('input[name="_token"]').val()},
                    dataType: 'json',
                    success: function (data) {
                        $('#idcat').html(data.str);

                    }
                });
            }
        </script>
        <div class="tab-content">
            <form method="post" action="{{route('module.remont.store')}}" rel="ajax_form">
                @csrf
                <input type="hidden" name="cat_id">
                <table class="table-striped table-hover table-border" width="100%">
                    <tr>
                        <td> Поставщик <span style="color:red">*</span></td>
                        <td><select name="idser" id="idser" alt="" rel="req">
                                @foreach($service as $s)
                                    <option value="{{$s->id_user}}">{{$s->name}}</option>
                                @endforeach
                            </select></td>
                    </tr>
                    <tr>
                        <td> Центр <span style="color:red">*</span></td>
                        <td><select name="idcen" id="idcen" onchange="show_cab()" alt="" rel="req">
                                @foreach($center as $c)
                                    <option value="{{$c->id}}">{{$c->name}}</option>
                                @endforeach
                            </select></td>
                    </tr>
                    <tr>
                        <td> Кабинет <span style="color:red">*</span></td>
                        <td><select name="idcab" id="idcab" onchange="show_cat()" alt="" rel="req">
                                <option value="">Выберите кабинет</option>
                            </select></td>
                    </tr>
                    <tr>
                        <td> Оборудование <span style="color:red">*</span></td>
                        <td><select name="idcat" id="idcat" alt="" rel="req">
                                <option value="">Выберите оборудование</option>
                            </select></td>
                    </tr>

                    <tr>
                        <td> Описание неисправностей <span style="color:red">*</span></td>
                        <td><textarea name="comm" id="comm" alt="" rel="req"></textarea></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="button" id="send" value="Подать заявку" class="btn"></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
@endsection