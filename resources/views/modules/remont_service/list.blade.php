<script src="/js/jquery-1.4.2.min.js"></script>
<script>

    $(function(){

        show_cab();

        $('input[type=reset]').click(function(){
            $('form input[type=text]').removeAttr("value");
            $('form select').val("clear");
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

        <li class="active"><a href="/service/remont">Заявки</a></li>

        <li ><a href="/service/remont/work">В работе</a></li>

        <li><a href="/service/remont/worked">Отработанные</a></li>
        <li><a href="/service/remont/unworked">Списанное оборудование</a></li>

        <li><a href="/service/remont/archive">Архив</a></li>



    </ul>

    <div class="tab-content">
        <form name="form" method="post" action="/service/remont" rel="ajax_form">
            <input type="hidden" name="search" value="1">
            <table width="100%">
                <tr>
                    <td style="padding:5px;">
                        <strong>Штрих код</strong><br />
                        <input type="text" name="ser" id="ser" value="<?=@$_SESSION["ser"]?>" style="padding:2px; font-size:12px; height:auto; width:150px;">
                    </td>
                    <td style="padding:5px;">
                        <strong>Название</strong><br />
                        <input type="text" name="name" id="name" value="<?=@$_SESSION["name"]?>" style="padding:2px; font-size:12px; height:auto; width:150px;">
                    </td>
                    <td style="padding:5px;">
                        <strong>Мед. центр</strong><br />
                        <select name="center" id="center" style="width:150px;padding:2px; font-size:12px; height:auto;" onchange="show_cab()">
                            <option value="clear"></option>
                            @foreach($center as $c)

                                <option value="'.$r["id"].'" '.(@$_SESSION["center"]==$r["id"]?'selected':'').'>'.$r["name"].'</option>
                            @endforeach
                        </select>
                    </td>
                    <td style="padding:5px;">
                        <strong>Номер кабинета</strong><br />
                        <select name="ncab" id="ncab" style="width:150px;padding:2px; font-size:12px; height:auto;" >
                            <option value="clear"></option>