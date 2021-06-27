@extends('layouts.app1')
@section('content')

<div class="tabbable tabs-top">
    <ul class="nav nav-tabs">
        <li class="active"><a href="/admin/category/list">Список категорий</a></li>
        <li ><a href="/admin/category/option">Настройка полей</a></li>

    </ul>
    <div class="tab-content">

        <table width="100%" class="table">

            <tr><td width="50%" align="right" style=""><strong>Добавить категорию</strong><br />

                    <br />

                    <h4>Категории</h4>





                </td><td><form method="post" style=" margin:0px;" action=""><input type="hidden" name="ids" value="0"><input name="cat" style="width:250px;"> <input type="submit" name="submit" value="Добавить"></form></td></tr>

            <tr><td width="50%" colspan="2" align="left" style="">

                    <form name="search_form" action="" method="get">

                        Классификация <input type="text" name="s1" style="margin-bottom:0px;"{{(isset($_GET["s1"])?'value="'.$_GET["s1"].'"':'')}}>





                        <input type="submit" name="submit" value="Найти" class="btn">



                    </form>



                </td>

            </tr>

        @foreach($razdel as $r)

                <tr bgcolor="#F5F5F5" onclick="show_tr({{$r->id}})"><td width="50%">{{$r->name}} | <a href="javascript:show_f({{$r->id}});">Редактировать</a> | <a href="javascript:del({{$r->id}});">Удалить</a></td><td><form style=" float:left; width:auto; margin:0px 10px 0px 0px;" action="" method="post"><input type="hidden" name="ids" value="{{$r->id}}"><input name="cat" style="width:250px;"> <input type="submit" name="submit" value="Добавить"></form>

                    Классификация

                    <form id="editf{{$r->id}}" style="margin:0px; margin-top:10px;  display:none;" action="" method="post"><input type="hidden" name="ide" value="{{$r->id}}"><input name="cate" style="width:250px;" value="{{$r->name}}"> <input type="submit" name="submit" value="Сохранить"></form>

                </td></tr>

            @foreach(\App\Razdel::query()->where('ids','=',$r->id)->get() as $rr)

                    <tr bgcolor="#EEEEEE" style="{{(isset($_GET["s1"])&& $_GET["s1"]?'':'display:none')}}" class="tr{{$r->id}}" onclick="show_tr({{$rr->id}})"><td width="50%" style=" padding-left:30px;">{{$rr->name}} | <a href="javascript:show_f({{$rr->id}});">Редактировать</a> | <a href="javascript:del({{$rr->id}});">Удалить</a></td><td><form style=" float:left; width:auto; margin:0px 10px 0px 0px;" action="" method="post"><input type="hidden" name="ids" value="{{$rr->id}}"><input name="cat" style="width:250px;"> <input type="submit" name="submit" value="Добавить"></form> Категория

                        <form id="editf{{$rr->id}}" style="margin:0px; margin-top:10px;  display:none;" action="" method="post"><input type="hidden" name="ide" value="{{$rr->id}}"><input name="cate" style="width:250px;" value="{{$rr->name}}"> <input type="submit" name="submit" value="Сохранить"></form></td></tr>

                @foreach(\App\Razdel::query()->where('ids','=',$rr->id)->get() as $rrr)

                        <tr bgcolor="#E5E5E5" style="{{(isset($_GET["s1"])&& $_GET["s1"]?'':'display:none')}}" class="tr{{$rr->id}}" onclick="show_tr({{$rrr->id}})"><td width="50%" style=" padding-left:60px;">{{$rrr->name}} | <a href="javascript:show_f({{$rrr->id}});">Редактировать</a> | <a href="javascript:del({{$rrr->id}});">Удалить</a> </td><td><form  style=" float:left; width:auto; margin:0px 10px 0px 0px;"action="" method="post"><input type="hidden" name="ids" value="{{$rrr->id}}"><input name="cat" style="width:250px;"> <input type="submit" name="submit" value="Добавить"></form> Наименование

                            <form id="editf{{$rrr->id}}" style="  margin:0px; margin-top:10px;display:none;" action="" method="post"><input type="hidden" name="ide" value="{{$rrr->id}}"><input name="cate" style="width:250px;" value="{{$rrr->name}}"> <input type="submit" name="submit" value="Сохранить"></form></td></tr>

                    @foreach(\App\Razdel::query()->where('ids','=',$rrr->id)->get() as $rrrr)

                            <tr bgcolor="#bbb" style="{{(isset($_GET["s1"]) && $_GET["s1"]!=''?'':'display:none')}}" class="tr{{$rrr}}" onclick="show_tr({{$rrrr->id}})"><td width="50%" style=" padding-left:90px;">{{$rrrr->name}} | <a target="_blank" href="/admin/category/edit/{{$rrrr->id}}">Документация</a>  | <a href="javascript:show_f({{$rrrr->id}});">Редактировать</a> | <a href="javascript:del({{$rrrr->id}});">Удалить</a></td><td>

                                <form style=" float:left; width:auto; margin:0px 10px 0px 0px;" action="" method="post"><input type="hidden" name="ids" value="{{$rrrr->id}}"><input name="cat" style="width:250px;"> <input type="submit" name="submit" value="Добавить"></form> Модель

                                <form id="editf{{$rrrr->id}}" style=" margin-top:0px; margin:0px; display:none;" action="" method="post"><input type="hidden" name="ide" value="{{$rrrr->id}}"><input name="cate" style="width:250px;" value="{{$rrrr->name}}"> <input type="submit" name="submit" value="Сохранить"></form></td></tr>

                        @foreach(\App\Razdel::query()->where('ids','=',$rrrr->id)->get() as $rrrrr)

                                <tr style="{{(isset($_GET["s1"]) && $_GET["s1"]!=''?'':'display:none')}}" class="tr{{$rrrr->id}}"><td width="50%" style=" padding-left:120px;">{{$rrrrr->name}} | <a href="javascript:show_f({{$rrrrr->id}});">Редактировать</a> | <a href="javascript:del({{$rrrrr->id}});">Удалить</a></td><td>Производитель<form id="editf{{$rrrrr->id}}" style=" margin-top:0px; margin:0px; display:none;" action="" method="post"><input type="hidden" name="ide" value="{{$rrrrr->id}}"><input name="cate" style="width:250px;" value="{{$rrrrr->name}}"> <input type="submit" name="submit" value="Сохранить"></form></td></tr>

                        @endforeach

                    @endforeach

                @endforeach

            @endforeach

        @endforeach

        </table>


            <script>
                function del(id)
                {
                    if (confirm("Вы действительно хотите удалить категорию?"))
                    {
                        location.href='/admin/category/' + id + '/del';
                    }
                }
                function show_f(id)

                {

                    $('#editf'+id).show();

                }



                function show_tr(id)

                {

                    $('.tr'+id).slideToggle('fast');

                }



            </script>
    </div>
</div>
    @endsection