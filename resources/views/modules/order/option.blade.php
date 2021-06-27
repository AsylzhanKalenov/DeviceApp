@extends('layouts.app1')


@section('content')

    <div class="tabbable tabs-right">
        <ul class="nav nav-tabs">
            <li ><a href="{{route('module.order.index')}}">Список заявок</a></li>

            <li ><a href="{{route('module.order.create')}}">Добавление заявки</a></li>

            <li><a href="/admin/order/edit">Редактирование заявки</a></li>

            <li class="active"><a href="{{route('option.list', 'order')}}">Настройка полей</a></li>

        </ul>
        <div class="tab-content">

            <script>
                function select_type(id)
                {
                    var mas=new Array('i0','i1','i2','i3','i4','i5','i6','i7','i8','i9','i10','i11','i12');
                    //var div = document.getElementById(id);

                    for(var i=0;i<mas.length;i++)
                    {
                        var div = document.getElementById(mas[i]);
                        if(mas[i]==id)
                        {
                            div.style.display='block';
                        }else
                        {
                            div.style.display='none';
                        }
                    }

                    if(id == 'i10' || id == 'i11' || id == 'i12'){
                        document.getElementById('required_fields').disabled = 'disabled'
                    }else{
                        document.getElementById('required_fields').disabled = ''
                    }

                }
            </script>
            <form id="form" name="form" method="post" action="{{isset($val->id)?route('option.update', $val->id):route('option.add_place')}}" rel="ajax_form"  enctype="multipart/form-data" >
                @csrf
                <input type="hidden" name="category" value="order">
                <input type="hidden" name="category_id" value="0">
                <input type="hidden" name="idf" value="">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="center"  style="border: 0px solid #c4c5a6;"><table width="97%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td height="10"></td>
                                </tr>
                                <tr>
                                    <td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                            <tr>
                                                <td align="right" class="small_text">Обязательное поле для заполнения:</td>
                                                <td align="left"><input name="required_fields" type="checkbox" id="required_fields" value="1" ></td>
                                            </tr>
                                            <tr>
                                                <td align="right" class="small_text">Выделить поле:</td>
                                                <td align="left"><input name="select_fields" type="checkbox" id="select_fields" value="1" ></td>
                                            </tr>

                                            <tr>
                                                <td width="41%" align="right" class="small_text">ID сортировки:</td>
                                                <td width="59%" align="left"><input name="id_sort" type="text" id="id_sort" value="{{isset($val->id_sort)?$val->id_sort:''}}" size="35" /></td>
                                            </tr>
                                            <tr>
                                                <td align="right" class="small_text"><span class="small_red_text">*</span> Наименование поля:</td>
                                                <td align="left"><input name="name_ru" type="text" id="name_ru" rel="req" alt="" value="{{isset($val->name_ru)?$val->name_ru:''}}" size="35" />
                                                    <br>
                                                    <span class="error" id="err_name_ru"></span></td>
                                            </tr>
                                            <tr style="{{isset($val->name_en)?'display: none;':''}}">
                                                <td align="right" class="small_text"><span class="small_red_text">*</span> Наименование поля (eng):</td>
                                                <td align="left"><input name="name_en" type="text" id="name_en" rel="req" alt="" value="{{isset($val->name_en)?$val->name_en:''}}" size="35" />
                                                    <br>
                                                    <span class="error" id="err_name_en"></span></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" align="center" class="small_text">(поле не должно содержать спец. символы и его длина не должна превышать 30 символов)</td>
                                            </tr>
                                            <tr>
                                                <td align="right" class="small_text"><span class="small_red_text">*</span> Тип поля:</td>
                                                <td align="left"><select name="type_field" id="type_field" rel="req" alt="" {{isset($val->id)?'disabled':''}} onChange="select_type(this.value)">
                                                        @foreach($ar as $k=>$v)
                                                            {
                                                            <option value="{{$k}}" {{isset($val->type_field)?($k == $val->type_field?'selected':''):''}}>{{$v}}</option>
                                                            }
                                                        @endforeach
                                                    </select>
                                                    <br>
                                                    <span class="error" id="err_type_field"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" align="center" class="small_text">
                                                    <div id="i0" style="display:none"></div>
                                                    <div id="i1" >
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                            <tr>
                                                                <td width="41%" align="right" class="small_text">Длина поля:</td>
                                                                <td width="59%" align="left"><input name="i1_width" type="text" id="i1_width" value="{{isset($val->width)?$val->width:''}}" size="35" /></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div id="i2" >
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                            <tr>
                                                                <td width="41%" align="right" class="small_text">Длина поля:</td>
                                                                <td width="59%" align="left"><input name="i2_width" type="text" id="i2_width" value="" size="35" /></td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right" class="small_text">Формат даты:</td>
                                                                <td align="left"><input name="i2_format_date" type="text" id="i2_format_date" value="Y-m-d H:i:s" size="35" disabled="disabled" /></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div id="i3" >
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                            <tr>
                                                                <td width="41%" align="right" class="small_text">Длина поля:</td>
                                                                <td width="59%" align="left"><input name="i3_width" type="text" id="i3_width" value="" size="35" /></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2" align="center" class="small_text">(данный тип поля шифруется)</td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div id="i4" >
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                            <tr>
                                                                <td width="41%" align="right" class="small_text">Длина поля:</td>
                                                                <td width="59%" align="left"><input name="i4_width" type="text" id="i4_width" value="" size="35" /></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2" align="center" class="small_text">(данный тип поля шифруется)</td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div id="i5" >
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                            <tr>
                                                                <td width="41%" align="right" class="small_text">Длина поля:</td>
                                                                <td width="59%" align="left"><input name="i5_width" type="text" id="i5_width" value="" size="35" /></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div id="i6" >
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                            <tr>
                                                                <td width="41%" align="right" class="small_text">Длина:</td>
                                                                <td width="59%" align="left"><input name="i6_width" type="text" id="i6_width" value="" size="45" /></td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right" class="small_text">Высота:</td>
                                                                <td align="left"><input name="i6_height" type="text" id="i6_height" value="" size="35" /></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div id="i7" >
                                                    </div>
                                                    <div id="i8" >
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                            <tr>
                                                                <td width="41%" align="right" class="small_text">Длина поля:</td>
                                                                <td width="59%" align="left"><input name="i8_width" type="text" id="i8_width" value="" size="35" /></td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right" class="small_text">Элементы выпадающего списка:</td>
                                                                <td align="left"><textarea name="i8_select_elements" id="i8_select_elements" cols="45" rows="5"></textarea></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2" align="center" class="small_text"></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div id="i9" >
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="4">

                                                            <tr>
                                                                <td width="41%" align="right" class="small_text">Элементы списка:</td>
                                                                <td width="59%" align="left"><textarea name="i9_select_elements" id="i9_select_elements" cols="45" rows="5"></textarea></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2" align="center" class="small_text"></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div id="i10" >
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                            <tr>
                                                                <td width="41%" align="right" class="small_text">Длина:</td>
                                                                <td width="59%" align="left"><input name="i10_width" type="text" id="i10_width" value="" size="35" /></td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right" class="small_text">Высота:</td>
                                                                <td align="left"><input name="i10_height" type="text" id="i10_height" value="" size="35" /></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div id="i11" >
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                            <tr>
                                                                <td width="41%" align="right" class="small_text">Максимальный размер (в байтах):</td>
                                                                <td width="59%" align="left"><input name="i11_size_file" type="text" id="i11_size_file" value="" size="35" /></td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right" class="small_text">Форматы изображений:</td>
                                                                <td align="left"><input name="i11_format_file" type="text" id="i11_format_file" value="jpg|gif|png|jpeg|JPG" size="35" disabled/></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2" align="center" class="small_text">(каждый формат добавляется с разделителем |)</td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right" class="small_text">Тип обработки изображения:</td>
                                                                <td align="left"><select name="type_resize" >
                                                                        <option value="auto">Авто</option>
                                                                        <option value="landscape">По ширине</option>
                                                                        <option value="portrait" >По высоте</option>
                                                                        <option value="exact">Точная</option>
                                                                        <option value="crop">Обрезка</option>
                                                                    </select>

                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right" class="small_text">Размеры миниатюры:</td>
                                                                <td align="left">
                                                                    <table>
                                                                        <tr>
                                                                            <td>Ширина: <br />
                                                                                <input type="text" name="w_resize_small" size="10" value=""> <br />
                                                                                Высота:<br />

                                                                                <input type="text" name="h_resize_small" size="10" value="">
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right" class="small_text">Размеры основного изображения:</td>
                                                                <td align="left">
                                                                    <table>
                                                                        <tr>
                                                                            <td>Ширина:<br />
                                                                                <input type="text" name="w_resize_big" size="10" value=""> <br />
                                                                                Высота:<br />

                                                                                <input type="text" name="h_resize_big" size="10" value="">
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>


                                                        </table>
                                                    </div>
                                                    <div id="i12" ><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                            <tr>
                                                                <td width="41%" align="right" class="small_text">Максимальный размер (в байтах):</td>
                                                                <td width="59%" align="left"><input name="i12_size_file" type="text" id="i12_size_file" value="" size="35" /></td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right" class="small_text">Форматы файлов:</td>
                                                                <td align="left"><input name="i12_format_file" type="text" id="i12_format_file" value="" size="35" /></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2" align="center" class="small_text"></td>
                                                            </tr>
                                                        </table>
                                                    </div>                    </td>
                                            </tr>

                                        </table></td>
                                </tr>
                                <tr>
                                    <td height="10"></td>
                                </tr>
                            </table></td>
                    </tr>
                    <tr>
                        <td height="10" align="center" ></td>
                    </tr>
                    <tr>
                        <td align="left"  style="border: 0px solid #c4c5a6;"><table  align="left" width="100%" border="0" cellspacing="4" cellpadding="0">
                                <tr>
                                    <td align="center"><input type="submit" name="button" id="send" class="btn" value="Сохранить"  /></td>

                                </tr>
                            </table>


                        </td>
                    </tr>



                </table>
            </form>
            <script>
                $(function() {
                    @if(isset($val->id))
                    {{--select_type({{$val->type_field}});--}}
                    $('#type_field').change();
                    @endif
                })
            </script>

            <div style="margin-top:20px;">
                <table class="table table-hover" style="font-size:12px;">

                    <tr>
                        <th>
                            ID сортировки
                        </th>
                        <th>
                            Наименование поля
                        </th>
                        <th>
                            Наименование поля (eng)
                        </th>
                        <th>
                            Тип поля
                        </th>
                        <th>
                            Действия
                        </th>
                    </tr>

                    @foreach($option as $op)

                        <tr>
                            <td>
                                {{$op->id_sort}}
                            </td>
                            <td>
                                {{$op->name_ru}}
                            </td>
                            <td>
                                {{$op->name_en}}
                            </td>
                            <td>
                                {{$ar[''.$op->type_field.'']}}
                            </td>
                            <td>
                                <a href="{{route('option.edit', $op->id)}}">Редактировать</a> | <a href="{{route('option.delete', $op->id)}}">Удалить</a>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection