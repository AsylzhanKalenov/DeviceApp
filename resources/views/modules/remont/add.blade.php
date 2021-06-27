@extends('layouts.app1')
@section('content')
<div class="tabbable tabs-top">

    <ul class="nav nav-tabs">

        <li class="active"><a href="{{route('module.remont.index')}}">Подать заявку</a></li>

        <li ><a href="{{route('module.remont.added')}}">Заявки</a></li>

        <li><a href="{{route('module.remont.archive')}}">Архив</a></li>

    </ul>

    @if(isset($success) && $success!='')
    <div class="alert alert-success">
        {{$success}}
    </div>
    @endif
    <div class="tab-content">
        <form method="post" action="{{route('module.remont.store')}}" rel="ajax_form">
            @csrf
            <input type="hidden" name="cat_id" value="{{$id}}">
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