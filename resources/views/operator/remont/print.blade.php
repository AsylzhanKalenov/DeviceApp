<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>


<h1 align="center">ЗАЯВКА
    <br />На ремонт оборудования
</h1>
</div>
<p><b> Заказчик: </b> </p>
<p>Наименование организации:  <span>{{get_center($catalog->idc, 'name')}}</span></p>
<p>Адрес: <span>{{get_center($catalog->idc, 'fact')}}</span></p>
<p>Контактное лицо: <span>{{get_center($catalog->idc, 'fio')}}</span> Тел\факс: <span>{{get_center($catalog->idc, 'phones')}}</span></p>
<p>Пользователь: <span>{{\App\User::get_user($remont->id_user)->fio}}</span></p>
<p>Отделение: _____________________________________________________ № каб.: <span>{{get_cabinet($catalog->idcab, 'nomer').' ('.get_cabinet($catalog->idcab, 'deayt')}})</span></p>
<p><b>Оборудование:</b></p>
<p>Наименование оборудования: <span>{{get_razdel($catalog->ids2, 'name')}}</span></p>
<p>Производитель: <span>{{\App\Razdel::query()->where('ids', get_razdel($catalog->ids3, 'id'))->select('name')->first()->name}}</span></p>
<p>Модель: <span>{{get_razdel($catalog->ids3, 'name')}}</span></p>
<p>Серийный номер: <span>{{$catalog->ser_nom}}</span></p>
<p>Гарантия (нужное выделить): <span>{{$catalog->gar_per.'-'.$catalog->gar_per1}}</span></p>
<p>Описание работ: <span>{{$remont->comm}}</span><br>
</p>


<style>
    span{
        text-decoration: underline;
    }
    .leftstr, .rightstr {
        float: left; /* Обтекание справа */
        width: 50%; /* Ширина текстового блока */
    }
    .rightstr {
        float: left;
    }
</style>
</head>
<body> 		<p class="leftstr"><b>Представитель заказчика, оформивший заявку:</b></p>
<p class="rightstr"><b>Представитель сервисного центра:*</b></p>
<p class="leftstr">Ф.И.О.: {{\App\User::get_user($remont->id_user)->fio}}</p>
<p class="rightstr">Ф.И.О.: {{get_service($remont->idser, 'lico')}}</p>
<p class="leftstr">Подпись: ________________</p>
<p class="rightstr">Ф.И.О.: ________________</p>
<p class="leftstr">МП: ________________</p>
<p class="rightstr">МП: ________________</p>
<div style="clear: left"></div>

</section>
</body>
</html>

