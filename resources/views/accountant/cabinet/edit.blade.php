@extends('layouts.app1')

@section('content')

    <div class="tabbable tabs-top">
        <ul class="nav nav-tabs">
            <li ><a href="{{route('accountant.cabinet.index')}}">Список кабинетов</a></li>

            <li ><a href="{{route('accountant.cabinet.create')}}">Добавление кабинета</a></li>


            <li class="active"><a href="/accountant/cabinet/edit">Редактирование кабинета</a></li>



        </ul>
        <div class="tab-content">

            <form method="post" rel="ajax_form" action="{{route('accountant.cabinet.update', $id->id)}}" enctype="multipart/form-data">
                @csrf
                {{ method_field('PATCH') }}
                <input type="hidden" name="add" value="1">
                <table class="table-striped" width="100%">

                    <tr>

                        <td>

                            Медицинский центр <span style="color:red">*</span>

                        </td>

                        <td>

                            <select name="ids" id="ids">

                                @foreach($center as $c)
                                    <option value="{{$c->id}}" {{$id->id_center==$c->id?'selected':''}}>{{$c->name}}</option>
                                @endforeach

                            </select>

                        </td>

                    </tr>
                    @foreach($option as $op)
                        <tr>
                            <td align="left">{{$op->name_ru}}{!!$op->required_fields==1?'<span style="color:red">*</span>':''!!}</td>
                            <td>
                                {!!html_entity_decode(input_view($op->id,'cabinet','id',$id->id,''))!!}
                            </td>
                        </tr>
                    @endforeach

                    <tr>
                        <td>
                        </td>
                        <td>
                            <input type="submit" id="send" value="Добавить" class="btn">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
@endsection
