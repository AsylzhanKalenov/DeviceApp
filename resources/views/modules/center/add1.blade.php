@extends('layouts.app1')

@section('content')

    <div class="tabbable tabs-top">
        <ul class="nav nav-tabs">
            <li ><a href="{{route('center.index')}}">Список центров</a></li>
            <li class="active"><a href="{{route('center.create')}}">Добавление центра</a></li>
            <li ><a href="/admin/center/edit">Редактирование центра</a></li>
            <li ><a href="{{route('option', ['table' =>'center'])}}">Настройка полей</a></li>


        </ul>
        <div class="tab-content">

            <form method="post" rel="ajax_form" action="{{route('center.store')}}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="add" value="1">
                <table class="table-striped" width="100%">

                    <tr>
                        <td align="left">Наименование<span style="color:red">*</span></td>
                        <td>
                            {!!html_entity_decode(input_view(3,'med_centers','','','',1))!!}
                        </td>
                    </tr>
                    <tr>
                        <td align="left">Логотип<span style="color:red">*</span></td>
                        <td>
                            <input name="logo" type="file" />
                            <br>форматы(jpg|gif|png|jpeg|JPG)/размер(3000000 bytes)'
                        </td>
                    </tr>
                    <tr>
                        <td align="left">Фактический адрес<span style="color:red">*</span></td>
                        <td>
                    <textarea name="fact" cols="30"
                              rows="5"  alt=""></textarea><br /><span class="error" id="err_fact"></span>
                        </td>
                    </tr>

                    <tr>
                        <td align="left">Юридический адрес<span style="color:red">*</span></td>
                        <td>
                <textarea name="uadr" cols="30"
                          rows="5"  alt=""></textarea><br /><span class="error" id="err_uadr"></span>
                        </td>
                    </tr>

                    <tr>
                        <td align="left">Телефоны<span style="color:red">*</span></td>
                        <td>
                <textarea name="phones" cols="30"
                          rows="5"  alt=""></textarea><br /><span class="error" id="err_uadr"></span>
                        </td>
                    </tr>

                    <tr>
                        <td align="left">Реквизиты<span style="color:red">*</span></td>
                        <td>
                <textarea name="req" cols="30"
                          rows="5"  alt=""></textarea><br /><span class="error" id="err_req"></span>
                        </td>
                    </tr>

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
