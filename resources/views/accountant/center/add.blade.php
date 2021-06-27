@extends('layouts.app1')

@section('content')

    <div class="tabbable tabs-top">
    <ul class="nav nav-tabs">
        <li ><a href="{{route('accountant.center.index')}}">Список центров</a></li>
        <li class="active"><a href="{{route('accountant.center.create')}}">Добавление центра</a></li>
        <li ><a href="/accountant/center/edit">Редактирование центра</a></li>
        <li ><a href="{{route('option.list', ['table' =>'center'])}}">Настройка полей</a></li>


    </ul>
        <img src="{{asset('upload/images/51028_983360_17.jpg')}}" alt="">
    <div class="tab-content">

        <form method="post" rel="ajax_form" action="{{route('accountant.center.store')}}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="add" value="1">
            <table class="table-striped" width="100%">
            @foreach($option as $op)
                <tr>
                <td align="left">{{$op->name_ru}}{!!$op->required_fields==1?'<span style="color:red">*</span>':''!!}</td>
                <td>
                    {!!html_entity_decode(input_view($op->id,'med_centers','','','',1))!!}
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
