@extends('layouts.app1')

@section('content')
<script>
    function get_ser() {

        var user = $('#user').val();
        var data1 = $('#data1').val();
        var data2 = $('#data2').val();

        $.ajax({
            url: "{{ route('admin.log.search') }}",
            method: 'POST',
            data: {user: user, data1: data1, data2: data2, _token: $('input[name="_token"]').val()},
            dataType: 'json',
            success: function (data) {
                $('#log_table tbody').html(data.str);
                $('.pagination ul').html(data.pag);
                $('#user1').val(user);
                $('#data11').val(data1);
                $('#data22').val(data2);
            }
        })

    }
    function get_ser_page(num) {

        var user = $('#user').val();
        var data1 = $('#data1').val();
        var data2 = $('#data2').val();

        $.ajax({
            url: "{{ route('admin.log.search') }}",
            method: 'POST',
            data: {user: user, data1: data1, data2: data2, num: num, _token: $('input[name="_token"]').val()},
            dataType: 'json',
            success: function (data) {
                $('#log_table tbody').html(data.str);
                $('.pagination ul').html(data.pag);
            }
        })

    }
</script>

    <form action="" method="post" rel="ajax_form">
    Пользователь
    <input type="text" name="user"   id ="user" alt="user" value="{{@$data["user"]}}" style="width:200px">
    Дата
    <input type="text" name="data1" id="data1" alt="data1" value="{{@$data["data1"]}}" style="width:100px">
    -
    <input type="text" name="data2" id="data2" alt="data2"  value="{{@$data["data2"]}}" style="width:100px">
    <input type="button" onclick="get_ser()" value="Показать" class="btn" style="margin-top:-12px;">
</form>
<table id = "log_table" class="table-striped" width="100%">
    <thead>
    <tr>
        <th>Дата</th>
        <th>Пользователь</th>
        <th>Центр</th>
        <th>Инфо</th>
        <th>Действие</th>
    </tr>
    </thead>
    <tbody>
    @foreach($log as $l)
        <tr><td>{{$l["date"]}}</td><td>{{$l["user"]}}</td><td>{{$l["center"]}}</td><td>{{$l["action"]}}</td><td>{{$l["vid"]}}</td></tr>
    @endforeach
    </tbody>
</table>

<input type="hidden" id="user1">
<input type="hidden" id="data11">
<input type="hidden" id="data22">

<div class="pagination" style="text-align:center"><ul>

    </ul></div>

<script type="text/javascript">

</script>
@endsection